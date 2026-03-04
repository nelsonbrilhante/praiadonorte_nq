<x-layouts.app>
    @php
        $lojaRoute = $locale === 'pt' ? '/loja' : '/shop';
    @endphp

    @push('head')
        <title>{{ __('messages.shop.title') }} | {{ __('messages.metadata.title') }}</title>
        <meta name="description" content="{{ __('messages.shop.subtitle') }}">
    @endpush

    {{-- Breadcrumbs --}}
    <x-ui.breadcrumbs :items="[
        ['label' => __('messages.breadcrumbs.home'), 'href' => LaravelLocalization::localizeURL('/')],
        ['label' => __('messages.shop.title'), 'current' => true],
    ]" />

    {{-- Page Header --}}
    <section class="py-8 border-b border-border">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold md:text-4xl">{{ __('messages.shop.title') }}</h1>
            <p class="mt-1 text-muted-foreground">{{ __('messages.shop.subtitle') }}</p>
        </div>
    </section>

    {{-- Category Filters --}}
    @if(count($categories) > 0)
    <section class="sticky top-16 z-30 border-b bg-background/95 backdrop-blur-sm py-3">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center gap-2">
                <a
                    href="{{ LaravelLocalization::localizeURL($lojaRoute) }}"
                    class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ !$currentCategory ? 'bg-ocean text-white' : 'bg-muted hover:bg-muted/80' }}"
                >
                    {{ __('messages.common.all') }}
                </a>
                @foreach($categories as $category)
                    <a
                        href="{{ LaravelLocalization::localizeURL($lojaRoute) }}?category={{ $category['slug'] }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ $currentCategory === $category['slug'] ? 'bg-ocean text-white' : 'bg-muted hover:bg-muted/80' }}"
                    >
                        {{ $category['name'] }}
                        <span class="ml-1 text-xs opacity-70">({{ $category['count'] }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Products Grid --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            @if(count($products) > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <a href="{{ LaravelLocalization::localizeURL($lojaRoute . '/' . $product['slug']) }}"
                           class="group overflow-hidden rounded-xl border bg-card transition-colors hover:bg-accent/30">
                            {{-- Product Image --}}
                            <div class="relative aspect-square overflow-hidden bg-muted">
                                @if($product['featured_image'])
                                    <img
                                        src="{{ $product['featured_image']['src'] }}"
                                        alt="{{ $product['featured_image']['alt'] ?: $product['name'] }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="flex h-full w-full items-center justify-center">
                                        <svg class="h-16 w-16 text-muted-foreground/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Sale Badge --}}
                                @if($product['on_sale'])
                                    <div class="absolute left-3 top-3">
                                        <x-ui.badge variant="destructive">{{ __('messages.shop.sale') }}</x-ui.badge>
                                    </div>
                                @endif

                                {{-- Stock Status --}}
                                @if($product['stock_status'] !== 'instock')
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40">
                                        <span class="rounded-full bg-black/70 px-4 py-2 text-sm font-medium text-white">
                                            {{ __('messages.shop.outOfStock') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="p-4">
                                {{-- Categories --}}
                                @if(count($product['categories']) > 0)
                                    <div class="mb-1 flex flex-wrap gap-1">
                                        @foreach(array_slice($product['categories'], 0, 2) as $cat)
                                            <span class="text-xs font-medium uppercase tracking-wider text-ocean">{{ $cat['name'] }}</span>
                                            @if(!$loop->last)
                                                <span class="text-xs text-muted-foreground">&middot;</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <h3 class="text-sm font-semibold line-clamp-2 group-hover:text-ocean transition-colors">
                                    {{ $product['name'] }}
                                </h3>

                                {{-- Price --}}
                                <div class="mt-2 flex items-baseline gap-2">
                                    @if($product['price'])
                                        <span class="text-lg font-bold">{{ number_format((float) $product['price'], 2, ',', '.') }} &euro;</span>
                                        @if($product['on_sale'] && $product['regular_price'])
                                            <span class="text-sm text-muted-foreground line-through">
                                                {{ number_format((float) $product['regular_price'], 2, ',', '.') }} &euro;
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                {{-- VAT note --}}
                                <p class="mt-1 text-xs text-muted-foreground">{{ __('messages.shop.vatIncluded') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($totalPages > 1)
                    <div class="mt-10 flex items-center justify-center gap-2">
                        @if($currentPage > 1)
                            <x-ui.button
                                variant="outline"
                                size="sm"
                                href="{{ LaravelLocalization::localizeURL($lojaRoute) }}?page={{ $currentPage - 1 }}{{ $currentCategory ? '&category=' . $currentCategory : '' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            </x-ui.button>
                        @endif

                        <span class="px-3 text-sm text-muted-foreground">
                            {{ $currentPage }} / {{ $totalPages }}
                        </span>

                        @if($currentPage < $totalPages)
                            <x-ui.button
                                variant="outline"
                                size="sm"
                                href="{{ LaravelLocalization::localizeURL($lojaRoute) }}?page={{ $currentPage + 1 }}{{ $currentCategory ? '&category=' . $currentCategory : '' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </x-ui.button>
                        @endif
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="py-20 text-center">
                    <svg class="mx-auto h-16 w-16 text-muted-foreground/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <h2 class="mt-4 text-xl font-semibold">{{ __('messages.shop.noProducts') }}</h2>
                    <p class="mt-2 text-muted-foreground">{{ __('messages.shop.noProductsDesc') }}</p>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
