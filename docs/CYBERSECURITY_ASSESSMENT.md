# Avaliação de Cibersegurança e Estratégia de Implementação

**Projeto:** Plataforma Unificada Praia do Norte
**Foco Principal:** E-commerce e Integridade de Dados
**Data:** 25 de Novembro de 2025
**Versão:** 2.0

## 1. Resumo Executivo e Modelo de Ameaças

A unificação das plataformas (Praia do Norte, Carsurf, NQ) numa única solução de e-commerce aumenta a superfície de ataque. O principal vetor de risco reside na **manipulação de transações financeiras** e no **roubo de dados pessoais (PII)**.

### Principais Ameaças Identificadas:

1. **Manipulação de Preços (Price Tampering):** Atacantes alterarem o valor do carrinho no frontend antes do envio para o gateway.
2. **Fraude em Pagamentos:** Utilização de cartões roubados ou injeção de webhooks falsos para simular pagamentos bem-sucedidos.
3. **Exposição de Dados (Data Leaks):** Acesso não autorizado a dados de clientes armazenados no CMS ou Base de Dados.
4. **Ataques à Cadeia de Suprimentos (Supply Chain):** Injeção de scripts maliciosos via dependências npm/composer vulneráveis (cartões de crédito/keyloggers).

---

## 2. Segurança no E-commerce (Crítico)

Esta é a componente mais sensível. A arquitetura deve seguir o princípio de **"Zero Trust no Frontend"**.

### 2.1. Integridade das Transações (Server-Side Authority)

O frontend (Zustand store) serve apenas para visualização. A lógica de negócio deve residir estritamente no servidor.

- **O Princípio:** Nunca confiar nos preços enviados pelo cliente.
- **A Implementação:**
    1. O utilizador envia apenas `{ produtoId, quantidade }` para o servidor.
    2. O servidor (Laravel Controller) consulta o **Aimeos** para obter o preço atual e stock real.
    3. O cálculo do total, descontos e portes é feito no backend Laravel.
    4. Só então o pedido de pagamento é gerado para a API da Easypay.

### 2.2. Integração Segura com Easypay v2.0

- **Gestão de Credenciais:** `AccountId` e `ApiKey` da Easypay nunca devem ser expostos no código cliente (browser). Devem estar em `.env` do Laravel e ser acedidos apenas pelo servidor.
- **Segurança de Webhooks:**
    - A confirmação de pagamento é assíncrona via Webhook.
    - **Validação de Assinatura:** Todos os webhooks recebidos da Easypay devem ter a sua origem e assinatura validadas (HMAC) antes de libertar a encomenda.
    - **Idempotência:** O sistema deve estar preparado para receber o mesmo webhook várias vezes sem duplicar encomendas ou envios.

### 2.3. Prevenção de Enumeration Attacks

- Os IDs das encomendas não devem ser sequenciais (ex: `Order #1001`, `Order #1002`), pois revela o volume de vendas.
- **Solução:** Usar UUIDs (Universally Unique Identifiers) ou CUIDs para referências de encomendas públicas.

---

## 3. Segurança da Aplicação (Next.js & Frontend)

### 3.1. Validação de Inputs (Zod)

Todos os dados que entram na aplicação (seja via formulário de contacto, checkout ou CMS) devem passar por validação rigorosa de esquema.

- **Sanitização:** Remover caracteres perigosos para prevenir SQL Injection (embora o Eloquent ORM trate a maioria) e XSS.
- **Zod:** Utilizar a biblioteca `zod` no frontend para garantir que os tipos de dados correspondem exatamente ao esperado antes de processar qualquer lógica.
- **Laravel Validation:** Utilizar Form Requests do Laravel para validação server-side.

### 3.2. Proteção XSS (Cross-Site Scripting)

Como o Aimeos envia Rich Text (HTML), existe risco de XSS armazenado se um editor de conteúdo for comprometido.

- **Solução:** Utilizar uma biblioteca de sanitização (como `dompurify` ou componentes seguros de renderização Markdown) ao exibir conteúdo HTML vindo do CMS. Nunca usar `dangerouslySetInnerHTML` sem sanitização prévia.

### 3.3. Cabeçalhos de Segurança HTTP (Security Headers)

Configurar no `next.config.js` (frontend) e Apache/Laravel (backend):

- **Content-Security-Policy (CSP):** Restringir as fontes de scripts, imagens e conexões (permitir apenas domínios próprios, Easypay, Cloudinary e Analytics aprovados).
- **X-Content-Type-Options:** `nosniff`.
- **X-Frame-Options:** `DENY` (para evitar Clickjacking).
- **Strict-Transport-Security (HSTS):** Forçar HTTPS.

---

## 4. Segurança do CMS (Laravel + Aimeos) e Infraestrutura

### 4.1. Hardening do Aimeos/Laravel

O painel de administração é a "chave do reino".

- **Restrição de Acesso:** Se possível, restringir o acesso à rota `/admin` apenas a IPs da Nazaré Qualifica ou via VPN.
- **Autenticação Forte:** Obrigatoriedade de passwords complexas para editores (Janete, Alexandre, etc.). Considerar ativar 2FA (Two-Factor Authentication) via Laravel Fortify.
- **API Tokens (Laravel Sanctum):**
    - Token Público: Apenas permissões de leitura para dados públicos.
    - Token Backend: Permissões completas necessárias, mas guardado em variáveis de ambiente seguras.

### 4.2. Base de Dados (MySQL 8.0 no VPS)

- **Não expor diretamente:** O frontend não deve comunicar diretamente com o MySQL. Toda a comunicação passa pela API do Laravel/Aimeos.
- **Firewall:** MySQL deve aceitar conexões apenas de `localhost` (127.0.0.1).
- **Backups:** Garantir política de backups diários automáticos via cPanel e retenção para recuperação em caso de Ransomware ou corrupção de dados.
- **Utilizador Restrito:** A aplicação deve usar um utilizador MySQL com privilégios mínimos necessários (não usar `root`).

---

## 5. Privacidade e Conformidade (GDPR)

Tratando-se de entidades municipais e e-commerce, o GDPR é mandatório.

### 5.1. Minimização de Dados

- Recolher apenas o estritamente necessário para o envio da encomenda (Nome, Morada, NIF, Email).
- Não armazenar dados sensíveis de pagamento (o número do cartão é processado pela Easypay, nós só guardamos o token/referência).

### 5.2. Retenção de Dados

- Definir políticas de limpeza de dados. Contas inativas ou carrinhos abandonados devem ser limpos periodicamente.

### 5.3. Consentimento

- Cookie Banner robusto e bloqueante até aceitação (Google Analytics, Pixels de Marketing).
- Checkboxes explícitas para Marketing (Newsletter) no checkout. Não podem vir pré-marcadas.

---

## 6. Plano de Ação Imediato (Checklist Técnica)

1. [ ] **Configurar Middleware de Segurança:** Implementar headers de segurança no Next.js e Laravel.
2. [ ] **Audit de Dependências:** Rodar `npm audit` e `composer audit` e corrigir vulnerabilidades críticas antes de cada deploy.
3. [ ] **Server-Side Pricing:** Implementar a lógica de cálculo de carrinho no Controller Laravel (`CheckoutController.php`), ignorando preços enviados pelo cliente.
4. [ ] **Validação de Webhook:** Criar middleware Laravel para verificar assinaturas HMAC da Easypay.
5. [ ] **Rate Limiting:** Configurar rate limiting nas rotas de API (`/api/auth/*`, `/api/checkout/*`) usando Laravel Rate Limiter.
6. [ ] **Revisão de Permissões Aimeos:** Confirmar que as Roles "Public" não têm acesso a dados de utilizadores ou encomendas.

---

## Histórico de Versões

| Versão | Data | Alterações |
|--------|------|------------|
| 1.0 | 2025-11-24 | Documento inicial (Strapi + Supabase) |
| 2.0 | 2025-11-25 | Atualizado para Laravel + Aimeos + MySQL |
