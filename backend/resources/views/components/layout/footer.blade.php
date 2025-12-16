@php
    $locale = LaravelLocalization::getCurrentLocale();
    $currentYear = date('Y');
@endphp

<footer class="border-t bg-muted/50">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
            {{-- Praia do Norte --}}
            <div>
                <a href="{{ LaravelLocalization::localizeURL('/sobre') }}" class="mb-4 block">
                    <img
                        src="{{ asset('images/logos/LOGOTIPO PN.png') }}"
                        alt="Praia do Norte"
                        class="h-10 w-auto"
                    />
                </a>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/sobre') }}" class="hover:text-ocean transition-colors">
                            {{ __('messages.navigation.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/noticias') }}" class="hover:text-ocean transition-colors">
                            {{ __('messages.navigation.news') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/surfer-wall') }}" class="hover:text-ocean transition-colors">
                            {{ __('messages.navigation.surferWall') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/eventos') }}" class="hover:text-ocean transition-colors">
                            {{ __('messages.navigation.events') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Carsurf --}}
            <div>
                <a href="{{ LaravelLocalization::localizeURL('/carsurf') }}" class="mb-4 block">
                    <img
                        src="{{ asset('images/logos/CARSURF_001.png') }}"
                        alt="Carsurf"
                        class="h-10 w-auto"
                    />
                </a>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/carsurf') }}" class="hover:text-performance transition-colors">
                            {{ __('messages.navigation.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/carsurf/programas') }}" class="hover:text-performance transition-colors">
                            {{ __('messages.pages.programs') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Nazaré Qualifica --}}
            <div>
                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}" class="mb-4 block">
                    <img
                        src="{{ asset('images/logos/imagem-grafica-nq-original-name.svg') }}"
                        alt="Nazaré Qualifica"
                        class="h-10 w-auto"
                    />
                </a>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}" class="hover:text-institutional transition-colors">
                            {{ __('messages.navigation.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/equipa') }}" class="hover:text-institutional transition-colors">
                            {{ __('messages.nq.team.title') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/servicos') }}" class="hover:text-institutional transition-colors">
                            {{ __('messages.pages.services') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/forte') }}" class="hover:text-institutional transition-colors">
                            {{ __('messages.nq.services.forte.title') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="mb-4 text-lg font-semibold">{{ __('messages.navigation.contact') }}</h3>
                <address class="not-italic text-sm text-muted-foreground space-y-2">
                    <p>Nazaré Qualifica, EM</p>
                    <p>{{ __('messages.nq.contact.address') }}</p>
                    <p>{{ __('messages.nq.contact.postalCode') }}</p>
                    <p>
                        <a href="tel:{{ __('messages.nq.contact.phone') }}" class="hover:text-ocean transition-colors">
                            {{ __('messages.nq.contact.phone') }}
                        </a>
                    </p>
                    <p>
                        <a href="mailto:{{ __('messages.nq.contact.email') }}" class="hover:text-ocean transition-colors">
                            {{ __('messages.nq.contact.email') }}
                        </a>
                    </p>
                </address>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t pt-8 md:flex-row">
            <p class="text-sm text-muted-foreground">
                {{ __('messages.footer.copyright', ['year' => $currentYear]) }}
            </p>
            <div class="flex gap-4 text-sm text-muted-foreground">
                <a href="{{ LaravelLocalization::localizeURL('/privacidade') }}" class="hover:text-foreground transition-colors">
                    {{ __('messages.footer.links.privacy') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/termos') }}" class="hover:text-foreground transition-colors">
                    {{ __('messages.footer.links.terms') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/cookies') }}" class="hover:text-foreground transition-colors">
                    {{ __('messages.footer.links.cookies') }}
                </a>
            </div>
        </div>
    </div>
</footer>
