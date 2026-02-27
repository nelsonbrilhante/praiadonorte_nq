@php
    $locale = LaravelLocalization::getCurrentLocale();
    $currentYear = date('Y');
@endphp

<footer class="bg-[#0b1022] text-white">
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col gap-8 md:flex-row md:justify-between">
            {{-- Column 1: Site pages --}}
            <nav class="grid grid-cols-2 gap-x-12 gap-y-3 text-sm">
                <a href="{{ LaravelLocalization::localizeURL('/') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.navigation.home') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/noticias') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.navigation.news') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/eventos') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.navigation.events') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/surfer-wall') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.navigation.surferWall') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/previsoes') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.navigation.forecast') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/contacto') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.navigation.contact') }}
                </a>
            </nav>

            {{-- Column 2: Legal --}}
            <nav class="grid grid-cols-1 gap-y-3 text-sm">
                <a href="{{ LaravelLocalization::localizeURL('/privacidade') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.footer.links.privacy') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/termos') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.footer.links.terms') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/cookies') }}" class="text-white/60 transition-colors hover:text-white">
                    {{ __('messages.footer.links.cookies') }}
                </a>
            </nav>
        </div>

        {{-- Copyright --}}
        <div class="mt-8 border-t border-white/10 pt-6">
            <p class="text-sm text-white/40">
                {{ __('messages.footer.copyright', ['year' => $currentYear]) }}
            </p>
        </div>
    </div>
</footer>
