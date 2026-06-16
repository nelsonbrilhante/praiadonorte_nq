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
                'email' => $this->maskEmail($event->credentials['email'] ?? null),
            ])
            ->log('Tentativa de login falhada');
    }

    /**
     * Minimise PII in the audit log (GDPR): never store the raw submitted
     * value (users sometimes type their password into the email field). Keep
     * only the first character of the local part and the domain for forensic
     *
     * signal — e.g. "carlos@gmail.com" -> "c***@gmail.com".
     */
    private function maskEmail(?string $email): string
    {
        if (empty($email)) {
            return 'unknown';
        }

        if (! str_contains($email, '@')) {
            return '***';
        }

        [$local, $domain] = explode('@', $email, 2);

        return mb_substr($local, 0, 1).'***@'.$domain;
    }
}
