@php
    $locale = LaravelLocalization::getCurrentLocale();
    $currentYear = date('Y');
@endphp

<footer class="bg-[#0b1022] text-white">
    <div class="container mx-auto px-4 py-12">
        {{-- 4-column grid: stack mobile, 2x2 tablet, 4-col desktop --}}
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Column 1: Praia do Norte --}}
            <nav aria-label="{{ __('messages.entities.praiaDoNorte') }}">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-white">
                    {{ __('messages.entities.praiaDoNorte') }}
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/praia-norte/sobre') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.navigation.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/praia-norte/forte') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.breadcrumbs.forte') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/praia-norte/hidrografico') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.breadcrumbs.hidrografico') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.navigation.surferWall') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/praia-norte/previsoes') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.navigation.forecast') }}
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- Column 2: Carsurf --}}
            <nav aria-label="{{ __('messages.entities.carsurf') }}">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-white">
                    {{ __('messages.entities.carsurf') }}
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/carsurf/sobre') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.navigation.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/carsurf/instalacoes') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.pages.facilities') }}
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- Column 3: Nazaré Qualifica --}}
            <nav aria-label="{{ __('messages.entities.nazareQualifica') }}">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-white">
                    {{ __('messages.entities.nazareQualifica') }}
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.navigation.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/equipa') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.breadcrumbs.equipa') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/servicos') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.breadcrumbs.servicos') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/documentos') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.nq.documentos.nav') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/contraordenacoes') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.breadcrumbs.contraordenacoes') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/estacionamento') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.breadcrumbs.estacionamento') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/ale') }}" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.breadcrumbs.ale') }}
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- Column 4: Contacto & Institucional --}}
            <div>
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-white">
                    {{ __('messages.footer.contact.title') }}
                </h3>

                {{-- Address --}}
                <p class="text-sm leading-relaxed text-white/60">
                    {{ __('messages.footer.contact.address') }}
                </p>

                {{-- Phones --}}
                <p class="mt-2 text-sm">
                    <a href="tel:+351262550010" class="text-white/60 transition-colors hover:text-white">
                        {{ __('messages.footer.contact.landline') }}
                    </a>
                </p>
                <p class="mt-1 text-sm">
                    <a href="tel:+351934000126" class="text-white/60 transition-colors hover:text-white">
                        {{ __('messages.footer.contact.phone') }}
                    </a>
                </p>

                {{-- Contact page link --}}
                <p class="mt-2 text-sm">
                    <a href="{{ LaravelLocalization::localizeURL('/contacto') }}" class="text-white/60 transition-colors hover:text-white">
                        {{ __('messages.navigation.contact') }}
                    </a>
                </p>

                {{-- Institutional links --}}
                <ul class="mt-4 space-y-2 text-sm">
                    <li>
                        <a href="https://www.livroreclamacoes.pt/Inicio/" target="_blank" rel="noopener noreferrer" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.footer.institutional.complaintsBook') }}
                            <span class="text-[10px]" aria-hidden="true">&#8599;</span>
                            <span class="sr-only">({{ $locale === 'pt' ? 'abre numa nova janela' : 'opens in a new window' }})</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://nazarequalifica.portaldedenuncias.pt/" target="_blank" rel="noopener noreferrer" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.footer.institutional.whistleblower') }}
                            <span class="text-[10px]" aria-hidden="true">&#8599;</span>
                            <span class="sr-only">({{ $locale === 'pt' ? 'abre numa nova janela' : 'opens in a new window' }})</span>
                        </a>
                    </li>
                </ul>

                {{-- Partners --}}
                <h4 class="mt-6 mb-2 text-xs font-semibold uppercase tracking-wider text-white/40">
                    {{ __('messages.footer.partners.title') }}
                </h4>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="https://www.cm-nazare.pt/" target="_blank" rel="noopener noreferrer" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.footer.partners.cmNazare') }}
                            <span class="text-[10px]" aria-hidden="true">&#8599;</span>
                            <span class="sr-only">({{ $locale === 'pt' ? 'abre numa nova janela' : 'opens in a new window' }})</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.sm-nazare.pt/" target="_blank" rel="noopener noreferrer" class="text-white/60 transition-colors hover:text-white">
                            {{ __('messages.footer.partners.smNazare') }}
                            <span class="text-[10px]" aria-hidden="true">&#8599;</span>
                            <span class="sr-only">({{ $locale === 'pt' ? 'abre numa nova janela' : 'opens in a new window' }})</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- NQ Logo --}}
        <div class="mt-10">
            <a href="{{ LaravelLocalization::localizeURL('/') }}">
                <img src="{{ asset('images/logos/nq-horizontal-white.svg') }}"
                     alt="Nazaré Qualifica"
                     class="h-12 opacity-60" />
            </a>
        </div>

        {{-- Copyright bar with legal links --}}
        <div class="mt-6 flex flex-col items-center gap-3 border-t border-white/10 pt-6 text-sm text-white/40 sm:flex-row sm:justify-between">
            <p>{{ __('messages.footer.copyright', ['year' => $currentYear]) }}</p>
            <nav class="flex gap-4" aria-label="{{ $locale === 'pt' ? 'Links legais' : 'Legal links' }}">
                <a href="{{ LaravelLocalization::localizeURL('/privacidade') }}" class="transition-colors hover:text-white/70">
                    {{ __('messages.footer.links.privacy') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/termos') }}" class="transition-colors hover:text-white/70">
                    {{ __('messages.footer.links.terms') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/cookies') }}" class="transition-colors hover:text-white/70">
                    {{ __('messages.footer.links.cookies') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/termos') }}#litigios" class="transition-colors hover:text-white/70">
                    {{ __('messages.footer.links.disputes') }}
                </a>
            </nav>
        </div>
    </div>
</footer>
