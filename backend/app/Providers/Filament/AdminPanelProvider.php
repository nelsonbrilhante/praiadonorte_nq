<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\SetLocaleMiddleware;
use App\Filament\Pages\Auth\Login;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName('Nazaré Qualifica')
            ->brandLogo(asset('images/logos/nq-horizontal.svg'))
            ->darkModeBrandLogo(asset('images/logos/nq-horizontal-white.svg'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.svg'))
            ->colors([
                'primary' => '#1e3a5f', // Navy Blue - Nazaré Qualifica
            ])
            ->viteTheme('resources/css/filament/admin.css')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => Blade::render('
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
                ')
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn () => request()->routeIs('filament.admin.auth.login')
                    ? Blade::render('
                        <div id="login-branding-panel" class="hidden md:flex" style="background: linear-gradient(180deg, rgba(30, 58, 95, 0.5) 0%, rgba(30, 58, 95, 0.25) 50%, rgba(30, 58, 95, 0.45) 100%), url(\'{{ asset(\'images/login-page-background.jpg\') }}\');">
                            <div class="branding-content">
                                <img
                                    src="{{ asset(\'images/logos/nq-vertical-white.svg\') }}"
                                    alt="Nazaré Qualifica"
                                    class="branding-logo"
                                >
                                <h2 class="branding-title">Nazaré Qualifica</h2>
                                <p class="branding-tagline">Empresa Municipal</p>
                                <p class="branding-subtitle">Sistema de Gestão e Administração</p>
                            </div>
                        </div>
                    ')
                    : ''
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocaleMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationItems([
                NavigationItem::make('Ver Website')
                    ->url('/pt', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt')
                    ->group('Website')
                    ->sort(999),
            ]);
    }
}
