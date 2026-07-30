<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Closure;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use UnitEnum;

class SiteSettings extends Page
{
    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?int $navigationSort = 998;

    protected static ?string $navigationLabel = 'Definições';

    protected static ?string $title = 'Definições do Site';

    protected static ?string $slug = 'site-settings';

    protected string $view = 'filament.pages.site-settings';

    public bool $maintenance_mode = false;

    public ?string $maintenance_message_pt = '';

    public ?string $maintenance_message_en = '';

    public bool $stats_weekly_enabled = false;

    public ?string $stats_weekly_recipients = '';

    public ?string $carsurf_reservas_recipients = '';

    public function mount(): void
    {
        $this->maintenance_mode = SiteSetting::isMaintenanceMode();

        $message = SiteSetting::getMaintenanceMessage();
        $this->maintenance_message_pt = $message['pt'] ?? '';
        $this->maintenance_message_en = $message['en'] ?? '';

        $this->stats_weekly_enabled = SiteSetting::get('stats_weekly_enabled', '0') === '1';
        $this->stats_weekly_recipients = SiteSetting::get('stats_weekly_recipients', '');

        $this->carsurf_reservas_recipients = SiteSetting::get(
            'carsurf_reservas_recipients',
            SiteSetting::CARSURF_RESERVAS_FALLBACK,
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('maintenance_mode')
                    ->label('Ativar modo de manutenção')
                    ->helperText('Quando ativo, visitantes não autenticados verão uma página de manutenção.')
                    ->onColor('danger')
                    ->offColor('success')
                    ->live(),

                Tabs::make('maintenance_message')
                    ->tabs([
                        Tabs\Tab::make('Português')
                            ->icon('heroicon-o-flag')
                            ->schema([
                                Textarea::make('maintenance_message_pt')
                                    ->label('Mensagem de manutenção (PT)')
                                    ->placeholder('Estamos a melhorar o nosso website. Voltamos em breve com novidades.')
                                    ->rows(3),
                            ]),
                        Tabs\Tab::make('English')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Textarea::make('maintenance_message_en')
                                    ->label('Maintenance message (EN)')
                                    ->placeholder("We're improving our website. We'll be back shortly with updates.")
                                    ->rows(3),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Relatório Semanal de Estatísticas')
                    ->description('Envio automático de relatório semanal por email (segunda-feira às 8h)')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Toggle::make('stats_weekly_enabled')
                            ->label('Ativar relatório semanal')
                            ->helperText('Quando ativo, um relatório com estatísticas do website é enviado todas as segundas-feiras.')
                            ->onColor('success')
                            ->offColor('gray'),
                        TextInput::make('stats_weekly_recipients')
                            ->label('Destinatários')
                            ->helperText('Emails separados por vírgula. Ex: presidente@cm-nazare.pt, diretor@nazarequalifica.pt')
                            ->placeholder('email1@example.com, email2@example.com'),
                    ])
                    ->collapsible(),

                Section::make('Formulário de Reservas Carsurf')
                    ->description('Para onde são enviados os pedidos de reserva submetidos em /carsurf/reservas')
                    ->icon('heroicon-o-envelope')
                    ->schema([
                        TextInput::make('carsurf_reservas_recipients')
                            ->label('Destinatários')
                            ->required()
                            ->helperText('Emails separados por vírgula. O primeiro endereço da lista é também o que aparece publicamente na página de reservas.')
                            ->placeholder(SiteSetting::CARSURF_RESERVAS_FALLBACK)
                            ->rules([
                                fn (): Closure => function (string $attribute, mixed $value, Closure $fail) {
                                    $addresses = collect(explode(',', (string) $value))
                                        ->map(fn (string $email) => trim($email))
                                        ->filter();

                                    if ($addresses->isEmpty()) {
                                        $fail('Indique pelo menos um endereço de email.');

                                        return;
                                    }

                                    $invalid = $addresses->reject(
                                        fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false
                                    );

                                    if ($invalid->isNotEmpty()) {
                                        $fail('Endereço inválido: '.$invalid->implode(', '));
                                    }
                                },
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public function save(): void
    {
        // Triggers the schema's validation rules. Without this the form's rules
        // never run and invalid input is persisted silently.
        $this->form->getState();

        SiteSetting::set('maintenance_mode', $this->maintenance_mode ? '1' : '0');

        $message = array_filter([
            'pt' => $this->maintenance_message_pt ?: null,
            'en' => $this->maintenance_message_en ?: null,
        ]);

        SiteSetting::set('maintenance_message', $message ? json_encode($message) : null);

        SiteSetting::set('stats_weekly_enabled', $this->stats_weekly_enabled ? '1' : '0');
        SiteSetting::set('stats_weekly_recipients', $this->stats_weekly_recipients ?: '');

        SiteSetting::set(
            'carsurf_reservas_recipients',
            $this->carsurf_reservas_recipients ?: SiteSetting::CARSURF_RESERVAS_FALLBACK,
        );

        Notification::make()
            ->title('Definições guardadas')
            ->success()
            ->send();
    }
}
