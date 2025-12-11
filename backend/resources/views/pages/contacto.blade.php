@php
    $locale = app('laravellocalization')->getCurrentLocale();
@endphp

<x-layouts.app>
    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Header --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('messages.contact.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('messages.contact.subtitle') }}</p>
        </div>
    </section>

    {{-- Contact Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
                {{-- Contact Form --}}
                <div>
                    <h2 class="mb-6 text-2xl font-bold">{{ __('messages.contact.form.title') }}</h2>
                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="mb-2 block text-sm font-medium">{{ __('messages.contact.form.name') }}</label>
                                <x-ui.input type="text" id="name" name="name" required />
                            </div>
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium">{{ __('messages.contact.form.email') }}</label>
                                <x-ui.input type="email" id="email" name="email" required />
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="mb-2 block text-sm font-medium">{{ __('messages.contact.form.subject') }}</label>
                            <x-ui.input type="text" id="subject" name="subject" required />
                        </div>
                        <div>
                            <label for="entity" class="mb-2 block text-sm font-medium">{{ __('messages.contact.form.entity') }}</label>
                            <select id="entity" name="entity" class="w-full rounded-lg border border-input bg-background px-3 py-2">
                                <option value="praia-norte">{{ __('messages.entities.praiaDoNorte') }}</option>
                                <option value="carsurf">{{ __('messages.entities.carsurf') }}</option>
                                <option value="nazare-qualifica">{{ __('messages.entities.nazareQualifica') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="mb-2 block text-sm font-medium">{{ __('messages.contact.form.message') }}</label>
                            <x-ui.textarea id="message" name="message" rows="5" required />
                        </div>
                        <x-ui.button type="submit" class="w-full">
                            {{ __('messages.contact.form.submit') }}
                        </x-ui.button>
                    </form>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h2 class="mb-6 text-2xl font-bold">{{ __('messages.contact.info.title') }}</h2>
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
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                    <a href="mailto:geral@praiadonortenazare.pt" class="hover:text-ocean">geral@praiadonortenazare.pt</a>
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
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                    <a href="mailto:geral@carsurf.pt" class="hover:text-performance">geral@carsurf.pt</a>
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
                                    <a href="tel:+351262000000" class="hover:text-institutional">+351 262 000 000</a>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                    <a href="mailto:geral@nazarequalifica.pt" class="hover:text-institutional">geral@nazarequalifica.pt</a>
                                </div>
                            </x-ui.card-content>
                        </x-ui.card>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Map Section --}}
    <section class="border-t">
        <div class="aspect-[21/9] w-full bg-muted">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3093.2067!2d-9.0709!3d39.6012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd18e8b5e8c1c8c1%3A0x5c1c8c1c8c1c8c1c!2sPraia%20do%20Norte!5e0!3m2!1sen!2spt!4v1234567890"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>
    </section>
</x-layouts.app>
