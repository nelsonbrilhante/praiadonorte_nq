<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LogAuthEvents
{
    public function handleLogin(Login $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->event('login')
            ->log('Login efetuado');
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            activity('auth')
                ->causedBy($event->user)
                ->event('logout')
                ->log('Logout efetuado');
        }
    }

    public function handleFailed(Failed $event): void
    {
        activity('auth')
            ->event('login_failed')
            ->withProperties([
                'email' => $event->credentials['email'] ?? 'unknown',
            ])
            ->log('Tentativa de login falhada');
    }
}
