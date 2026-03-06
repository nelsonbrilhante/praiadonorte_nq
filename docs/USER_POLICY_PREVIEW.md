# Política de Utilizadores e Permissões

**Projeto:** Plataforma Unificada Praia do Norte / Nazaré Qualifica / Carsurf
**Data:** 25 de Novembro de 2025
**Versão:** 2.0

## 1. Visão Geral

Este documento define a estrutura de utilizadores, papéis (roles) e permissões para a nova plataforma unificada. A gestão de conteúdos e loja online será centralizada no backend **Laravel + Aimeos**, enquanto a autenticação de clientes finais (loja) será gerida via **Laravel Sanctum**.

## 2. Tipos de Contas e Sistemas

Existem dois sistemas distintos de utilizadores:

1. **Utilizadores Internos (Backoffice/CMS):** Funcionários da Nazaré Qualifica/Praia do Norte que gerem conteúdos e encomendas. Geridos via **Aimeos Admin Panel**.
2. **Utilizadores Externos (Clientes):** Público geral que se regista para efetuar compras ou aceder a áreas reservadas. Geridos via **Laravel Sanctum + MySQL**.

---

## 3. Utilizadores Internos (CMS - Aimeos)

A gestão de conteúdos será dividida por responsabilidades funcionais.

### 3.1. Papéis (Roles) Propostos

#### **A. Super Admin (Administrador de Sistema)**

*   **Acesso:** Total a todas as configurações, plugins, tipos de conteúdo e utilizadores.
*   **Responsabilidade:** Manutenção técnica, gestão de acessos de staff.

#### **B. Gestor de Conteúdos (Content Manager)**

*   **Foco:** Editorial, Informação e Multimédia.
*   **Utilizador Principal:** Janete Vigia (`janete.vigia@nazarequalifica.pt`)
*   **Permissões de Escrita/Edição:**
    *   **Praia do Norte:** Notícias, Entrevistas, SurferWall.
    *   **Carsurf:** Recursos (Instalações), Conteúdos gerais.
    *   **Geral:** Live Cams (configuração básica), Previsões.
*   **Acesso de Leitura:** Loja Online.

#### **C. Gestor de Loja (Store Manager)**

*   **Foco:** E-commerce e Vendas.
*   **Utilizador Principal:** Alexandre Vinagre (`alexandre.vinagre@nazarequalifica.pt`)
*   **Permissões de Escrita/Edição:**
    *   **Catálogo:** Produtos, Categorias, Vouchers, Promoções.
    *   **Vendas:** Gestão de Encomendas (Orders), Clientes (Loja), Stocks.
*   **Acesso de Leitura:** Notícias e conteúdos institucionais.

#### **D. Gestor Institucional (Institutional Manager)**

*   **Foco:** Documentação Legal e Transparência.
*   **Utilizadores Potenciais:** Carlos Filipe (`carlos.filipe@nazarequalifica.pt`) e/ou responsáveis por áreas legais.
*   **Permissões de Escrita/Edição:**
    *   **Nazaré Qualifica:** Corpos Sociais, Ética e Transparência, Contraordenações.
    *   **Recursos Humanos:** (Se aplicável nesta fase).

### 3.2. Matriz de Acesso ao Conteúdo (CMS)

| Área de Conteúdo | Admin | Content Mgr (Janete) | Store Mgr (Alexandre) | Institucional Mgr |
| :--- | :---: | :---: | :---: | :---: |
| **Notícias / Blog** | Total | **Edição** | Leitura | Leitura |
| **SurferWall** | Total | **Edição** | Leitura | Leitura |
| **Produtos / Loja** | Total | Leitura | **Edição** | Leitura |
| **Encomendas** | Total | Leitura | **Gestão** | Leitura |
| **Recursos Carsurf** | Total | **Edição** | Leitura | Leitura |
| **Transparência/Legal**| Total | Leitura | Leitura | **Edição** |
| **Configurações Site** | Total | Leitura | Leitura | Leitura |

---

## 4. Utilizadores Externos (Frontend - Clientes)

Estes utilizadores acedem através do website público.

### 4.1. Visitante (Guest)

*   **Permissões:**
    *   Visualizar todo o conteúdo público (Notícias, SurferWall, Institucional).
    *   Visualizar catálogo de produtos.
    *   Adicionar produtos ao carrinho (sessão temporária).
    *   Submeter formulários de contacto geral.

### 4.2. Cliente Registado (Registered Customer)

*   **Autenticação:** Email/Password via Laravel Sanctum ou Social Login (Google/Facebook - a definir).
*   **Permissões Adicionais:**
    *   Finalizar compras (Checkout).
    *   Aceder à "Minha Conta":
        *   Histórico de Encomendas.
        *   Gerir moradas de envio e faturação.
        *   Gerir dados pessoais (PD).
    *   Wishlist (Lista de Desejos).

---

## 5. Política de Migração de Emails e Domínios

Conforme definido na política de utilizadores, haverá uma normalização dos domínios de email para `@nazarequalifica.pt`.

### 5.1. Contas a Migrar/Configurar

*   `janete.vigia@nazarequalifica.pt` (Novo standard)
*   `alexandre.vinagre@nazarequalifica.pt` (Novo standard)
*   `carlos.filipe@nazarequalifica.pt` (Novo standard)

### 5.2. Aliases Funcionais (Email Forwarding)

Para garantir a comunicação com o público, os seguintes endereços devem ser mantidos ou criados como aliases que encaminham para as caixas postais corretas (ou sistemas de tickets):

*   `forte@nazarequalifica.pt` -> Encaminhar para Responsável Forte / Bilheteira
*   `praiadonorte@nazarequalifica.pt` -> Geral Praia do Norte
*   `carsurf@nazarequalifica.pt` -> Geral Carsurf

---

## 6. Segurança e Privacidade (GDPR)

*   **Acesso Mínimo:** Os utilizadores internos devem ter apenas as permissões necessárias para a sua função (Princípio do Menor Privilégio).
*   **Dados Pessoais:** O acesso a dados de clientes (encomendas) deve ser restrito ao *Store Manager* e *Admin*.
*   **Logs de Auditoria:** O Aimeos deve registar quem criou ou editou cada conteúdo.

---

## Histórico de Versões

| Versão | Data | Alterações |
|--------|------|------------|
| 1.0 | 2025-11-24 | Documento inicial (Strapi + NextAuth.js) |
| 2.0 | 2025-11-25 | Atualizado para Laravel + Aimeos + Sanctum |
