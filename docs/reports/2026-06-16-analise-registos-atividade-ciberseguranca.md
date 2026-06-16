# Análise dos Registos de Atividade + Revisão de Cibersegurança

**Data:** 2026-06-16
**Âmbito:** Audit log (`activity_log`) + logs da aplicação (`laravel.log`), ambiente de **produção**.
**Foco:** segurança, saúde operacional, integridade do registo, e o bug de **duplicação** dos registos.
**Acesso:** SSH `root@176.126.87.120` → container Laravel `o4ck0w8woo4s88gg4gkg04gs-200015259522` (a correr commit `0c7a6f8`, ou seja `main` atual) e MySQL `praia_do_norte` (container `gowgkc4s8wwck0kw0wk4k48o`). Todas as consultas a produção foram **read-only**.

---

## Sumário executivo

> **Estado pós-execução (2026-06-16):** fixes A1/B1/B2 + segurança C5/C8(parcial)/C9/C3/C7/C2 commitados (`122778b`), **deployados** em produção e **verificados** (1 listener por evento de auth; `activitylog:clean --force` corre com sucesso; HSTS presente; `/api/v1/surfers` devolve 200). Os **53 duplicados** existentes foram **removidos** (135 → 82, 0 grupos duplicados; backup em `/root/db-backups/activity_log-precleanup-20260616-124829.sql`). Recomendações **ainda por fazer**: C1 (trustProxies — arquitetural, requer escalação), C6 (tamper-resistance da BD), C4 (cookies de sessão), C10/C11, e promover a CSP de report-only para enforcing.

| # | Achado | Severidade | Estado |
|---|--------|------------|--------|
| A1 | **Duplicação** de TODOS os registos de auth (e alguns de modelo) — ~40% do audit log são duplicados | Alta | ✅ **Corrigido + deployado + duplicados limpos** |
| B1 | Comando agendado `activitylog:clean` **falha todos os dias** (retention nunca corre) | Alta | ✅ **Corrigido + deployado** |
| B2 | `Auth guard [sanctum] is not defined` — rota morta `/api/v1/user` da era Next.js | Média | ✅ **Corrigido + deployado** |
| B3 | 168× "Livewire encountered corrupt data" — ruído pós-deploy ("página expirou") | Média | ⚠️ A monitorizar |
| B4 | Forecast API (open-meteo) — timeouts/DNS intermitentes (14 erros) | Baixa | ℹ️ Tratado com cache/try-catch |
| C5 | **`canAccessPanel()` devolve `true` para qualquer utilizador** — painel `/admin` sem gate por role | Alta | 📋 Recomendação |
| C6 | Audit log adulterável a nível de BD (sem append-only/off-box) | Alta | 📋 Recomendação |
| C7 | PII (email de login falhado) em texto claro no audit, 365 dias — GDPR | Alta | 📋 Recomendação |
| C1 | `trustProxies(at: '*')` → IPs do audit log e rate-limit **falsificáveis** | Média/Alta | 📋 Recomendação |
| C8 | XSS armazenado via `{!! !!}` (HTML externo da WooCommerce sem sanitizar) | Média | 📋 Recomendação |
| C9 | `/api/v1/surfers*` 500 sempre (relação `surfboards` removida) | Média | 📋 Recomendação |
| C2 | Faltam HSTS e CSP (restantes headers OK) | Média | 📋 Recomendação |
| C3 | API pública `/api/v1/*` sem rate limiting | Média | 📋 Recomendação |
| C4 | `SESSION_SECURE_COOKIE` não definido; `session.encrypt=false` | Baixa/Média | 📋 Recomendação |
| C10/C11 | Bypass manutenção = qualquer user; credenciais admin no `CLAUDE.md` | Baixa | 📋 Recomendação |

**Conclusão de segurança (positiva):** **não há indício de compromisso.** Todos os logins com sucesso pertencem a contas legítimas; nenhuma conta desconhecida entrou; o sistema de auditoria está **íntegro** (IP + user-agent capturados em 100% dos eventos de auth). As tentativas de login falhadas externas são *bots* a sondar `/admin`, todas falharam e **nenhuma** visou o email real do administrador.

---

## Parte A — Duplicação do audit log (causa raiz + correção)

### Sintoma
Cada entrada do audit log aparecia **duas vezes** (mesmo segundo, mesmo utilizador, mesma ação). Em produção: **53 grupos duplicados = 53 linhas em excesso em 134 (~40%)**.

### Diagnóstico (reprodução em runtime, não assumido)
Instrumentei o `LogAuthEvents` e reproduzi um login real local via browser. Resultados decisivos:
- Um login → `handleLogin` disparou **2×** no **mesmo request** `/livewire/update`, com `spl_object_id` do evento **idêntico** (3112) → **um só evento despoletado**.
- `getRawListeners(Login::class)` devolveu **2 listeners** para o mesmo handler:
  - forma *array* `[LogAuthEvents::class, 'handleLogin']` → registo **explícito** em `AppServiceProvider::boot()`.
  - forma *string* `App\Listeners\LogAuthEvents@handleLogin` → formato típico de **event auto-discovery**.

### Causa raiz
O **Laravel 12 ativa event auto-discovery por omissão** (`Illuminate\Foundation\Application::configure()` chama `->withEvents()`, e `EventServiceProvider::$shouldDiscoverEvents = true`). A descoberta varre `app/Listeners`, encontra `LogAuthEvents` e regista `handleLogin`/`handleLogout`/`handleFailed`. Como o `AppServiceProvider::boot()` **também** os regista explicitamente, cada evento de auth ficou com **2 listeners** → 2 escritas. (Confirmado para Login, Logout e Failed: 2 listeners cada.)

> Nota: a análise estática inicial concluiu erradamente "sem auto-discovery" porque o `bootstrap/app.php` não chama `withEvents` explicitamente — é um *default* do framework. Só a reprodução em runtime revelou a verdadeira causa.

### Correção aplicada
`backend/bootstrap/app.php` — **desativar a auto-discovery**, mantendo o registo explícito como única fonte de verdade (escolha deliberada: para um sistema de auditoria, registo explícito e "fail-loud" é preferível a descoberta implícita):
```php
->withEvents(discover: false)
```
**Verificação:** após o fix, cada evento de auth passou a ter **1 listener**; um login real local gera **1** registo (não 2). `app/Listeners` só contém `LogAuthEvents`, portanto nada mais dependia da descoberta.

### Pendente (requer aprovação)
1. **Deploy** do fix para parar novos duplicados.
2. **Limpeza** dos 53 duplicados existentes (backup já feito em `/root/db-backups/activity_log-backup-20260616-115038.sql`). SQL proposto (mantém o `id` mais baixo de cada grupo):
   ```sql
   DELETE a FROM activity_log a
   JOIN activity_log b
     ON a.log_name <=> b.log_name AND a.event <=> b.event
    AND a.causer_id <=> b.causer_id AND a.causer_type <=> b.causer_type
    AND a.subject_type <=> b.subject_type AND a.subject_id <=> b.subject_id
    AND a.description <=> b.description AND a.created_at <=> b.created_at
    AND a.id > b.id;
   -- esperado: 53 linhas removidas (134 → 81)
   ```

> Os pares de `Noticia` vistos localmente (causer nulo, `properties=[]`) são artefacto de seeder/CLI, fenómeno distinto e sem impacto em produção.

---

## Parte B — Análise dos registos de produção

### Audit log (`activity_log`) — 134 registos (2026-04-14 → 2026-06-16)
Distribuição: login 76, login_failed 28, updated 13, logout 8, deploy 6, created 3.

**Integridade — excelente:** 0 registos de auth com `ip_address` nulo, 0 com `user_agent` nulo (em login/logout/login_failed). O `ActivityLogObserver` funciona em produção; o fix de writability/www-data da sessão 12 mantém-se.

**Tentativas de login falhadas (após considerar a duplicação):**
| IP | Tentativas (reais) | Natureza |
|----|--------------------|----------|
| 146.70.233.174 | 12 (6) | **Próprio admin via VPN** — interlaçadas com logins com sucesso na mesma janela |
| 188.37.48.146 | 8 (4) | Bot (PL), 4 tentativas em 13s, emails aleatórios |
| 23.191.200.28 | 4 (2) | Bot |
| 192.42.116.97 | 2 (1) | Saída Tor |
| 148.69.37.103 | 2 (1) | Bot |

Emails tentados: `throttle-test@`/`diag-test@` (testes do próprio), `carlosmserra@gmail.com`, `e.w.i.q...@gmail.com`, `silviafateixa@hotmail.com` — **nenhum** corresponde ao admin real (`nelson.brilhante@cm-nazare.pt`).

**Verificação de compromisso:** o único IP com falhas **e** sucessos (146.70.233.174) é o próprio administrador via VPN (Mullvad) — não um atacante. Todos os logins com sucesso pertencem a contas legítimas (`nelson.brilhante`, `janete.vigia`, `mafalda.santos`). **Sem indício de acesso não autorizado.**

### Log da aplicação (`laravel-YYYY-MM-DD.log`, rotação diária OK)
203 ERROR + 6 WARNING no total. Perfil:

- **168× "Livewire encountered corrupt data when trying to hydrate a component"** (B3) — família "página expirou"; tipicamente checksums Livewire invalidados por deploys enquanto há separadores abertos. Sobretudo UX, mas volume alto. Recomenda-se confirmar estabilidade do `APP_KEY` entre deploys e monitorizar.
- **14× `activitylog:clean` falhou (exit 1)** (B1) — ver abaixo.
- **14× Forecast API** (open-meteo) — timeouts e falhas de DNS (B4). `ForecastService` tem cache (15 min) + try/catch e degrada para `null`. Sugestão: definir `Http::timeout(5)` e evitar cachear `null` (stale-on-error). Baixa severidade.
- **7× `Auth guard [sanctum] is not defined`** (B2) — ver abaixo.

> Os fixes recentes mantêm-se: **sem** erros de permissão/writability, **sem** o 500 da pesquisa, e o scheduler **está** a correr.

#### B1 — `activitylog:clean` falha diariamente → causa raiz
Ao executar manualmente: *"APPLICATION IN PRODUCTION. Command cancelled."* O comando da Spatie usa `ConfirmableTrait`/`confirmToProceed()` e tem a flag `--force` precisamente "para correr em produção". O agendamento (`bootstrap/app.php`) chamava-o **sem `--force`** → cancelado e exit 1 todas as noites → **a retenção de 365 dias nunca corre**.
**Correção aplicada:** `$schedule->command('activitylog:clean --force')->daily();` (verificado em `schedule:list`).

#### B2 — `sanctum` guard indefinido → causa raiz
`routes/api.php` tinha um bloco morto `Route::middleware('auth:sanctum')->group(...)` para `/api/v1/user` (legado Next.js, "for future use"). `laravel/sanctum` **não está instalado** e não há guard `sanctum` em `config/auth.php` → erro 500 sempre que a rota é tocada (bots).
**Correção aplicada:** removida a rota morta e o import `Request` não usado (verificado em `route:list`). As rotas usadas (`/api/v1/search`, etc.) permanecem.

---

## Parte C — Revisão de cibersegurança (website + audit system)

> A secção é complementada por uma passagem sistemática (subagente security-auditor) — ver "Achados adicionais" abaixo.

### C1 — `trustProxies(at: '*')` (Média/Alta)
`bootstrap/app.php:26` confia em **qualquer** proxy. O `request()->ip()` (gravado no audit log) e as chaves de rate-limit passam a derivar do `X-Forwarded-For`, **falsificável** se o container for alcançável fora do Cloudflare/Traefik.
**Remediação:** restringir a confiança às gamas reais do Traefik/Cloudflare (`trustProxies(at: [...])` ou `IpUtils`), garantindo que o container só aceita tráfego do proxy.

### C2 — Cabeçalhos de segurança: faltam HSTS e CSP (Média)
O Nginx (`Dockerfile`) **já define** `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `X-XSS-Protection: 0`, `Referrer-Policy` e `Permissions-Policy` (confirmado na resposta live de `nazarequalifica.pt`). **Faltam** porém `Strict-Transport-Security` (HSTS) e `Content-Security-Policy` — ausentes mesmo ao nível do Cloudflare (confirmado por `curl -I`).
**Remediação:** adicionar HSTS (`max-age=31536000; includeSubDomains; preload`) e uma CSP (começar em report-only, `default-src 'self'` + exceções para Google Fonts/Umami/Cloudflare). A CSP serve de 2.ª linha contra os sinks `{!! !!}` (ver C7).

### C3 — API pública sem rate limiting (Média)
`routes/api.php` expõe `/api/v1/*` sem autenticação **e sem throttle** (inclui `/search`, usado pelo Cmd+K) → vetor de scraping/abuso/DoS.
**Remediação:** aplicar `->middleware('throttle:60,1')` ao grupo `v1` (ou limite mais apertado para `/search`).

### C4 — Cookies de sessão (Baixa/Média)
`SESSION_SECURE_COOKIE` não definido em produção (`config/session.php:172` → `null`) e `session.encrypt=false`. O site é HTTPS-only (forceScheme + Cloudflare), mas a boa prática é forçar.
**Remediação:** `SESSION_SECURE_COOKIE=true` (e ponderar `SESSION_ENCRYPT=true`). `http_only=true` e `same_site=lax` já estão corretos. `APP_DEBUG=false` confirmado em produção ✅.

### Pontos fortes confirmados
- Audit log **admin-only** e **imutável na UI** (`ActivityLogResource`: `canCreate/Edit/Delete = false`).
- Integridade do registo 100% (IP + user-agent sempre presentes).
- `APP_DEBUG=false`, HTTPS forçado em produção, `http_only` cookies.

### Achados adicionais (passagem sistemática — verificados)

| Ref | Achado | Sev. | Evidência | Remediação |
|-----|--------|------|-----------|------------|
| **C5** | **`canAccessPanel()` devolve `true` para QUALQUER utilizador autenticado** — o painel `/admin` não tem gate por role (existe enum `Role`: Admin/Editor/EntityEditor, mas não é usado aqui). Sem registo público, mas qualquer credencial válida (ou conta de baixo privilégio comprometida) alcança o CMS; várias resources não têm `canAccess()` próprio. **Verificado** em `User.php`. | **Alta** | `app/Models/User.php:58-61` | `return in_array($this->role, [Role::Admin, Role::Editor, Role::EntityEditor], true);` e exigir email verificado |
| **C6** | **Audit log adulterável a nível de BD** — imutável na UI ✅, mas qualquer admin com acesso à BD/`tinker` pode `DELETE`/`UPDATE` na tabela `activity_log`. Sem append-only, sem envio off-box, sem hash-chain. `ActivityLog::$guarded = []` (mass-assignment). | Alta | `app/Models/ActivityLog.php:12`; `migrations/...create_activity_log_table.php` | Role de BD sem DELETE/UPDATE em `activity_log`; ponderar SIEM/syslog append-only; `$fillable` explícito |
| **C7** | **PII em texto claro no audit (GDPR)** — cada login falhado grava o email submetido em `properties.email` (utilizadores escrevem a password no campo email; listas de credential-stuffing também caem aqui), retido 365 dias e legível por qualquer admin. | Alta | `app/Listeners/LogAuthEvents.php:32-35`; `config/activitylog.php:18` | Mascarar/hash do email; retenção mais curta para `log_name='auth'`; nunca registar conteúdo do campo password |
| **C8** | **XSS armazenado via `{!! !!}`** — `loja/show.blade.php:173,182` renderiza HTML **externo** da WooCommerce (`short_description`/`description`) **sem sanitização**. Risco mais alto por a fonte ser externa. Campos CMS (noticias/eventos/surfer/legais) idem, risco menor (admin de confiança). *(O diff do audit log usa `{!! !!}` mas escapa via `htmlspecialchars` — SEGURO.)* | Média | `resources/views/pages/loja/show.blade.php:173,182`; `WooCommerceService.php:198-199` | Sanitizar HTML da WooCommerce com allowlist (HTMLPurifier); CSP (C2) como backstop |
| **C9** | **`/api/v1/surfers*` devolve 500 sempre** — `SurferController` usa `Surfer::with('surfboards')` mas a relação foi removida. Endpoints legados Next.js, não autenticados, a falhar. **Verificado** (Surfer não tem `surfboards`). | Média | `app/Http/Controllers/Api/SurferController.php:13,26,35` | Remover as rotas `/api/v1` mortas (ou corrigir+throttle+testar) — superfície de ataque sem benefício |
| **C10** | Bypass de manutenção é qualquer utilizador autenticado, não só admins | Baixa | `app/Http/Middleware/CheckMaintenanceMode.php:18` | `if ($request->user()?->isAdmin())` |
| **C11** | Credenciais de admin em texto claro no `CLAUDE.md` (versionado) — email é alvo válido de brute-force (ver throttle) | Baixa | `CLAUDE.md` (raiz) | Rotacionar e mover para `.credentials.md` (já git-ignored); limpar histórico se coincidirem com produção |

**Reforço ao C-login (throttle):** com o `trustProxies('*')` (C1), a chave de throttle do Filament (email+IP) é contornável rodando o `X-Forwarded-For` → brute-force efetivamente ilimitado contra o email do admin (que está no `CLAUDE.md`). Recomenda-se lockout por email (independente de IP) + ponderar 2FA para o role Admin.

**Pontos fortes adicionais confirmados:** CSRF aplicado (grupo `web` + Filament `VerifyCsrfToken`); sem SQL injection nas rotas revistas (LIKE com bind); entity-scoping de editores sólido a nível de query e de formulário; uploads com `->image()`/`acceptedFileTypes`/`maxSize`; credenciais WooCommerce só server-side; `URL::forceScheme('https')` em produção. **Lacuna de cobertura:** tentativas de **autorização** falhadas (403) e downloads de documentos **não** são auditados — adicionar se a conformidade o exigir.

**A confirmar/correr:** `composer audit` em CI; confirmar que a porta do container não está exposta para lá do Traefik.

---

## Ações pendentes (requerem aprovação)

1. **Commit + deploy** dos 3 fixes (`bootstrap/app.php` ×2, `routes/api.php`) → para `main` → Coolify.
2. **Limpeza** dos 53 duplicados em produção (backup já feito).
3. **Decidir** sobre as recomendações C1–C4 (podem ir num commit separado).

## Verificação
- Duplicação: 1 login = 1 registo (verificado local). Pós-limpeza: query de grupos duplicados deve devolver vazio.
- `activitylog:clean --force`: confirmar exit 0 no próximo run agendado (sem ERROR no log).
- `/api/v1/user`: passa a 404 (em vez de 500); restantes rotas API intactas.
