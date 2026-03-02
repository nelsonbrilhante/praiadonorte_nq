<x-layouts.app>
    @php
        // Helper function to get localized field
        $getLocalized = function($field, $locale) {
            if (is_array($field)) {
                return $field[$locale] ?? $field['pt'] ?? '';
            }
            return $field ?? '';
        };

        $title = $getLocalized($noticia->title, $locale);
        $content = $getLocalized($noticia->content, $locale);
        $excerpt = $getLocalized($noticia->excerpt, $locale);

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

        $entityTextColors = [
            'praia-norte' => 'text-ocean',
            'carsurf' => 'text-performance',
            'nazare-qualifica' => 'text-institutional',
        ];

        $entityGradients = [
            'praia-norte' => 'gradient-ocean-deep',
            'carsurf' => 'gradient-performance',
            'nazare-qualifica' => 'gradient-institutional',
        ];

        $entityColor = $entityColors[$noticia->entity] ?? 'bg-muted';
        $entityLabel = $entityLabels[$noticia->entity] ?? $noticia->entity;
    @endphp

    @push('head')
        <title>{{ $title }} | {{ __('messages.metadata.title') }}</title>
        <meta name="description" content="{{ $excerpt }}">
    @endpush

    {{-- Full-bleed Hero --}}
    <section class="relative h-[50vh] min-h-[400px] overflow-hidden">
        @if($noticia->cover_image)
            <img src="{{ asset('storage/' . $noticia->cover_image) }}"
                 class="absolute inset-0 h-full w-full object-cover"
                 alt="{{ $title }}" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        @else
            <div class="absolute inset-0 {{ $entityGradients[$noticia->entity] ?? 'gradient-ocean-deep' }}"></div>
        @endif

        <div class="container relative mx-auto flex h-full flex-col justify-end px-4 pb-10">
            {{-- Entity + Featured + Category badges --}}
            <div class="mb-4 flex flex-wrap items-center gap-2">
                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $entityColor }}">
                    {{ $entityLabel }}
                </span>
                @if($noticia->featured)
                    <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
                        {{ __('messages.common.featured') }}
                    </span>
                @endif
                @if($noticia->category)
                    <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
                        {{ $noticia->category }}
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="mb-4 text-3xl font-bold text-white md:text-4xl lg:text-5xl">{{ $title }}</h1>

            {{-- Author + Date inline --}}
            <div class="flex flex-wrap items-center gap-4 text-white/80">
                @if($noticia->author)
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        {{ $noticia->author }}
                    </span>
                @endif
                <span class="flex items-center gap-2">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                    </svg>
                    {{ $noticia->published_at->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                </span>
            </div>
        </div>
    </section>

    {{-- Info Bar --}}
    <section class="border-b bg-muted/10 py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div>
                    <span class="text-muted-foreground">{{ __('messages.events.date') }}:</span>
                    <span class="font-medium">{{ $noticia->published_at->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                </div>
                @if($noticia->author)
                    <div>
                        <span class="text-muted-foreground">{{ __('messages.news.by', ['author' => '']) }}</span>
                        <span class="font-medium">{{ $noticia->author }}</span>
                    </div>
                @endif
                @if($noticia->category)
                    <div>
                        <span class="text-muted-foreground">{{ __('messages.events.category') }}:</span>
                        <span class="font-medium">{{ $noticia->category }}</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl reveal-up" x-data x-intersect.once="$el.classList.add('is-visible')">
                <div class="prose prose-lg max-w-none">
                    {!! $content !!}
                </div>

                {{-- Tags --}}
                @if($noticia->tags && count($noticia->tags) > 0)
                    <div class="flex flex-wrap gap-2 mt-8 pt-8 border-t">
                        @foreach($noticia->tags as $tag)
                            <x-ui.badge variant="outline">{{ $tag }}</x-ui.badge>
                        @endforeach
                    </div>
                @endif

                {{-- Back Link --}}
                <div class="mt-8">
                    <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/noticias') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                        {{ __('messages.news.backToList') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>

    {{-- Related News --}}
    @if($related->count() > 0)
        <section class="border-t bg-muted/10 py-12">
            <div class="container mx-auto px-4">
                <h2 class="mb-6 text-2xl font-bold">{{ __('messages.news.relatedNews') }}</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    @foreach($related as $item)
                        <a href="{{ LaravelLocalization::localizeURL('/noticias/' . $item->slug) }}"
                           class="group overflow-hidden rounded-xl border bg-card transition-colors hover:bg-accent/30">
                            @if($item->cover_image)
                                <div class="relative aspect-[16/9] overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->cover_image) }}"
                                         alt="{{ $getLocalized($item->title, $locale) }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         loading="lazy" />
                                </div>
                            @endif
                            <div class="p-4">
                                <span class="text-xs font-semibold uppercase tracking-wider {{ $entityTextColors[$item->entity] ?? 'text-ocean' }}">
                                    {{ $entityLabels[$item->entity] ?? $item->entity }}
                                </span>
                                <p class="font-medium group-hover:text-ocean line-clamp-2">{{ $getLocalized($item->title, $locale) }}</p>
                                <p class="mt-1 text-sm text-muted-foreground">
                                    {{ $item->published_at->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                                </p>
                                @php $itemExcerpt = $getLocalized($item->excerpt, $locale); @endphp
                                @if($itemExcerpt)
                                    <p class="mt-1 text-sm text-muted-foreground line-clamp-2">{{ $itemExcerpt }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
