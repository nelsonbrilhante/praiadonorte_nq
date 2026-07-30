# Session Handoff - Praia do Norte

> Este ficheiro serve como ponto de continuidade entre sessões de desenvolvimento.
> Lê-lo no início de cada sessão para retomar o contexto.

---

## Última Sessão

- **Data**: 2026-07-30 (sessão 15)
- **Resumo**: Email do **formulário de reservas Carsurf**. (1) Auditado: o destinatário estava **fixo no código** em dois sítios, obrigando a deploy para mudar. (2) Passou a ser **configurável no painel** (`Definições → Formulário de Reservas Carsurf`) — o user já alterou em produção para `carsurf@nazarequalifica.pt`. (3) Corrigidos **4 defeitos de apresentação** do email (marca, link, idioma, cor). (4) Corrigidos **2 bugs pré-existentes** encontrados pelo caminho. **2 deploys verificados em produção; email renderizado dentro do container para validar.**
- **Branch**: `main` — `3e4c3a8` deployed e verificado. Sem commits locais por empurrar (o antigo `36d5753` foi integrado nesta sessão).

### O que foi feito:

1. **Destinatário configurável (commit `4712c28`, deployed)** — chave `carsurf_reservas_recipients` no `SiteSetting`, editável no painel, aceita vários emails por vírgula; o primeiro é também o `mailto:` público da página. `SiteSetting::carsurfReservasRecipients()` faz o parse, descarta inválidos e recorre à constante `CARSURF_RESERVAS_FALLBACK` se nada válido sobrar — **uma reserva nunca fica sem destino**. As closures de `/carsurf/reservas` saíram do `routes/web.php` para `CarsurfReservationController` (torna o fallback testável).

2. **Quatro correções ao email (commit `3e4c3a8`, deployed)** — o template usava `<x-mail::message>`, que herda `config('app.name')` e `config('app.url')`, ambos globais. Passou a `<x-mail::layout>` com cabeçalho e rodapé próprios:
   - cabeçalho: `Praia do Norte` → **`CARSURF`**
   - link: `nq.nelsonbrilhante.com` → **`https://carsurf.nazare.pt`** (explícito, já não depende de `APP_URL`)
   - rodapé: `All rights reserved.` → **`Todos os direitos reservados.`**
   - botão: `#18181b` → **`#127a99`**. O azul do logótipo (extraído de `CARSURF_001.png`) é `#18a2cc`, mas com texto branco dá 2,97:1 e reprovaria WCAG AA; `#127a99` é a mesma matiz (194°) escurecida até 4,92:1. O azul exacto ficou no filete decorativo do cabeçalho. **Tema aplicado só a este mailable** (`resources/views/mail/carsurf.css`); restantes emails no tema padrão.
   - `lang/pt.json` **criado** (não existia): corrige o inglês também nos **outros** emails da plataforma (reposição de senha, relatório semanal).
   - envio fixa `locale('pt')`: o destinatário é sempre a equipa Carsurf, submissão em inglês não deve gerar email inglês.

3. **Bug pré-existente: validação do painel nunca corria** — `SiteSettings::save()` lia as propriedades públicas diretamente e nunca chamava `$this->form->getState()`, pelo que **nenhuma regra de validação desta página era aplicada** e valores inválidos eram gravados em silêncio. Corrigido, com testes de não-regressão a garantir que o modo de manutenção e o relatório semanal continuam a guardar.

4. **Bug pré-existente: testes com BD eram impossíveis** — a migração `2026_03_19_000001_seed_legal_content` chama o `LegalContentSeeder`, que escreve `SiteSetting` (com `LogsActivity`), mas `activity_log` só nasce na migração `2026_04_14_080804`. Qualquer BD nova rebentava com `no such table: activity_log`. Resolvido com `Model::withoutEvents()` (o pacote spatie instalado **não** tem `withoutLogs()`). **Inerte em produção** — verificado que a migração já constava como executada (batch 6). O `ExampleTest` que vinha do Laravel também falhava desde sempre (assere 200 em `/`, que redireciona 302 para `/pt`).

5. **Testes**: 1 a falhar → **21 a passar** (49 asserções). Novos: `CarsurfReservationTest` (destinatários, fallback, validação), `SiteSettingsPageTest` (validação + não-regressão), `CarsurfReservationEmailTest` (as 4 correções travadas).

### Validado em produção:
- Reserva de teste submetida pelo formulário às 10:01:55 → gravada, fila drenou, 0 falhas, 0 erros no log.
- Email renderizado **dentro do container** após o deploy: 9 asserções (4 presenças + 5 ausências) todas OK.
- Alteração do destinatário registada na auditoria (`activity_log` #184, autor id=2, 09:57:47).

### Pendentes (por ordem de importância):
1. **`APP_URL` em produção aponta para `nq.nelsonbrilhante.com`** — alimenta os **links de reposição de senha** e qualquer URL absoluto. É variável de ambiente no Coolify, com efeito em toda a plataforma; **escalado ao user, à espera de decisão**.
2. **Entrega na caixa não confirmada** — a chave Resend é *send-only*, não deixa consultar estado de entrega nem domínios verificados. Se as mensagens não chegarem, suspeitar de **SPF/DKIM** de `nazarequalifica.pt` para o Resend, não do formulário.
3. **`ContactMessage` não tem resource no Filament** — as submissões gravam na BD mas ninguém as vê no painel. Se um email falhar, o pedido fica invisível.
4. **`stats_weekly_recipients` continua sem validação** — um typo parte o relatório semanal em silêncio. Agora que o `save()` valida, é uma linha.
5. **`<title>` do email diz "Praia do Norte"** — codificado no layout do Laravel. **Deliberadamente não corrigido**: nenhum cliente de email mostra o `<title>`, e corrigi-lo exigiria forkar uma vista do vendor.

### Troca de Resend para caixa dedicada — investigação registada no Notion:
Avaliada a 2026-07-30 e **não executada**. Registo completo com factos verificados, bloqueador e passos de execução na **Caixa de Entrada da CENTRAL**:
`https://app.notion.com/p/3ad576324ea681c29a22cb29c33032f9`

Resumo do que lá está, para não se repetir a investigação: SMTP é viável (portas 465/587/25 abertas do container para `vm01.cm-nazare.pt`, Exim 4.99.5/cPanel), mas **não há credencial utilizável** — a password de `geral@carsurf.nazare.pt` no `.credentials.md` dá `535`, e a única que autentica é `store@nazarequalifica.pt`, que é a caixa da loja WooCommerce. O motivo da troca é de **conformidade**, não técnico: a Política de Privacidade não divulga o Resend como subcontratante. Recomendação registada: divulgar o Resend na política (resolve a conformidade) antes de considerar a troca de transporte. **Atenção ao DMARC `p=reject` com alinhamento estrito** — um envio mal alinhado é rejeitado, não vai para spam.

### Nota de configuração (corrige o `.credentials.md`):
Produção envia por **Resend** (`MAIL_MAILER=resend` + `RESEND_API_KEY`), **não por SMTP**. As variáveis `MAIL_HOST=vm01.cm-nazare.pt` / `MAIL_PORT=465` / `MAIL_USERNAME` / `MAIL_PASSWORD` continuam definidas mas são **ignoradas**. `MAIL_FROM_ADDRESS=no-reply@nazarequalifica.pt` (o `.credentials.md` diz `geral@carsurf.nazare.pt` — desatualizado).

---

## Sessão 14 (2026-06-17) — resumo arquivado

- **Data**: 2026-06-17 (sessão 14)
- **Resumo**: (1) **Avaliação de migração** do `nazarequalifica.pt` para o alojamento **PTisp cPanel partilhado** (`vm01.cm-nazare.pt`, o mesmo do projeto `20260616_portal_fornecedor`) + **comunicação defensiva à administração**. Conclusão: não é "mudar de servidor", é **re-plataformar** (Docker→cPanel; Umami inviável; loja WooCommerce é projeto à parte; downgrade de recursos/controlo). (2) **Fix responsivo do hero (mobile)** + espaçamento "Últimas Notícias" — **deployed e verificado em produção, sem perda de dados**.
- **Branch**: `main` — `9b45c7e` deployed e verificado. (Nota: o commit local antigo `454ec3d` continua por integrar conforme histórico.)

### O que foi feito:
1. **Fix CSS responsivo (commit `9b45c7e`, deployed)** — no mobile o título do hero sobrepunha o header fixo. `hero-slider.blade.php`: hero `h-[70dvh]`→`h-[85dvh]`, título `text-4xl`→`text-2xl`+`line-clamp-3`, excerto `text-base`+`line-clamp-2` (desktop intacto via `md:`). `home.blade.php`: secção notícias `py-10 md:py-16`, título `mb-6 md:mb-10`. **Verificado a 375px em produção; backup automático da BD correu antes do deploy; migrate no-op; seeds saltados por sentinela.**
2. **Avaliação migração PTisp + comunicação** (documentos **locais, NÃO commitados** — sensíveis): `docs/reports/2026-06-17-avaliacao-migracao-ptisp-pn-nq.md` e `docs/reports/2026-06-17-comunicacao-administracao-migracao.md`.

### Migração PTisp — estado e pendentes:
- Destino **não tem SSH ativo** (ticket PTisp aberto 2026-06-16), sem Docker, reseller (não root), 2 GB/1 core partilhado. **Migração NÃO deve iniciar** sem: SSH ativo, decisão sobre Umami (sem destino no cPanel) e plano para a loja WooCommerce.
- **Track record validado** para a comunicação: projeto desde 2025-12-03 (~6 meses), produção desde 2026-03-02 (~3,5 meses), **zero incidentes**.
- Comunicação dirigida a **Conselho de Gerência NQ (Álvaro Festas) + Executivo CMN** — **por rever/enviar pelo user** (eu não enviei nada). Confirmar nomes/cargos antes de formalizar.

### Documentos criados (sessão 14):
- `docs/reports/2026-06-17-avaliacao-migracao-ptisp-pn-nq.md` (local, não commitado)
- `docs/reports/2026-06-17-comunicacao-administracao-migracao.md` (local, não commitado)

---

## Sessão 13 (2026-06-16) — resumo arquivado

- **Data**: 2026-06-16 (sessão 13)
- **Resumo**: Auditoria dos registos de atividade (audit log Spatie + logs da aplicação) em produção + análise de cibersegurança. **Resolvida a duplicação do audit log (bug #18)**: causa raiz confirmada por REPRODUÇÃO — o Laravel 12 ativa event auto-discovery por omissão, que registava o `LogAuthEvents` além do registo explícito no `AppServiceProvider` → cada evento de auth gravado 2× (~40% do audit log). **Resolvido o bug #19** (`activitylog:clean` falhava diariamente por faltar `--force`). + 4 quick-wins de segurança + limpeza de 53 duplicados em produção. **1 deploy verificado (`122778b`); backups feitos; sem perda de dados.**
- **Branch**: `main` — `122778b` deployed e verificado. (1 commit local não-pushed: `454ec3d`, só doc de estado do relatório.)

### O que foi feito (resolvido + verificado em produção):
1. **Duplicação do audit log (commit `122778b`)** — `->withEvents(discover: false)` em `bootstrap/app.php` desativa a auto-discovery (registo explícito = única fonte de verdade). Verificado: 1 listener por evento de auth. **53 duplicados removidos** (135→82, 0 grupos). Resolve bug #18.
2. **`activitylog:clean --force`** — faltava `--force` (ConfirmableTrait exige-o em produção) → falhava todas as noites. Verificado: corre com sucesso. Resolve bug #19.
3. **Rota morta `/api/v1/user`** (`auth:sanctum` sem sanctum instalado) removida; **`/api/v1/surfers*`** já não dá 500 (relação `surfboards` removida).
4. **Segurança (deployed):** C5 gate do `/admin` por role; C3 `throttle:60,1` na API pública; C7 máscara de email no audit (GDPR); C2 HSTS + CSP report-only.

### Segurança — diagnóstico (sem indício de compromisso):
- Nenhuma conta desconhecida entrou; integridade do audit 100% (IP+user-agent sempre presentes). Tentativas falhadas externas = bots a sondar `/admin`, nenhuma visou o email real do admin. Único IP com falhas+sucessos = o próprio admin via VPN.

### Estado pendente (backlog registado no Notion):
- **C1 `trustProxies('*')`** — IPs do audit/rate-limit falsificáveis; **arquitetural, escalar (Regra 2)**. + C4 `SESSION_SECURE_COOKIE`, C6 tamper-resistance do audit a nível de BD, C10/C11, promover CSP report-only→enforcing. Ver tarefa Notion "Follow-up auditoria" + `docs/reports/2026-06-16-analise-registos-atividade-ciberseguranca.md`.
- Env Coolify ainda por alinhar: `CACHE_STORE`/`SESSION_DRIVER=database`, `APP_URL`.
- Push do commit local `454ec3d` no próximo deploy.

### Notion (Relatórios COO, PRJ-8):
- Sessões 10/11/12/13 registadas (Done) + tarefa **"Follow-up auditoria — pendentes de segurança + config"** (Not started).

### Documentos criados:
- Relatório: `docs/reports/2026-06-16-analise-registos-atividade-ciberseguranca.md` (committed em `122778b`).

---

## Sessão 12 (2026-05-27) — resumo arquivado

- **Data**: 2026-05-27 (sessão 12)
- **Resumo**: Resolvido o 500 intermitente na autenticação + **restaurada a observabilidade de produção**. Causa raiz: logging partido — o scheduler corria como root e criava os logs diários root-owned, que o php-fpm (www-data) não conseguia escrever, mascarando erros (500) e perdendo todos os traces. Completado o fix estrutural pendente da sessão 11 (Dockerfile committed + deployed) + corrigidos 2 bugs visíveis ao utilizador (FOUC do hero slider; 500 na pesquisa). **3 deploys verificados, zero perda de dados.**
- **Branch**: `main` — tudo committed e deployed. Container de produção: `0c7a6f8`.

### O que foi feito (resolvido + verificado em produção):
1. **Fix estrutural do logging (commit `10c0122`)** — scheduler + queue-worker passam a `user=www-data` no supervisor + fix do brace-expansion do ash. **Completou o pendente da sessão 11.** Verificado: scheduler corre como www-data; www-data escreve logs (antes `Permission denied`); traces reais capturados.
2. **FOUC do hero slider (commit `76d9c38`)** — `x-cloak` nos slides inativos; slide 0 server-rendered. Sem texto sobreposto no primeiro paint.
3. **Pesquisa 500 (commit `0c7a6f8`)** — `nationality` (coluna removida) → `aka` em SearchController + SearchSpotlight + frontend. **Resolve o bug conhecido #16.** Verificado: queries que davam 500 devolvem 200.
4. **Investigação exaustiva** do 500 intermitente de login: não reproduzível (35+ tentativas), em clusters que recuperam sozinhos; traces antigos perdidos pelo logging partido. Observabilidade agora restaurada para o capturar na próxima ocorrência.

### Estado pendente:
- **500 intermitente de login**: não recorreu; observabilidade pronta para capturar o trace completo se voltar.
- **2 bugs documentados como tarefa para 1-5 jun**: eventos de auth duplicados (dupla-registo do listener via `event:cache` + auto-discovery); `activitylog:clean` falha diária. Ver `docs/reports/2026-06-01-tarefa-bugs-pendentes-pn-nq.md`.
- **Coolify env (ainda por fazer)**: `CACHE_STORE`/`SESSION_DRIVER` continuam `file` (CLAUDE.md diz `database`); `APP_URL` (detetado favicon mixed-content http).

### Documentos criados (locais, não commitados — para passar ao ATLAS):
- SIADAP3: `docs/reports/2026-05-27-siadap-intervencao-pn-nq.md` (objetivo provável O3)
- Tarefa próxima semana: `docs/reports/2026-06-01-tarefa-bugs-pendentes-pn-nq.md`
- Notion incidente: https://www.notion.so/36d576324ea68110a83cf12841d412b6

---

## Sessão 11 (2026-04-20) — resumo arquivado

- **Data**: 2026-04-20 (sessão 11)
- **Resumo**: Investigação + fix imediato do 500 em `/admin/site-settings` (permissões de storage root-owned) + envio dos relatórios semanais pedidos. Fix estrutural (Dockerfile) em curso, **aguarda validação + commit na próxima sessão**.
- **Branch**: `main` (Dockerfile com alterações locais, não commitado)

### Estado pendente (IMPORTANTE ler primeiro):
- `Dockerfile` **alterado localmente mas não commitado** — não fazer push sem validação:
  - `[program:queue-worker]` e `[program:scheduler]` passaram a ter `user=www-data`
  - `mkdir storage/framework/{sessions,views,cache}` substituído por paths explícitos (fix bug latente: `ash` não expande chavetas)
- Container em produção **remendado em runtime** via `chown -R www-data:www-data storage bootstrap/cache` — resolveu até Coolify fazer redeploy, em que **o remendo desaparece** e volta ao problema se o Dockerfile não for entretanto deployed.

### O que foi feito:

1. **Envios manuais do relatório semanal 13-19/04**
   - Teste para `nelsonbrilhante@gmail.com` (mesmo período do PDF Outlook já recebido)
   - Individual para `alvaro.festas@nazarequalifica.pt`
   - Individual para `carlos.filipe@nazarequalifica.pt`
   - Comando usado: `docker exec o4ck0w8woo4s88gg4gkg04gs-112244361405 php artisan stats:send-weekly --email=<address>` (sem `--test` → usa janela Mon-Sun correcta)

2. **Investigação do 500 em `/admin/site-settings`** (era na carga da página, não no save)
   - Root cause capturada: `file_put_contents(/var/www/html/storage/framework/cache/data/7b/c7/...): Permission denied`
   - Chain: `SiteSettings::mount()` → `SiteSetting::get()` linha 32 → `Cache::remember()` → `FileStore::put()` em dir root-owned
   - Causa subjacente: supervisor no Dockerfile inicia `scheduler` e `queue-worker` sem `user=www-data`, logo os processos corriam como root e criavam dirs de cache/log como root desde 2026-04-15 (primeira execução diária do `activitylog:clean` adicionado no commit `8fa1802` de 04-14)

3. **Fix imediato aplicado em produção (runtime apenas, não persiste)**
   - `chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache` no container
   - `chmod -R 775` nos mesmos
   - Eliminada a pasta literal `{sessions,views,cache}` que o `ash` tinha criado no container
   - Validado: `/admin/site-settings` carrega; save com 4 emails persistido em produção às 13:42:16

4. **Destinatários gravados em produção (`site_settings.stats_weekly_recipients`)**:
   ```
   nelson.brilhante@cm-nazare.pt, alvaro.festas@nazarequalifica.pt,
   joaquim.paulo@cm-nazare.pt, carlos.filipe@nazarequalifica.pt
   ```
   Próxima segunda-feira (27/04 12:00 UTC) o scheduler envia automaticamente para os 4. `nelsonbrilhante@gmail.com` NÃO está na lista (continua manual).

5. **Desvios da config de produção vs CLAUDE.md detectados (não corrigidos)**:
   - `CACHE_STORE=file` — CLAUDE.md diz `database`
   - `SESSION_DRIVER=file` — CLAUDE.md diz `database`
   - `APP_URL=https://nq.nelsonbrilhante.com` — devia ser `https://nazarequalifica.pt`

6. **Permissões de logs já corrigidas**
   - Ficheiros `laravel-2026-04-15.log` a `laravel-2026-04-20.log` passaram de `root:root 644` para `www-data:www-data 775`

7. **Snapshot da VPS** feito pelo utilizador antes do chown (segurança).

### Ficheiros modificados (não commitados):
- `Dockerfile` — supervisor `user=www-data` + mkdir sem chavetas

### Próximas tarefas (para amanhã):

1. [ ] Validar manualmente todas as entradas do menu admin em produção (lista no plano):
   Geral/Utilizadores, Geral/HeroSlides, Noticias, Eventos, Surfers, Paginas PN/Carsurf/NQ,
   Documents, DocumentCategory, CorporateBody, ContraOrdenacao, ContactMessages,
   LegalCompliance, ActivityLog, SiteSettings
2. [ ] Commit + push do `Dockerfile` (trigger Coolify redeploy)
3. [ ] No Coolify UI: mudar env vars `CACHE_STORE=database` e `SESSION_DRIVER=database` (+ considerar alinhar `APP_URL=https://nazarequalifica.pt`) — **requer clear cache após**
4. [ ] Após redeploy: confirmar `docker exec ... ps aux | grep scheduler` mostra processo como `www-data`, não root
5. [ ] Dia seguinte ao deploy: verificar após 00:00 UTC (activitylog:clean) e após Mon 12:00 UTC (stats:send-weekly) que dirs/ficheiros novos ficam com owner correcto
6. [ ] (Opcional) Remover test probe se ainda existir: `SiteSetting` com key iniciada por `_____test_probe`

### Plano detalhado (persistido):
- `/Users/zumuha/.claude/plans/quiet-juggling-platypus.md`

### Infra:
- **VPS snapshot** feito pelo utilizador antes da intervenção (22h, 2026-04-20)
- Backup SQLite local: `backend/storage/app/backups/backup-20260420-142651.sqlite`

---

## Sessão 10 (2026-04-14) — resumo arquivado

Sistema de Audit Log (spatie/laravel-activitylog v5) implementado + 3 bugs fixed
(morphTo filter, OOM build, diff duplicado). Commits: `8fa1802`, `95e4ead`,
`eb01b57`, `1a9ee01`. VPS: 4 GB swap permanente adicionado.

Nota 2026-04-20: o scheduler `activitylog:clean daily` adicionado nessa sessão
passou a correr diariamente como root (supervisor sem `user=www-data`) e
criou dirs de cache/log root-owned — causa do 500 resolvido agora na sessão 11.

---

## Estado Actual do Projecto

| Item | Valor |
|------|-------|
| **Fase** | Produção (deployed) + QA contínuo |
| **Branch** | `main` |
| **Stack** | Laravel 12 + Filament 4.x + MySQL 8.0 + WooCommerce (Coolify) |
| **Produção Laravel** | `nazarequalifica.pt` (+ `praiadonortenazare.pt`, `carsurf.nazare.pt`) |
| **Produção WooCommerce** | `store.praiadonortenazare.pt` |
| **Analytics** | `analytics.nazarequalifica.pt` (Umami, operacional) |
| **Email semanal** | Ativo, segundas-feiras 12:00 UTC. Destinatários: `nelson.brilhante@cm-nazare.pt`, `alvaro.festas@nazarequalifica.pt`, `joaquim.paulo@cm-nazare.pt`, `carlos.filipe@nazarequalifica.pt` |
| **Audit Log** | ✅ Activo em produção — admin-only em Configurações → Registos de Atividade |
| **CI/CD** | Push to `main` → Coolify webhook (simples, sem docker network connect). WordPress deploy manual. |
| **VPS swap** | 4 GB permanente (prevenção OOM nos builds Docker) |

---

## Próximas Tarefas

### Prioridade Alta — Fix estrutural do 500 admin
- [x] ~~Commit/push do `Dockerfile` (scheduler/queue www-data)~~ — feito sessão 12 (commit `10c0122`, deployed + verificado)
- [ ] Env vars Coolify ainda por alinhar: `CACHE_STORE=database`, `SESSION_DRIVER=database`, `APP_URL=https://nazarequalifica.pt` (este resolve o favicon mixed-content http) — requer clear cache após
  - **Confirmado na sessão 15**: `APP_URL` continua `https://nq.nelsonbrilhante.com` em produção. Consequências verificadas para além do favicon: alimenta os **links de reposição de senha** e era a causa do cabeçalho do email de reservas ligar ao domínio de testes (esse email já foi desacoplado da variável). **Escalado ao user; à espera de decisão** por afetar toda a plataforma.
- [ ] Validação completa de todas as entradas do menu admin em produção

### Prioridade Alta — Email Semanal
1. [x] ~~Destinatários configurados no admin~~ — feito 2026-04-20 (site_settings persistido)
2. [ ] Adicionar URLs novas ao `humanizeUrl()`: `/pt/praia-norte/webcams`, `/en/praia-norte/webcams`, `/en/praia-norte/previsoes`, `/pt/nazare-qualifica/contraordenacoes/identificacao-de-condutor`
3. [ ] Validar próximo envio automático: Mon 2026-04-27 12:00 UTC deve enviar para os 4 destinatários

### Prioridade Média — Formulário de Reservas Carsurf (sessão 15)
- [x] ~~Destinatário configurável no painel~~ — feito 2026-07-30 (`4712c28`, deployed; user já alterou para `carsurf@nazarequalifica.pt`)
- [x] ~~Marca, link, idioma e cor do email~~ — feito 2026-07-30 (`3e4c3a8`, deployed e validado no container)
- [ ] **Confirmar entrega na caixa** — a chave Resend é *send-only* e não permite consultar estado de entrega. Se não chegarem, verificar **SPF/DKIM** de `nazarequalifica.pt` para o Resend
- [ ] **Resource Filament para `ContactMessage`** — as submissões gravam na BD mas não há ecrã no painel; se um email falhar, o pedido fica invisível
- [ ] Validação no campo `stats_weekly_recipients` (relatório semanal) — continua sem validar; agora que o `save()` valida, é uma linha

### Prioridade Média — Audit Log (melhorias futuras)
- [ ] Validar em produção durante algumas semanas
- [ ] Considerar adicionar "Link para editar" no detalhe quando subject_type ainda existe
- [ ] Dashboard widget com actividade recente na home do admin

### Prioridade Alta — Umami Funcionalidades Avançadas
4. [ ] Custom Events (data-umami-event nos botões) — spec em `docs/superpowers/specs/2026-04-01-umami-funcionalidades-avancadas-design.md`
5. [ ] Performance Tracking (Core Web Vitals)
6. [ ] Anti-adblock (stats.js + TRACKER_SCRIPT_NAME)
7. [ ] Share Page (dashboard público para stakeholders)
8. [ ] Guia UTM para equipa de comunicação

### Prioridade Alta — WooCommerce & Pagamentos
9. [ ] Integrar Easypay com WooCommerce (Multibanco, MBWay, Cartão de Crédito)
10. [ ] Campo NIF/CIF/NIE nas facturas

### Prioridade Alta — Conteúdo
11. [ ] Traduzir conteúdo para EN (notícias, eventos, surfers)

### Prioridade Média — QA
12. [ ] Testes funcionais de todas as páginas
13. [ ] Lighthouse audit (target: >90 em todas as métricas)

### Prioridade Média — Segurança
14. [~] Security headers — sessão 13: HSTS ✅ + X-Frame-Options/X-Content-Type/Referrer/Permissions já ativos. **CSP em report-only — falta promover a enforcing.**
15. [x] ~~Rate limiting nas rotas públicas~~ — sessão 13 (`throttle:60,1` em `/api/v1`).
16b. [ ] **C1 `trustProxies('*')`** (arquitetural — escalar) + C4 SESSION_SECURE_COOKIE + C6 tamper-resistance do audit + C10/C11. Ver tarefa Notion "Follow-up auditoria" e `docs/reports/2026-06-16-analise-registos-atividade-ciberseguranca.md`.

### Bugs conhecidos
16. [x] ~~Coluna `nationality` removida do Surfer mas pesquisa spotlight ainda a referencia (erro 500)~~ — RESOLVIDO sessão 12 (commit `0c7a6f8`, `nationality`→`aka`)
17. [~] Permissões de cache/log após redeploy — mitigado sessão 12 (scheduler/queue www-data + entrypoint cria dirs explícitos). Confirmar dirs de cache www-data após próximos redeploys.
18. [x] ~~Eventos de auth duplicados~~ — RESOLVIDO sessão 13 (commit `122778b`): Laravel 12 event auto-discovery + registo explícito → duplo registo do listener. Fix: `->withEvents(discover: false)`. 53 duplicados limpos em produção.
19. [x] ~~`activitylog:clean` falha diária (exit 1)~~ — RESOLVIDO sessão 13 (commit `122778b`): faltava `--force` (ConfirmableTrait).

---

## Gaps Conhecidos

| Item | Estado | Notas |
|------|--------|-------|
| Formulário de Contacto backend | ⚠️ Não implementado | `action="#"` sem handler POST |
| Easypay WooCommerce | 🔴 Bloqueado | Plugin configurado, "Connection not validated" |
| NIF/CIF em facturas | ⚠️ Não implementado | Campo custom no checkout |
| Conteúdo EN | ⚠️ Parcial | Estrutura i18n pronta, falta tradução |
| Email semanal | ⚠️ Fix deployed | Hairpin NAT via Traefik — aguarda validação automática 20/04 |
| Audit Log | ✅ Operacional | Admin-only; logs persistem entre deploys; retenção 365 dias |
| Pesquisa spotlight | ✅ Resolvido | `nationality`→`aka` (sessão 12, commit `0c7a6f8`) |
| WordPress Coolify rebuild | ⚠️ | Coolify tem bug — stop/start não recria container WP |

---

## Como Continuar

```bash
# 1. Ler este ficheiro para contexto
# 2. Iniciar servidor
cd backend && composer dev

# 3. Aceder ao admin
open http://localhost:8000/admin

# 4. Produção
# Laravel: nazarequalifica.pt
# WooCommerce: store.praiadonortenazare.pt
# Analytics: analytics.nazarequalifica.pt (admin / 8WFARCDXpwpjfbiTHH84)

# 5. Verificar se email de 13/04 chegou correctamente
# 6. Se OK, adicionar mais destinatários no admin
# 7. Actualizar este ficheiro no final da sessão
```
