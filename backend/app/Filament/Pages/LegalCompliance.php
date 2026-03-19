<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Tiptap\Editor;
use UnitEnum;

class LegalCompliance extends Page
{
    public static function canAccess(): bool
    {
        return auth()->user()->canAccessEntity('nazare-qualifica');
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static string|UnitEnum|null $navigationGroup = 'Nazaré Qualifica';

    protected static ?int $navigationSort = 999;

    protected static ?string $navigationLabel = 'Conformidade Legal';

    protected static ?string $title = 'Conformidade Legal';

    protected static ?string $slug = 'legal-compliance';

    protected string $view = 'filament.pages.legal-compliance';

    // Privacy
    public ?string $privacy_title_pt = '';

    public ?string $privacy_title_en = '';

    public string|array|null $privacy_content_pt = '';

    public string|array|null $privacy_content_en = '';

    public ?string $privacy_last_updated = '';

    // Terms
    public ?string $terms_title_pt = '';

    public ?string $terms_title_en = '';

    public string|array|null $terms_content_pt = '';

    public string|array|null $terms_content_en = '';

    public ?string $terms_last_updated = '';

    // Cookies
    public ?string $cookies_title_pt = '';

    public ?string $cookies_title_en = '';

    public string|array|null $cookies_content_pt = '';

    public string|array|null $cookies_content_en = '';

    public ?string $cookies_last_updated = '';

    // Disputes
    public ?string $disputes_title_pt = '';

    public ?string $disputes_title_en = '';

    public string|array|null $disputes_content_pt = '';

    public string|array|null $disputes_content_en = '';

    public ?string $disputes_last_updated = '';

    public function mount(): void
    {
        $privacy = SiteSetting::getJson('legal_privacy', []);
        $this->privacy_title_pt = $privacy['pt']['title'] ?? '';
        $this->privacy_title_en = $privacy['en']['title'] ?? '';
        $this->privacy_content_pt = $privacy['pt']['content'] ?? '';
        $this->privacy_content_en = $privacy['en']['content'] ?? '';
        $this->privacy_last_updated = $privacy['last_updated'] ?? '';

        $terms = SiteSetting::getJson('legal_terms', []);
        $this->terms_title_pt = $terms['pt']['title'] ?? '';
        $this->terms_title_en = $terms['en']['title'] ?? '';
        $this->terms_content_pt = $terms['pt']['content'] ?? '';
        $this->terms_content_en = $terms['en']['content'] ?? '';
        $this->terms_last_updated = $terms['last_updated'] ?? '';

        $cookies = SiteSetting::getJson('legal_cookies', []);
        $this->cookies_title_pt = $cookies['pt']['title'] ?? '';
        $this->cookies_title_en = $cookies['en']['title'] ?? '';
        $this->cookies_content_pt = $cookies['pt']['content'] ?? '';
        $this->cookies_content_en = $cookies['en']['content'] ?? '';
        $this->cookies_last_updated = $cookies['last_updated'] ?? '';

        $disputes = SiteSetting::getJson('legal_disputes', []);
        $this->disputes_title_pt = $disputes['pt']['title'] ?? '';
        $this->disputes_title_en = $disputes['en']['title'] ?? '';
        $this->disputes_content_pt = $disputes['pt']['content'] ?? '';
        $this->disputes_content_en = $disputes['en']['content'] ?? '';
        $this->disputes_last_updated = $disputes['last_updated'] ?? '';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Legal')
                    ->tabs([
                        Tabs\Tab::make('Privacidade')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Tabs::make('privacy_langs')
                                    ->tabs([
                                        Tabs\Tab::make('Português')
                                            ->icon('heroicon-o-flag')
                                            ->schema([
                                                TextInput::make('privacy_title_pt')
                                                    ->label('Título')
                                                    ->columnSpanFull(),
                                                RichEditor::make('privacy_content_pt')
                                                    ->label('Conteúdo')
                                                    ->columnSpanFull(),
                                            ]),
                                        Tabs\Tab::make('English')
                                            ->icon('heroicon-o-globe-alt')
                                            ->schema([
                                                TextInput::make('privacy_title_en')
                                                    ->label('Title')
                                                    ->columnSpanFull(),
                                                RichEditor::make('privacy_content_en')
                                                    ->label('Content')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                                TextInput::make('privacy_last_updated')
                                    ->label('Última atualização')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        Tabs\Tab::make('Termos e Condições')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Tabs::make('terms_langs')
                                    ->tabs([
                                        Tabs\Tab::make('Português')
                                            ->icon('heroicon-o-flag')
                                            ->schema([
                                                TextInput::make('terms_title_pt')
                                                    ->label('Título')
                                                    ->columnSpanFull(),
                                                RichEditor::make('terms_content_pt')
                                                    ->label('Conteúdo')
                                                    ->columnSpanFull(),
                                            ]),
                                        Tabs\Tab::make('English')
                                            ->icon('heroicon-o-globe-alt')
                                            ->schema([
                                                TextInput::make('terms_title_en')
                                                    ->label('Title')
                                                    ->columnSpanFull(),
                                                RichEditor::make('terms_content_en')
                                                    ->label('Content')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                                TextInput::make('terms_last_updated')
                                    ->label('Última atualização')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        Tabs\Tab::make('Cookies')
                            ->icon('heroicon-o-cursor-arrow-ripple')
                            ->schema([
                                Tabs::make('cookies_langs')
                                    ->tabs([
                                        Tabs\Tab::make('Português')
                                            ->icon('heroicon-o-flag')
                                            ->schema([
                                                TextInput::make('cookies_title_pt')
                                                    ->label('Título')
                                                    ->columnSpanFull(),
                                                RichEditor::make('cookies_content_pt')
                                                    ->label('Conteúdo')
                                                    ->columnSpanFull(),
                                            ]),
                                        Tabs\Tab::make('English')
                                            ->icon('heroicon-o-globe-alt')
                                            ->schema([
                                                TextInput::make('cookies_title_en')
                                                    ->label('Title')
                                                    ->columnSpanFull(),
                                                RichEditor::make('cookies_content_en')
                                                    ->label('Content')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                                TextInput::make('cookies_last_updated')
                                    ->label('Última atualização')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        Tabs\Tab::make('Litígios')
                            ->icon('heroicon-o-shield-exclamation')
                            ->schema([
                                Tabs::make('disputes_langs')
                                    ->tabs([
                                        Tabs\Tab::make('Português')
                                            ->icon('heroicon-o-flag')
                                            ->schema([
                                                TextInput::make('disputes_title_pt')
                                                    ->label('Título')
                                                    ->columnSpanFull(),
                                                RichEditor::make('disputes_content_pt')
                                                    ->label('Conteúdo')
                                                    ->helperText('Este conteúdo é injectado na página de Termos com id="litigios" para suportar links directos (#litigios)')
                                                    ->columnSpanFull(),
                                            ]),
                                        Tabs\Tab::make('English')
                                            ->icon('heroicon-o-globe-alt')
                                            ->schema([
                                                TextInput::make('disputes_title_en')
                                                    ->label('Title')
                                                    ->columnSpanFull(),
                                                RichEditor::make('disputes_content_en')
                                                    ->label('Content')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                                TextInput::make('disputes_last_updated')
                                    ->label('Última atualização')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Convert Tiptap JSON array to HTML string.
     */
    private function toHtml(string|array|null $content): string
    {
        if (is_array($content)) {
            return (new Editor)->setContent($content)->getHtml();
        }

        return $content ?? '';
    }

    public function save(): void
    {
        $now = now()->format('Y-m-d H:i:s');

        SiteSetting::set('legal_privacy', json_encode([
            'pt' => [
                'title' => $this->privacy_title_pt,
                'content' => $this->toHtml($this->privacy_content_pt),
            ],
            'en' => [
                'title' => $this->privacy_title_en,
                'content' => $this->toHtml($this->privacy_content_en),
            ],
            'last_updated' => $now,
        ]));

        SiteSetting::set('legal_terms', json_encode([
            'pt' => [
                'title' => $this->terms_title_pt,
                'content' => $this->toHtml($this->terms_content_pt),
            ],
            'en' => [
                'title' => $this->terms_title_en,
                'content' => $this->toHtml($this->terms_content_en),
            ],
            'last_updated' => $now,
        ]));

        SiteSetting::set('legal_cookies', json_encode([
            'pt' => [
                'title' => $this->cookies_title_pt,
                'content' => $this->toHtml($this->cookies_content_pt),
            ],
            'en' => [
                'title' => $this->cookies_title_en,
                'content' => $this->toHtml($this->cookies_content_en),
            ],
            'last_updated' => $now,
        ]));

        SiteSetting::set('legal_disputes', json_encode([
            'pt' => [
                'title' => $this->disputes_title_pt,
                'content' => $this->toHtml($this->disputes_content_pt),
            ],
            'en' => [
                'title' => $this->disputes_title_en,
                'content' => $this->toHtml($this->disputes_content_en),
            ],
            'last_updated' => $now,
        ]));

        $this->privacy_last_updated = $now;
        $this->terms_last_updated = $now;
        $this->cookies_last_updated = $now;
        $this->disputes_last_updated = $now;

        Notification::make()
            ->title('Conteúdo legal guardado')
            ->success()
            ->send();
    }
}
