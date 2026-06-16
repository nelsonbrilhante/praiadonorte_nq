<?php

use App\Models\SiteSetting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // Laravel 12 enables event auto-discovery by default. Auth/model event listeners
    // are registered explicitly in AppServiceProvider::boot(), so discovery would
    // register them a second time and duplicate every audit-log entry. Disable it so
    // the explicit registration is the single source of truth.
    ->withEvents(discover: false)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('stats:send-weekly')
            ->weeklyOn(1, '12:00') // Monday at 12:00 (noon)
            ->when(fn () => SiteSetting::get('stats_weekly_enabled', '0') === '1');

        // Prune old activity log entries (respects config/activitylog.php clean_after_days).
        // --force is required because the command uses ConfirmableTrait and would otherwise
        // abort with "APPLICATION IN PRODUCTION" (exit 1) on every non-interactive run.
        $schedule->command('activitylog:clean --force')->daily();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies (container behind Cloudflare + Traefik)
        $middleware->trustProxies(at: '*');

        // Laravel Localization middleware aliases
        $middleware->alias([
            'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
