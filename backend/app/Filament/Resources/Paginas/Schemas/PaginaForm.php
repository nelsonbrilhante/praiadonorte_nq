<?php

namespace App\Filament\Resources\Paginas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PaginaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações Básicas')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title.pt')
                                ->label('Título (PT)')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('slug', Str::slug($state))),
                            TextInput::make('title.en')
                                ->label('Title (EN)')
                                ->maxLength(255),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->unique(
                                    table: 'paginas',
                                    column: 'slug',
                                    ignoreRecord: true,
                                    modifyRuleUsing: fn ($rule, $get) => $rule->where('entity', $get('entity'))
                                )
                                ->helperText('Use "landing" para página principal de landing'),
                            Select::make('entity')
                                ->label('Entidade')
                                ->options([
                                    'praia-norte' => 'Praia do Norte',
                                    'carsurf' => 'Carsurf',
                                    'nazare-qualifica' => 'Nazaré Qualifica',
                                ])
                                ->default('praia-norte')
                                ->required()
                                ->live(),
                        ]),
                        Toggle::make('published')
                            ->label('Publicada')
                            ->default(true),
                    ]),

                // Conteúdo simples para páginas normais (usa Textarea para evitar conflitos com TipTap)
                // NÃO mostrar para páginas com secções específicas (landing, homepage, ou páginas NQ estruturadas)
                Section::make('Conteúdo')
                    ->schema([
                        Tabs::make('Idiomas')
                            ->tabs([
                                Tab::make('Português')
                                    ->icon('heroicon-o-flag')
                                    ->schema([
                                        Textarea::make('content.pt')
                                            ->label('Conteúdo (PT)')
                                            ->rows(10)
                                            ->columnSpanFull()
                                            ->formatStateUsing(fn ($state) => is_string($state) ? $state : ''),
                                    ]),
                                Tab::make('English')
                                    ->icon('heroicon-o-globe-alt')
                                    ->schema([
                                        Textarea::make('content.en')
                                            ->label('Content (EN)')
                                            ->rows(10)
                                            ->columnSpanFull()
                                            ->formatStateUsing(fn ($state) => is_string($state) ? $state : ''),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) =>
                        $get('slug') !== 'landing' &&
                        $get('slug') !== 'homepage' &&
                        // Excluir páginas NQ com secções específicas
                        !($get('entity') === 'nazare-qualifica' && in_array($get('slug'), ['sobre', 'equipa', 'servicos', 'carsurf', 'estacionamento', 'forte', 'ale']))
                    ),

                // ========== HOMEPAGE HERO SECTION (visível apenas para slug=homepage E entity=praia-norte) ==========

                Section::make('Hero da Homepage')
                    ->description('Vídeo de fundo e textos principais da homepage')
                    ->icon('heroicon-o-play-circle')
                    ->schema([
                        TextInput::make('content.pt.hero.youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=LDi6PQ4b6W8')
                            ->helperText('URL do vídeo YouTube para fundo do Hero')
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.title')
                                ->label('Título (PT)')
                                ->required()
                                ->default('Praia do Norte'),
                            TextInput::make('content.en.hero.title')
                                ->label('Title (EN)')
                                ->default('Praia do Norte'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.subtitle')
                                ->label('Subtítulo (PT)')
                                ->default('Onde nascem as ondas gigantes'),
                            TextInput::make('content.en.hero.subtitle')
                                ->label('Subtitle (EN)')
                                ->default('Where giant waves are born'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.cta_text')
                                ->label('Texto Botão (PT)')
                                ->default('Descobrir'),
                            TextInput::make('content.en.hero.cta_text')
                                ->label('Button Text (EN)')
                                ->default('Discover'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.cta_url')
                                ->label('URL Botão (PT)')
                                ->default('/sobre'),
                            TextInput::make('content.en.hero.cta_url')
                                ->label('Button URL (EN)')
                                ->default('/about'),
                        ]),
                    ])
                    ->visible(fn ($get) => $get('slug') === 'homepage' && $get('entity') === 'praia-norte')
                    ->collapsible(),

                // ========== LANDING PAGE SECTIONS (visíveis apenas para slug=landing) ==========

                // Hero Section
                Section::make('Hero')
                    ->description('Secção principal com vídeo de fundo')
                    ->icon('heroicon-o-play-circle')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('video_url')
                                ->label('Vídeo MP4 (upload)')
                                ->acceptedFileTypes(['video/mp4'])
                                ->disk('public')
                                ->directory('carsurf/videos')
                                ->visibility('public')
                                ->maxSize(102400) // 100MB max
                                ->helperText('Upload de ficheiro MP4 (máx 100MB)'),
                            TextInput::make('content.pt.hero.youtube_url')
                                ->label('YouTube URL')
                                ->url()
                                ->placeholder('https://youtu.be/dUqKdF-AcCQ')
                                ->helperText('Cole aqui o link do YouTube (alternativa ao MP4)'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.title')
                                ->label('Título (PT)')
                                ->required(),
                            TextInput::make('content.en.hero.title')
                                ->label('Title (EN)'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.subtitle')
                                ->label('Subtítulo (PT)'),
                            TextInput::make('content.en.hero.subtitle')
                                ->label('Subtitle (EN)'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.cta_primary')
                                ->label('Botão Primário (PT)')
                                ->placeholder('Ex: Visite-nos'),
                            TextInput::make('content.en.hero.cta_primary')
                                ->label('Primary Button (EN)')
                                ->placeholder('Ex: Visit Us'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.cta_secondary')
                                ->label('Botão Secundário (PT)')
                                ->placeholder('Ex: Ver Instalações'),
                            TextInput::make('content.en.hero.cta_secondary')
                                ->label('Secondary Button (EN)')
                                ->placeholder('Ex: View Facilities'),
                        ]),
                    ])
                    ->visible(fn ($get) => $get('slug') === 'landing')
                    ->collapsible(),

                // About Section
                Section::make('Sobre')
                    ->description('Apresentação do centro')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.about.title')
                                ->label('Título (PT)'),
                            TextInput::make('content.en.about.title')
                                ->label('Title (EN)'),
                        ]),
                        Grid::make(2)->schema([
                            Textarea::make('content.pt.about.text')
                                ->label('Texto (PT)')
                                ->rows(4),
                            Textarea::make('content.en.about.text')
                                ->label('Text (EN)')
                                ->rows(4),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.about.highlight')
                                ->label('Destaque (PT)')
                                ->placeholder('Texto em destaque'),
                            TextInput::make('content.en.about.highlight')
                                ->label('Highlight (EN)'),
                        ]),
                    ])
                    ->visible(fn ($get) => $get('slug') === 'landing')
                    ->collapsible()
                    ->collapsed(),

                // Facilities Section
                Section::make('Instalações')
                    ->description('Lista de instalações do centro')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Repeater::make('content.pt.facilities')
                            ->label('Instalações (PT)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Nome')
                                        ->required(),
                                    Select::make('icon')
                                        ->label('Ícone')
                                        ->options([
                                            'home' => 'Casa/Alojamento',
                                            'dumbbell' => 'Ginásio',
                                            'heart-pulse' => 'Fisioterapia',
                                            'monitor' => 'Digital',
                                            'archive' => 'Armazenamento',
                                            'sofa' => 'Relaxe',
                                            'clipboard-check' => 'Avaliação',
                                            'utensils' => 'Refeições',
                                        ]),
                                ]),
                                Textarea::make('description')
                                    ->label('Descrição')
                                    ->rows(2),
                                Grid::make(2)->schema([
                                    TextInput::make('price')
                                        ->label('Preço')
                                        ->placeholder('Ex: €15/cama'),
                                    TextInput::make('capacity')
                                        ->label('Capacidade')
                                        ->placeholder('Ex: 30 camas'),
                                ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->columnSpanFull(),
                        Repeater::make('content.en.facilities')
                            ->label('Facilities (EN)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Name')
                                        ->required(),
                                    Select::make('icon')
                                        ->label('Icon')
                                        ->options([
                                            'home' => 'Home/Accommodation',
                                            'dumbbell' => 'Gym',
                                            'heart-pulse' => 'Physiotherapy',
                                            'monitor' => 'Digital',
                                            'archive' => 'Storage',
                                            'sofa' => 'Relax',
                                            'clipboard-check' => 'Assessment',
                                            'utensils' => 'Meals',
                                        ]),
                                ]),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(2),
                                Grid::make(2)->schema([
                                    TextInput::make('price')
                                        ->label('Price'),
                                    TextInput::make('capacity')
                                        ->label('Capacity'),
                                ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => $get('slug') === 'landing')
                    ->collapsible()
                    ->collapsed(),

                // Team Section
                Section::make('Equipa')
                    ->description('Membros da equipa')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Repeater::make('content.pt.team')
                            ->label('Equipa (PT)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Nome')
                                        ->required(),
                                    TextInput::make('role')
                                        ->label('Cargo'),
                                ]),
                                Textarea::make('description')
                                    ->label('Descrição')
                                    ->rows(2),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email(),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->columnSpanFull(),
                        Repeater::make('content.en.team')
                            ->label('Team (EN)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Name')
                                        ->required(),
                                    TextInput::make('role')
                                        ->label('Role'),
                                ]),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(2),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email(),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => $get('slug') === 'landing')
                    ->collapsible()
                    ->collapsed(),

                // Contact Section
                Section::make('Contacto')
                    ->description('Informações de contacto')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.contact.phone')
                                ->label('Telefone')
                                ->tel()
                                ->placeholder('+351 XXX XXX XXX'),
                            TextInput::make('content.pt.contact.email')
                                ->label('Email')
                                ->email(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.contact.hours')
                                ->label('Horário')
                                ->placeholder('24/7'),
                            TextInput::make('content.pt.contact.address')
                                ->label('Morada'),
                        ]),
                        TextInput::make('content.pt.contact.maps_url')
                            ->label('URL Google Maps')
                            ->url()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => $get('slug') === 'landing')
                    ->collapsible()
                    ->collapsed(),

                // Partners Section
                Section::make('Parceiros')
                    ->description('Texto sobre parcerias')
                    ->icon('heroicon-o-building-library')
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('content.pt.partners.text')
                                ->label('Texto (PT)')
                                ->rows(3),
                            Textarea::make('content.en.partners.text')
                                ->label('Text (EN)')
                                ->rows(3),
                        ]),
                    ])
                    ->visible(fn ($get) => $get('slug') === 'landing')
                    ->collapsible()
                    ->collapsed(),

                // ========== NQ ABOUT PAGE (entity=nazare-qualifica, slug=sobre) ==========
                Section::make('Sobre a Empresa')
                    ->description('Introdução e objetivos da Nazaré Qualifica')
                    ->icon('heroicon-o-building-office-2')
                    ->schema([
                        // Intro Section
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.intro.title')
                                ->label('Título Intro (PT)')
                                ->default('Sobre a Empresa')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.intro.title', $record?->content['pt']['intro']['title'] ?? $state)),
                            TextInput::make('content.en.intro.title')
                                ->label('Intro Title (EN)')
                                ->default('About the Company')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.intro.title', $record?->content['en']['intro']['title'] ?? $state)),
                        ]),
                        Grid::make(2)->schema([
                            Textarea::make('content.pt.intro.text')
                                ->label('Texto Intro (PT)')
                                ->rows(4)
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.intro.text', $record?->content['pt']['intro']['text'] ?? $state)),
                            Textarea::make('content.en.intro.text')
                                ->label('Intro Text (EN)')
                                ->rows(4)
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.intro.text', $record?->content['en']['intro']['text'] ?? $state)),
                        ]),
                        // Objectives Repeater
                        Repeater::make('content.pt.objectives')
                            ->label('Objetivos (PT)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('title')
                                        ->label('Título')
                                        ->required(),
                                    Select::make('icon')
                                        ->label('Ícone')
                                        ->options([
                                            'building' => 'Edifício',
                                            'car' => 'Estacionamento',
                                            'landmark' => 'Forte/Monumento',
                                            'factory' => 'Indústria/ALE',
                                            'waves' => 'Surf/Ondas',
                                            'users' => 'Pessoas/Equipa',
                                            'briefcase' => 'Negócios',
                                            'target' => 'Objetivo',
                                        ])
                                        ->default('building'),
                                ]),
                                Textarea::make('description')
                                    ->label('Descrição')
                                    ->rows(2),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.pt.objectives', $record?->content['pt']['objectives'] ?? $state))
                            ->columnSpanFull(),
                        Repeater::make('content.en.objectives')
                            ->label('Objectives (EN)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('title')
                                        ->label('Title')
                                        ->required(),
                                    Select::make('icon')
                                        ->label('Icon')
                                        ->options([
                                            'building' => 'Building',
                                            'car' => 'Parking',
                                            'landmark' => 'Fort/Monument',
                                            'factory' => 'Industry/ALE',
                                            'waves' => 'Surf/Waves',
                                            'users' => 'People/Team',
                                            'briefcase' => 'Business',
                                            'target' => 'Goal',
                                        ])
                                        ->default('building'),
                                ]),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(2),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.en.objectives', $record?->content['en']['objectives'] ?? $state))
                            ->columnSpanFull(),
                        // CTA Section
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.cta.title')
                                ->label('CTA Título (PT)')
                                ->default('Conheça os Nossos Serviços')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.cta.title', $record?->content['pt']['cta']['title'] ?? $state)),
                            TextInput::make('content.en.cta.title')
                                ->label('CTA Title (EN)')
                                ->default('Discover Our Services')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.cta.title', $record?->content['en']['cta']['title'] ?? $state)),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.cta.subtitle')
                                ->label('CTA Subtítulo (PT)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.cta.subtitle', $record?->content['pt']['cta']['subtitle'] ?? $state)),
                            TextInput::make('content.en.cta.subtitle')
                                ->label('CTA Subtitle (EN)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.cta.subtitle', $record?->content['en']['cta']['subtitle'] ?? $state)),
                        ]),
                    ])
                    ->visible(fn ($get) => $get('entity') === 'nazare-qualifica' && $get('slug') === 'sobre')
                    ->collapsible(),

                // ========== NQ TEAM PAGE (entity=nazare-qualifica, slug=equipa) ==========
                Section::make('Corpos Sociais')
                    ->description('Órgãos de gestão da empresa')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        // Conselho de Gerência
                        Repeater::make('content.pt.conselho')
                            ->label('Conselho de Gerência (PT)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Nome')
                                        ->required(),
                                    TextInput::make('role')
                                        ->label('Cargo')
                                        ->placeholder('Presidente, 1º Vogal, etc.'),
                                ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => ($state['name'] ?? '') . ' - ' . ($state['role'] ?? ''))
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.pt.conselho', $record?->content['pt']['conselho'] ?? $state))
                            ->columnSpanFull(),
                        Repeater::make('content.en.conselho')
                            ->label('Board of Directors (EN)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Name')
                                        ->required(),
                                    TextInput::make('role')
                                        ->label('Role')
                                        ->placeholder('President, 1st Member, etc.'),
                                ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => ($state['name'] ?? '') . ' - ' . ($state['role'] ?? ''))
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.en.conselho', $record?->content['en']['conselho'] ?? $state))
                            ->columnSpanFull(),
                        // Assembleia Geral
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.assembleia.name')
                                ->label('Assembleia Geral - Nome (PT)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.assembleia.name', $record?->content['pt']['assembleia']['name'] ?? $state)),
                            TextInput::make('content.pt.assembleia.role')
                                ->label('Assembleia Geral - Cargo (PT)')
                                ->default('Presidente')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.assembleia.role', $record?->content['pt']['assembleia']['role'] ?? $state)),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.en.assembleia.name')
                                ->label('General Assembly - Name (EN)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.assembleia.name', $record?->content['en']['assembleia']['name'] ?? $state)),
                            TextInput::make('content.en.assembleia.role')
                                ->label('General Assembly - Role (EN)')
                                ->default('President')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.assembleia.role', $record?->content['en']['assembleia']['role'] ?? $state)),
                        ]),
                        // Fiscal Único
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.fiscal.company')
                                ->label('Fiscal Único - Empresa (PT)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.fiscal.company', $record?->content['pt']['fiscal']['company'] ?? $state)),
                            TextInput::make('content.pt.fiscal.representative')
                                ->label('Fiscal Único - Representante (PT)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.fiscal.representative', $record?->content['pt']['fiscal']['representative'] ?? $state)),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('content.en.fiscal.company')
                                ->label('Sole Auditor - Company (EN)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.fiscal.company', $record?->content['en']['fiscal']['company'] ?? $state)),
                            TextInput::make('content.en.fiscal.representative')
                                ->label('Sole Auditor - Representative (EN)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.fiscal.representative', $record?->content['en']['fiscal']['representative'] ?? $state)),
                        ]),
                    ])
                    ->visible(fn ($get) => $get('entity') === 'nazare-qualifica' && $get('slug') === 'equipa')
                    ->collapsible(),

                // ========== NQ SERVICES PAGE (entity=nazare-qualifica, slug=servicos) ==========
                Section::make('Lista de Serviços')
                    ->description('Cartões dos 4 serviços principais')
                    ->icon('heroicon-o-squares-2x2')
                    ->schema([
                        Repeater::make('content.pt.services')
                            ->label('Serviços (PT)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('slug')
                                        ->label('Slug (URL)')
                                        ->required()
                                        ->placeholder('carsurf, estacionamento, forte, ale'),
                                    Select::make('icon')
                                        ->label('Ícone')
                                        ->options([
                                            'waves' => 'Surf/Ondas',
                                            'car' => 'Estacionamento',
                                            'landmark' => 'Forte/Monumento',
                                            'factory' => 'Indústria/ALE',
                                        ])
                                        ->required(),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('title')
                                        ->label('Título')
                                        ->required(),
                                    TextInput::make('shortDescription')
                                        ->label('Descrição Curta'),
                                ]),
                                Select::make('color')
                                    ->label('Cor do Card')
                                    ->options([
                                        'ocean' => 'Azul (Ocean)',
                                        'blue' => 'Azul Claro',
                                        'amber' => 'Âmbar/Dourado',
                                        'green' => 'Verde',
                                    ])
                                    ->default('ocean'),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.pt.services', $record?->content['pt']['services'] ?? $state))
                            ->columnSpanFull(),
                        Repeater::make('content.en.services')
                            ->label('Services (EN)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('slug')
                                        ->label('Slug (URL)')
                                        ->required(),
                                    Select::make('icon')
                                        ->label('Icon')
                                        ->options([
                                            'waves' => 'Surf/Waves',
                                            'car' => 'Parking',
                                            'landmark' => 'Fort/Monument',
                                            'factory' => 'Industry/ALE',
                                        ])
                                        ->required(),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('title')
                                        ->label('Title')
                                        ->required(),
                                    TextInput::make('shortDescription')
                                        ->label('Short Description'),
                                ]),
                                Select::make('color')
                                    ->label('Card Color')
                                    ->options([
                                        'ocean' => 'Blue (Ocean)',
                                        'blue' => 'Light Blue',
                                        'amber' => 'Amber/Gold',
                                        'green' => 'Green',
                                    ])
                                    ->default('ocean'),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.en.services', $record?->content['en']['services'] ?? $state))
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get) => $get('entity') === 'nazare-qualifica' && $get('slug') === 'servicos')
                    ->collapsible(),

                // ========== NQ SERVICE DETAIL PAGES (entity=nazare-qualifica, slug=carsurf|estacionamento|forte|ale) ==========
                Section::make('Detalhes do Serviço')
                    ->description('Conteúdo específico do serviço')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        // Description
                        Grid::make(2)->schema([
                            Textarea::make('content.pt.description')
                                ->label('Descrição (PT)')
                                ->rows(4)
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.description', $record?->content['pt']['description'] ?? $state)),
                            Textarea::make('content.en.description')
                                ->label('Description (EN)')
                                ->rows(4)
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.description', $record?->content['en']['description'] ?? $state)),
                        ]),
                        // Features Repeater
                        Repeater::make('content.pt.features')
                            ->label('Características (PT)')
                            ->simple(
                                TextInput::make('feature')
                                    ->label('Característica')
                                    ->required(),
                            )
                            ->defaultItems(0)
                            ->reorderable()
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.pt.features', $record?->content['pt']['features'] ?? $state))
                            ->columnSpanFull(),
                        Repeater::make('content.en.features')
                            ->label('Features (EN)')
                            ->simple(
                                TextInput::make('feature')
                                    ->label('Feature')
                                    ->required(),
                            )
                            ->defaultItems(0)
                            ->reorderable()
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.en.features', $record?->content['en']['features'] ?? $state))
                            ->columnSpanFull(),
                        // Stats (for Forte and ALE)
                        Repeater::make('content.pt.stats')
                            ->label('Estatísticas/Números (PT)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('value')
                                        ->label('Valor')
                                        ->required()
                                        ->placeholder('1577, 650k+, etc.'),
                                    TextInput::make('label')
                                        ->label('Legenda')
                                        ->required()
                                        ->placeholder('Construído em, Visitantes/ano, etc.'),
                                ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => ($state['value'] ?? '') . ' - ' . ($state['label'] ?? ''))
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.pt.stats', $record?->content['pt']['stats'] ?? $state))
                            ->columnSpanFull(),
                        Repeater::make('content.en.stats')
                            ->label('Stats/Numbers (EN)')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('value')
                                        ->label('Value')
                                        ->required(),
                                    TextInput::make('label')
                                        ->label('Label')
                                        ->required(),
                                ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => ($state['value'] ?? '') . ' - ' . ($state['label'] ?? ''))
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('content.en.stats', $record?->content['en']['stats'] ?? $state))
                            ->columnSpanFull(),
                        // Contact info specific to service
                        Grid::make(2)->schema([
                            TextInput::make('content.pt.contact.phone')
                                ->label('Telefone')
                                ->tel()
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.contact.phone', $record?->content['pt']['contact']['phone'] ?? $state)),
                            TextInput::make('content.pt.contact.email')
                                ->label('Email')
                                ->email()
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.contact.email', $record?->content['pt']['contact']['email'] ?? $state)),
                        ]),
                    ])
                    ->visible(fn ($get) => $get('entity') === 'nazare-qualifica' && in_array($get('slug'), ['carsurf', 'estacionamento', 'forte', 'ale']))
                    ->collapsible(),

                // SEO Section
                Section::make('SEO')
                    ->schema([
                        Tabs::make('SEO Idiomas')
                            ->tabs([
                                Tab::make('PT')
                                    ->schema([
                                        TextInput::make('seo_title.pt')
                                            ->label('SEO Título (PT)')
                                            ->maxLength(60),
                                        Textarea::make('seo_description.pt')
                                            ->label('SEO Descrição (PT)')
                                            ->rows(2)
                                            ->maxLength(160),
                                    ]),
                                Tab::make('EN')
                                    ->schema([
                                        TextInput::make('seo_title.en')
                                            ->label('SEO Title (EN)')
                                            ->maxLength(60),
                                        Textarea::make('seo_description.en')
                                            ->label('SEO Description (EN)')
                                            ->rows(2)
                                            ->maxLength(160),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
