<x-layouts.app>
    @php
        // Helper function to get localized field
        $getLocalized = function($field, $locale) {
            if (is_array($field)) {
                return $field[$locale] ?? $field['pt'] ?? '';
            }
            return $field ?? '';
        };

        // Extract hero data from homepage if available
        $heroData = null;
        if ($homepage && isset($homepage->content[$locale]['hero'])) {
            $heroData = $homepage->content[$locale]['hero'];
        }
    @endphp

    {{-- Hero Section --}}
    @if($heroData)
        <x-praia-norte.hero-section
            :title="$heroData['title'] ?? null"
            :subtitle="$heroData['subtitle'] ?? null"
            :ctaText="$heroData['cta_text'] ?? null"
            :ctaUrl="$heroData['cta_url'] ?? null"
            :youtubeUrl="$homepage->video_url ?? null"
        />
    @else
        <x-praia-norte.hero-section />
    @endif

    {{-- News Section --}}
    <section class="py-16 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold">{{ __('messages.home.news.title') }}</h2>
                <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/noticias') }}">
                    {{ __('messages.home.news.viewAll') }}
                </x-ui.button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($noticias as $noticia)
                    <a href="{{ LaravelLocalization::localizeURL('/noticias/' . $noticia->slug) }}">
                        <x-ui.card class="overflow-hidden h-full hover:shadow-lg transition-shadow cursor-pointer" :noPadding="true">
                            <div class="relative h-48">
                                @if($noticia->cover_image)
                                    <img
                                        src="{{ asset('storage/' . $noticia->cover_image) }}"
                                        alt="{{ $getLocalized($noticia->title, $locale) }}"
                                        class="w-full h-full object-cover"
                                    />
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                                @endif
                            </div>
                            <x-ui.card-header>
                                <x-ui.card-title class="line-clamp-2">
                                    {{ $getLocalized($noticia->title, $locale) }}
                                </x-ui.card-title>
                                <x-ui.card-description class="line-clamp-2">
                                    {{ $getLocalized($noticia->excerpt, $locale) }}
                                </x-ui.card-description>
                            </x-ui.card-header>
                        </x-ui.card>
                    </a>
                @empty
                    @for($i = 1; $i <= 3; $i++)
                        <x-ui.card class="overflow-hidden" :noPadding="true">
                            <div class="h-48 bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                            <x-ui.card-header>
                                <x-ui.card-title class="line-clamp-2">Título da notícia {{ $i }}</x-ui.card-title>
                                <x-ui.card-description>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                </x-ui.card-description>
                            </x-ui.card-header>
                        </x-ui.card>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- Surfer Wall Section --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold">{{ __('messages.home.surfers.title') }}</h2>
                    <p class="text-muted-foreground">{{ __('messages.home.surfers.subtitle') }}</p>
                </div>
                <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/surfer-wall') }}">
                    {{ __('messages.home.surfers.viewAll') }}
                </x-ui.button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($surfers as $surfer)
                    <a href="{{ LaravelLocalization::localizeURL('/surfer-wall/' . $surfer->slug) }}">
                        <x-ui.card class="overflow-hidden hover:shadow-lg transition-shadow cursor-pointer group" :noPadding="true">
                            <div class="relative aspect-square">
                                @if($surfer->photo)
                                    <img
                                        src="{{ asset('storage/' . $surfer->photo) }}"
                                        alt="{{ $surfer->name }}"
                                        class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                    />
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                                @endif
                            </div>
                            <x-ui.card-content class="p-4">
                                <p class="font-semibold">{{ $surfer->name }}</p>
                                <p class="text-sm text-muted-foreground">{{ $surfer->nationality }}</p>
                            </x-ui.card-content>
                        </x-ui.card>
                    </a>
                @empty
                    @for($i = 1; $i <= 4; $i++)
                        <x-ui.card class="overflow-hidden" :noPadding="true">
                            <div class="aspect-square bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                            <x-ui.card-content class="p-4">
                                <p class="font-semibold">Surfer {{ $i }}</p>
                                <p class="text-sm text-muted-foreground">Portugal</p>
                            </x-ui.card-content>
                        </x-ui.card>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- Entities Section --}}
    <section class="py-16 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Praia do Norte --}}
                <x-ui.card class="border-t-4 border-t-ocean">
                    <x-ui.card-header>
                        <x-ui.card-title class="text-ocean">{{ __('messages.entities.praiaDoNorte') }}</x-ui.card-title>
                        <x-ui.card-description>
                            {{ $locale === 'pt' ? 'O lar das ondas gigantes mais famosas do mundo' : 'Home to the world\'s most famous giant waves' }}
                        </x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/sobre') }}" class="w-full">
                            {{ __('messages.common.learnMore') }}
                        </x-ui.button>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Carsurf --}}
                <x-ui.card class="border-t-4 border-t-performance">
                    <x-ui.card-header>
                        <x-ui.card-title class="text-performance">{{ __('messages.entities.carsurf') }}</x-ui.card-title>
                        <x-ui.card-description>
                            {{ $locale === 'pt' ? 'Centro de alto rendimento para atletas de surf' : 'High-performance center for surf athletes' }}
                        </x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/carsurf') }}" class="w-full">
                            {{ __('messages.common.learnMore') }}
                        </x-ui.button>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Nazaré Qualifica --}}
                <x-ui.card class="border-t-4 border-t-institutional">
                    <x-ui.card-header>
                        <x-ui.card-title class="text-institutional">{{ __('messages.entities.nazareQualifica') }}</x-ui.card-title>
                        <x-ui.card-description>
                            {{ $locale === 'pt' ? 'Empresa municipal gestora das infraestruturas' : 'Municipal company managing infrastructure' }}
                        </x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}" class="w-full">
                            {{ __('messages.common.learnMore') }}
                        </x-ui.button>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    {{-- Events Section --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold">{{ __('messages.home.events.title') }}</h2>
                <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/eventos') }}">
                    {{ __('messages.home.events.viewAll') }}
                </x-ui.button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($eventos as $evento)
                    @php
                        $startDate = $evento->start_date;
                        $day = $startDate->format('d');
                        $month = $startDate->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->shortMonthName;
                    @endphp
                    <a href="{{ LaravelLocalization::localizeURL('/eventos/' . $evento->slug) }}">
                        <x-ui.card class="flex overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" :noPadding="true">
                            <div class="w-32 bg-gradient-to-br from-ocean/20 to-ocean/5 flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-2xl font-bold">{{ $day }}</p>
                                    <p class="text-sm capitalize">{{ $month }}</p>
                                </div>
                            </div>
                            <div class="flex-1">
                                <x-ui.card-header>
                                    <x-ui.card-title class="text-lg">
                                        {{ $getLocalized($evento->title, $locale) }}
                                    </x-ui.card-title>
                                    <x-ui.card-description>
                                        {{ $evento->location }}
                                    </x-ui.card-description>
                                </x-ui.card-header>
                            </div>
                        </x-ui.card>
                    </a>
                @empty
                    @for($i = 1; $i <= 2; $i++)
                        <x-ui.card class="flex overflow-hidden" :noPadding="true">
                            <div class="w-32 bg-gradient-to-br from-ocean/20 to-ocean/5 flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-2xl font-bold">15</p>
                                    <p class="text-sm">Jan</p>
                                </div>
                            </div>
                            <div class="flex-1">
                                <x-ui.card-header>
                                    <x-ui.card-title class="text-lg">Evento {{ $i }}</x-ui.card-title>
                                    <x-ui.card-description>
                                        Praia do Norte, Nazaré
                                    </x-ui.card-description>
                                </x-ui.card-header>
                            </div>
                        </x-ui.card>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
