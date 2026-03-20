@php
    $locale = app('laravellocalization')->getCurrentLocale();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ __('messages.contact.title') }}" subtitle="{{ __('messages.contact.subtitle') }}" entity="praia-norte" />

    {{-- Contact Info --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <h2 class="mb-8 text-center text-2xl font-bold">{{ __('messages.contact.info.title') }}</h2>
                <div class="space-y-8">
                    {{-- Praia do Norte --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title class="text-ocean">{{ __('messages.entities.praiaDoNorte') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-3">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <span>{{ __('messages.contact.info.praiaDoNorte.address') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                <a href="tel:{{ __('messages.contact.info.praiaDoNorte.phone') }}" class="hover:text-ocean">{{ __('messages.contact.info.praiaDoNorte.phone') }}</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <a href="mailto:{{ __('messages.contact.info.praiaDoNorte.email') }}" class="hover:text-ocean">{{ __('messages.contact.info.praiaDoNorte.email') }}</a>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- Carsurf --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title class="text-performance">{{ __('messages.entities.carsurf') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-3">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <span>{{ __('messages.contact.info.carsurf.address') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                <a href="tel:{{ __('messages.contact.info.carsurf.phone') }}" class="hover:text-performance">{{ __('messages.contact.info.carsurf.phone') }}</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <a href="mailto:{{ __('messages.contact.info.carsurf.email') }}" class="hover:text-performance">{{ __('messages.contact.info.carsurf.email') }}</a>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- Nazare Qualifica --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title class="text-institutional">{{ __('messages.entities.nazareQualifica') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-3">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <span>{{ __('messages.contact.info.nazareQualifica.address') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                <a href="tel:{{ __('messages.contact.info.nazareQualifica.phone') }}" class="hover:text-institutional">{{ __('messages.contact.info.nazareQualifica.phone') }}</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                <a href="tel:+351{{ str_replace(' ', '', __('messages.contact.info.nazareQualifica.mobile')) }}" class="hover:text-institutional">{{ __('messages.contact.info.nazareQualifica.mobile') }}</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <a href="mailto:{{ __('messages.contact.info.nazareQualifica.email') }}" class="hover:text-institutional">{{ __('messages.contact.info.nazareQualifica.email') }}</a>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>
        </div>
    </section>

    {{-- Map Section --}}
    <section class="border-t">
        <div class="aspect-[21/9] w-full bg-muted">
            <iframe
                src="https://www.openstreetmap.org/export/embed.html?bbox=-9.0820%2C39.6105%2C-9.0760%2C39.6145&layer=mapnik&marker=39.6124%2C-9.0790"
                width="100%"
                height="100%"
                style="border:0;"
                loading="lazy"
            ></iframe>
        </div>
    </section>
</x-layouts.app>
