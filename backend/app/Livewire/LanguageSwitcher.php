<?php

namespace App\Livewire;

use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LanguageSwitcher extends Component
{
    public string $currentLocale;
    public array $locales = [];

    public function mount(): void
    {
        $this->currentLocale = LaravelLocalization::getCurrentLocale();
        $this->locales = LaravelLocalization::getSupportedLocales();
    }

    public function switchLocale(string $locale): void
    {
        if (array_key_exists($locale, $this->locales)) {
            // Get the current path and replace the locale prefix
            $currentPath = request()->path();

            // Remove current locale prefix and add new one
            $pathWithoutLocale = preg_replace('/^(' . $this->currentLocale . ')\//', '', $currentPath);
            $pathWithoutLocale = preg_replace('/^(' . $this->currentLocale . ')$/', '', $pathWithoutLocale);

            // Build new URL with target locale
            $newUrl = '/' . $locale . ($pathWithoutLocale ? '/' . $pathWithoutLocale : '');

            $this->redirect($newUrl, navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
