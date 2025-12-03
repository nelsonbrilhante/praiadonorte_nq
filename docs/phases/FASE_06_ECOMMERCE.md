# Fase 6: E-commerce Setup

**Duração Estimada**: 1-2 semanas
**Dependências**: Fase 5 + Decisão API SAGE
**Bloco**: 4 - E-commerce

> **IMPORTANTE**: Esta fase aguarda análise da documentação API SAGE para decidir entre:
> - **Opção A**: Aimeos para gestão de produtos + SAGE para faturação/stock
> - **Opção B**: Integração direta com API SAGE
> - **Opção C**: Camada intermédia Laravel que abstrai SAGE

---

## Objetivos

- Configurar tipos de conteúdo no Aimeos
- Criar estrutura de produtos
- Configurar i18n

---

## Tarefas

### 2.1 Configuração do Aimeos

**`config/shop.php`**:

```php
return [
    'routes' => [
        'admin' => ['prefix' => 'admin', 'middleware' => ['web', 'auth']],
        'jsonapi' => ['prefix' => 'api', 'middleware' => ['api']],
    ],

    'page' => [
        'account-index' => ['account/profile', 'account/history'],
        'basket-index' => ['basket/standard', 'basket/related'],
        'catalog-list' => ['catalog/filter', 'catalog/list'],
    ],

    'client' => [
        'html' => [
            'common' => [
                'template' => [
                    'baseurl' => 'packages/aimeos/shop/themes/default',
                ],
            ],
        ],
    ],

    // Multi-site para as 3 entidades
    'mshop' => [
        'locale' => [
            'site' => 'praia-norte', // default
        ],
    ],
];
```

### 2.2 Estrutura de Produtos

**Categorias:**

```
Loja Praia do Norte
├── Vestuário
│   ├── T-Shirts
│   ├── Hoodies
│   └── Caps
├── Acessórios
│   ├── Stickers
│   └── Patches
├── Equipamento
│   ├── Pranchas (showcase)
│   └── Fatos
└── Colecionáveis
    └── Edições Limitadas
```

**Campos de Produto (Aimeos):**

| Campo | Tipo | i18n | Obrigatório |
|-------|------|------|-------------|
| label | string | Sim | Sim |
| code (SKU) | string | Não | Sim |
| description | text | Sim | Sim |
| price | decimal | Não | Sim |
| stock | integer | Não | Sim |
| images | media[] | Não | Sim |
| category | relation | Não | Sim |
| variants | relation[] | Não | Não |
| entity | enum | Não | Sim |

### 2.3 Tipos de Conteúdo Customizados

**Artigos/Notícias:**

```php
// Usando Aimeos CMS Manager
// Campos: title, slug, content, coverImage, author, category, entity, tags, publishedAt
```

**Surfistas (Surfer Wall):**

```php
// Campos: name, slug, bio, photo, nationality, achievements, surfboards, socialMedia, featured
```

**Eventos:**

```php
// Campos: title, description, startDate, endDate, location, entity, image, ticketUrl
```

### 2.4 Configurar Admin Users

```php
// Criar utilizadores no admin panel
// 1. Super Admin (desenvolvimento)
// 2. Content Manager (Janete)
// 3. Store Manager (Alexandre)
```

---

## Entregáveis

- [ ] Aimeos configurado com multi-site
- [ ] Categorias de produtos criadas
- [ ] Produtos de teste inseridos (PT + EN)
- [ ] Tipos de conteúdo customizados (Artigos, Surfistas, Eventos)
- [ ] Admin panel acessível com roles configuradas
- [ ] API endpoints testados

---

## Critérios de Conclusão

1. Admin panel funcional em `/admin`
2. Produtos visíveis via API `/api/jsonapi/product`
3. Traduções PT/EN funcionando
4. Categorias hierárquicas criadas
5. Pelo menos 5 produtos de teste inseridos
