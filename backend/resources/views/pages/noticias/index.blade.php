<x-layouts.app>
    @php
        // Helper function to get localized field
        $getLocalized = function($field, $locale) {
            if (is_array($field)) {
                return $field[$locale] ?? $field['pt'] ?? '';
            }
            return $field ?? '';
        };

        $entityColors = [
            'praia-norte' => 'bg-ocean text-white',
            'carsurf' => 'bg-performance text-white',
            'nazare-qualifica' => 'bg-institutional text-white',
        ];

        $entityLabels = [
            'praia-norte' => __('messages.entities.praiaDoNorte'),
            'carsurf' => __('messages.entities.carsurf'),
            'nazare-qualifica' => __('messages.entities.nazareQualifica'),
        ];
    @endphp

    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Header --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('messages.news.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('messages.news.subtitle') }}</p>
        </div>
    </section>

    {{-- Filters --}}
    <section class="border-b bg-muted/30 py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ LaravelLocalization::localizeURL('/noticias') }}"
                    class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ !request('entity') ? 'bg-ocean text-white' : 'bg-muted hover:bg-muted/80' }}"
                >
                    {{ __('messages.news.categories.all') }}
                </a>
                @foreach(['praia-norte', 'carsurf', 'nazare-qualifica'] as $entity)
                    <a
                        href="{{ LaravelLocalization::localizeURL('/noticias') }}?entity={{ $entity }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ request('entity') === $entity ? $entityColors[$entity] : 'bg-muted hover:bg-muted/80' }}"
                    >
                        {{ $entityLabels[$entity] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- News Grid --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            @if($noticias->count() > 0)
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($noticias as $noticia)
                        <a href="{{ LaravelLocalization::localizeURL('/noticias/' . $noticia->slug) }}">
                            <x-ui.card class="h-full cursor-pointer overflow-hidden transition-shadow hover:shadow-lg" :noPadding="true">
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
                                    <div class="mb-2 flex items-center gap-2">
                                        <x-ui.badge class="{{ $entityColors[$noticia->entity] ?? 'bg-muted' }}">
                                            {{ $entityLabels[$noticia->entity] ?? $noticia->entity }}
                                        </x-ui.badge>
                                        @if($noticia->featured)
                                            <x-ui.badge variant="secondary">{{ __('messages.news.featured') }}</x-ui.badge>
                                        @endif
                                    </div>
                                    <x-ui.card-title class="line-clamp-2">
                                        {{ $getLocalized($noticia->title, $locale) }}
                                    </x-ui.card-title>
                                    <x-ui.card-description class="line-clamp-2">
                                        {{ $getLocalized($noticia->excerpt, $locale) }}
                                    </x-ui.card-description>
                                </x-ui.card-header>
                                <x-ui.card-content>
                                    <p class="text-sm text-muted-foreground">
                                        {{ $noticia->published_at->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                                    </p>
                                </x-ui.card-content>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center">
                    <p class="text-lg text-muted-foreground">{{ __('messages.news.noNews') }}</p>
                </div>
            @endif

            {{-- Pagination --}}
            @if($noticias->lastPage() > 1)
                <div class="mt-8 flex justify-center gap-2">
                    {{ $noticias->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
