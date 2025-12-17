<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    /**
     * Disable the default brand logo on the login page
     * We use a custom vertical logo via render hook instead
     */
    public function hasLogo(): bool
    {
        return false;
    }

    /**
     * Custom heading for the login page
     */
    public function getHeading(): string | Htmlable | null
    {
        return 'Painel de Administração';
    }

    /**
     * Custom subheading for the login page
     */
    public function getSubheading(): string | Htmlable | null
    {
        return 'Introduza as suas credenciais para aceder';
    }
}
