@php
    $locale = app('laravellocalization')->getCurrentLocale();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero
        title="{{ __('messages.carsurf.reservas.title') }}"
        subtitle="{{ __('messages.carsurf.reservas.subtitle') }}"
        entity="carsurf"
    />

    {{-- Reservation Form --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-5">

                    {{-- Form --}}
                    <div class="lg:col-span-3 reveal-up" x-data x-intersect.once="$el.classList.add('is-visible')">
                        <h2 class="mb-2 text-2xl font-bold">{{ __('messages.carsurf.reservas.form.title') }}</h2>
                        <p class="mb-6 text-muted-foreground">{{ __('messages.carsurf.reservas.form.description') }}</p>

                        @if(session('success'))
                            <div class="mb-6 rounded-lg border border-performance/30 bg-performance/10 p-4 text-performance-dark dark:text-performance">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                    <span class="font-medium">{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 rounded-lg border border-destructive/30 bg-destructive/10 p-4 text-destructive">
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                        @endif

                        <form action="{{ LaravelLocalization::localizeURL('/carsurf/reservas') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="name" class="mb-2 block text-sm font-medium">{{ __('messages.carsurf.reservas.form.name') }} <span class="text-destructive">*</span></label>
                                <x-ui.input type="text" id="name" name="name" :value="old('name')" required />
                                @error('name')
                                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-2 block text-sm font-medium">{{ __('messages.carsurf.reservas.form.email') }} <span class="text-destructive">*</span></label>
                                    <x-ui.input type="email" id="email" name="email" :value="old('email')" required />
                                    @error('email')
                                        <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="mb-2 block text-sm font-medium">{{ __('messages.carsurf.reservas.form.phone') }}</label>
                                    <x-ui.input type="tel" id="phone" name="phone" :value="old('phone')" />
                                    @error('phone')
                                        <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="message" class="mb-2 block text-sm font-medium">{{ __('messages.carsurf.reservas.form.message') }} <span class="text-destructive">*</span></label>
                                <x-ui.textarea id="message" name="message" rows="5" required>{{ old('message') }}</x-ui.textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <x-ui.button type="submit" class="w-full bg-performance text-white hover:bg-performance/90">
                                {{ __('messages.carsurf.reservas.form.submit') }}
                            </x-ui.button>
                        </form>
                    </div>

                    {{-- Contact Info Sidebar --}}
                    <div class="lg:col-span-2 reveal-up" x-data x-intersect.once="$el.classList.add('is-visible')">
                        <h2 class="mb-6 text-2xl font-bold">{{ __('messages.carsurf.reservas.info.title') }}</h2>

                        <x-ui.card class="border-performance/20">
                            <x-ui.card-content class="space-y-4 pt-6">
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">{{ __('messages.carsurf.reservas.info.email') }}</p>
                                        <a href="mailto:geral@carsurf.nazare.pt" class="font-medium text-performance hover:underline">geral@carsurf.nazare.pt</a>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">{{ __('messages.carsurf.reservas.info.phone') }}</p>
                                        <a href="tel:+351938013603" class="font-medium text-performance hover:underline">+351 938 013 603</a>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">{{ __('messages.carsurf.reservas.info.address') }}</p>
                                        <p>Centro de Alto Rendimento de Surf, 2450-504 Nazaré</p>
                                    </div>
                                </div>
                            </x-ui.card-content>
                        </x-ui.card>

                        <div class="mt-6 rounded-lg bg-performance/5 p-4">
                            <p class="text-sm text-muted-foreground">
                                {{ __('messages.carsurf.reservas.info.note') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
