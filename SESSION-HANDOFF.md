# Session Handoff - Praia do Norte

> Este ficheiro serve como ponto de continuidade entre sessões de desenvolvimento.
> Lê-lo no início de cada sessão para retomar o contexto.

---

## Última Sessão

- **Data**: 2025-12-11
- **Resumo**: Reorganização do menu Filament por entidade + correção da hidratação de formulários NQ

---

## Estado Actual do Projecto

| Item | Valor |
|------|-------|
| **Fase** | CMS Completo - Frontend em migração |
| **Branch** | `main` |
| **Backend** | Laravel 12.41.1 + Filament 4.2.4 |
| **Frontend** | Blade + Livewire (em migração de Next.js) |
| **i18n** | Laravel localization configurado |
| **Admin Theme** | Navy Blue (#1e3a5f) |

---

## O Que Foi Feito (Sessão Actual)

### 1. Reorganização do Menu Filament por Entidade

O menu lateral do Filament foi completamente reorganizado para separar conteúdos por entidade:

**Estrutura Anterior:**
```
Páginas (todas misturadas)
Surfer Wall
Conteúdo
```

**Nova Estrutura:**
```
📊 Dashboard

🏠 Geral
   └── Homepage

🏋️ Carsurf
   └── Páginas

🏢 Nazaré Qualifica
   └── Páginas

📰 Conteúdo
   ├── Notícias
   └── Eventos

🌊 Praia do Norte
   ├── Páginas
   ├── Surfers
   └── Pranchas

🌐 Website
   └── Ver Website (abre em nova aba)
```

### 2. Ficheiros Criados

```
backend/app/Filament/Resources/
├── Geral/
│   ├── HomepageResource.php          # Resource dedicado para Homepage
│   └── Pages/
│       ├── ListHomepages.php
│       └── EditHomepage.php
├── Paginas/
│   └── BasePageResource.php          # Classe base abstracta para Resources por entidade
├── PraiaNorte/
│   └── PraiaNortePageResource.php    # Páginas Praia do Norte (exclui homepage)
├── Carsurf/
│   └── CarsurfPageResource.php       # Páginas Carsurf
└── NazareQualifica/
    └── NQPageResource.php            # Páginas Nazaré Qualifica
```

### 3. Correcção da Hidratação de Formulários NQ

Os formulários das páginas Nazaré Qualifica não carregavam os dados da BD. Corrigido com `afterStateHydrated()` em todos os campos:

- **Sobre a Empresa**: intro, objectives, CTA
- **Corpos Sociais**: conselho, assembleia, fiscal
- **Lista de Serviços**: services repeaters
- **Detalhes do Serviço**: description, features, stats, contact

### 4. Modificações em Ficheiros Existentes

```
backend/app/Filament/Resources/
├── Paginas/
│   ├── PaginaResource.php            # Oculto da navegação ($shouldRegisterNavigation = false)
│   └── Schemas/PaginaForm.php        # Adicionado afterStateHydrated() a todos os campos NQ
├── Surfers/
│   └── SurferResource.php            # Movido para grupo "Praia do Norte"
└── Surfboards/
    └── SurfboardResource.php         # Movido para grupo "Praia do Norte"

backend/app/Providers/Filament/
└── AdminPanelProvider.php            # Adicionado link "Ver Website" no menu
```

---

## URLs de Desenvolvimento

| Serviço | URL |
|---------|-----|
| **Site Público** | http://localhost:8000/pt |
| **Site EN** | http://localhost:8000/en |
| **Filament Admin** | http://localhost:8000/admin |

**Credenciais Filament:**
- Email: `admin@nazarequalifica.pt`
- Password: `password`

**Scripts:**
```bash
./scripts/start.sh    # Iniciar servidor Laravel
./scripts/stop.sh     # Parar servidor
```

---

## Arquitectura dos Resources por Entidade

### Padrão Implementado

```php
// BasePageResource.php - Classe base abstracta
abstract class BasePageResource extends Resource
{
    abstract public static function getEntityFilter(): string;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('entity', static::getEntityFilter());
    }
}

// NQPageResource.php - Exemplo de implementação
class NQPageResource extends BasePageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Nazaré Qualifica';

    public static function getEntityFilter(): string
    {
        return 'nazare-qualifica';
    }
}
```

### Hidratação de Campos JSON Aninhados

O Filament 4 não hidrata automaticamente campos com paths como `content.pt.intro.title`. Solução:

```php
TextInput::make('content.pt.intro.title')
    ->afterStateHydrated(fn ($state, $set, $record) =>
        $set('content.pt.intro.title', $record?->content['pt']['intro']['title'] ?? $state))
```

---

## Próximas Tarefas

### Prioridade Alta
1. [ ] Continuar migração de páginas Next.js → Blade
2. [ ] Converter Homepage para Blade
3. [ ] Converter páginas de Notícias para Blade
4. [ ] Converter páginas de Eventos para Blade

### Prioridade Média
1. [ ] Converter Surfer Wall para Blade
2. [ ] Converter Previsões para Blade
3. [ ] Converter Carsurf landing para Blade

### Prioridade Baixa
1. [ ] Reduzir espaçamento vertical no menu Filament (CSS customizado)
2. [ ] SEO metadata
3. [ ] Performance optimization
4. [ ] Security headers

---

## Notas Técnicas Importantes

### Filament 4 - Namespaces Diferentes

```php
// Correcto no Filament 4
use Filament\Actions\EditAction;        // ✅
use Filament\Actions\DeleteAction;      // ✅

// Incorrecto (Filament 3)
use Filament\Tables\Actions\EditAction; // ❌
```

### viteTheme() Causa Problemas

Não usar `->viteTheme()` no AdminPanelProvider - quebra o carregamento do CSS do Filament. Para CSS customizado, usar outro método.

### Entity Filter nas Queries

Cada Resource de páginas filtra por `entity`:
- `praia-norte` - Praia do Norte (exclui homepage)
- `carsurf` - Carsurf
- `nazare-qualifica` - Nazaré Qualifica
- Homepage usa query `where('slug', 'homepage')` (sem filtro de entity)

---

## Como Continuar

```bash
# 1. Ler este ficheiro para contexto
# 2. Iniciar servidor
cd backend && php artisan serve

# 3. Em outro terminal, iniciar Vite (para assets)
cd backend && npm run dev

# 4. Aceder ao admin
open http://localhost:8000/admin

# 5. Continuar migração das páginas para Blade
# 6. Actualizar este ficheiro no final da sessão
```
