<?php

namespace App\Providers;

use App\Listeners\LogAuthEvents;
use App\Models\ActivityLog;
use App\Observers\ActivityLogObserver;
use App\Services\WooCommerceService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WooCommerceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Observer: auto-fill ip_address and user_agent on every activity log entry
        ActivityLog::observe(ActivityLogObserver::class);

        // Auth event listeners: login / logout / failed login
        Event::listen(Login::class, [LogAuthEvents::class, 'handleLogin']);
        Event::listen(Logout::class, [LogAuthEvents::class, 'handleLogout']);
        Event::listen(Failed::class, [LogAuthEvents::class, 'handleFailed']);
    }
}
