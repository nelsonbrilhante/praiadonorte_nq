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

        // Entity colors and translation keys
        $entityConfig = [
            'praia-norte' => ['color' => 'ocean', 'key' => 'praiaDoNorte'],
            'carsurf' => ['color' => 'performance', 'key' => 'carsurf'],
            'nazare-qualifica' => ['color' => 'institutional', 'key' => 'nazareQualifica'],
        ];
        $entityColor = $entityConfig[$noticia->entity]['color'] ?? 'ocean';
        $entityKey = $entityConfig[$noticia->entity]['key'] ?? 'praiaDoNorte';
    @endphp

    @push('head')
        <title>{{ $title }} | {{ __('messages.metadata.title') }}</title>
        <meta name="description" content="{{ $excerpt }}">
    @endpush

    <article class="py-8">
        <div class="container mx-auto px-4">
            {{-- Breadcrumbs --}}
            <x-ui.breadcrumbs class="mb-6" />

            {{-- Header --}}
            <header class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <x-ui.badge variant="{{ $entityColor }}">
                        {{ __('messages.entities.' . $entityKey) }}
                    </x-ui.badge>
                    @if($noticia->featured)
                        <x-ui.badge variant="secondary">
                            {{ __('messages.news.featured') }}
                        </x-ui.badge>
                    @endif
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $title }}</h1>

                <div class="flex flex-wrap items-center gap-4 text-muted-foreground">
                    @if($noticia->author)
                        <span>{{ __('messages.news.by', ['author' => $noticia->author]) }}</span>
                    @endif
                    <span>{{ __('messages.news.publishedAt', ['date' => $noticia->published_at->format('d/m/Y')]) }}</span>
                </div>
            </header>

            {{-- Cover Image --}}
            @if($noticia->cover_image)
                <div class="relative aspect-video mb-8 rounded-lg overflow-hidden">
                    <img
                        src="{{ asset('storage/' . $noticia->cover_image) }}"
                        alt="{{ $title }}"
                        class="w-full h-full object-cover"
                    />
                </div>
            @endif

            {{-- Content --}}
            <div class="max-w-3xl mx-auto">
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
    </article>

    {{-- Related News --}}
    @if($related->count() > 0)
        <section class="py-16 bg-muted/30">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl font-bold mb-8">{{ __('messages.news.relatedNews') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related as $item)
                        <a href="{{ LaravelLocalization::localizeURL('/noticias/' . $item->slug) }}">
                            <x-ui.card class="overflow-hidden h-full hover:shadow-lg transition-shadow cursor-pointer" :noPadding="true">
                                <div class="relative h-48">
                                    @if($item->cover_image)
                                        <img
                                            src="{{ asset('storage/' . $item->cover_image) }}"
                                            alt="{{ $getLocalized($item->title, $locale) }}"
                                            class="w-full h-full object-cover"
                                        />
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                                    @endif
                                </div>
                                <x-ui.card-header>
                                    <x-ui.card-title class="line-clamp-2">
                                        {{ $getLocalized($item->title, $locale) }}
                                    </x-ui.card-title>
                                    <x-ui.card-description class="line-clamp-2">
                                        {{ $getLocalized($item->excerpt, $locale) }}
                                    </x-ui.card-description>
                                </x-ui.card-header>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
