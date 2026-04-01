<x-layouts.app
    :seo_title="$product['name'] . ' | ' . __('messages.shop.title') . ' | ' . __('messages.metadata.title')"
    :seo_description="Str::limit(strip_tags($product['short_description']), 160)"
    :og_image="($product['featured_image']['src'] ?? null)"
>
    @php
        $lojaRoute = $locale === 'pt' ? '/loja' : '/shop';
    @endphp

    {{-- Breadcrumbs --}}
    <x-ui.breadcrumbs :items="[
        ['label' => __('messages.breadcrumbs.home'), 'href' => LaravelLocalization::localizeURL('/')],
        ['label' => __('messages.shop.title'), 'href' => LaravelLocalization::localizeURL($lojaRoute)],
        ['label' => \Illuminate\Support\Str::limit($product['name'], 40), 'current' => true],
    ]" />

    {{-- Page Header --}}
    <section class="py-8 border-b border-border">
        <div class="container mx-auto px-4">
            {{-- Categories --}}
            @if(count($product['categories']) > 0)
                <div class="mb-2 flex flex-wrap gap-2">
                    @foreach($product['categories'] as $cat)
                        <span class="text-xs font-medium uppercase tracking-wider text-ocean">
                            {{ $cat['name'] }}
                        </span>
                        @if(!$loop->last)
                            <span class="text-xs text-muted-foreground">&middot;</span>
                        @endif
                    @endforeach
                </div>
            @endif

            <h1 class="text-3xl font-bold md:text-4xl">{{ $product['name'] }}</h1>

            {{-- Price --}}
            <div class="mt-2 flex items-baseline gap-3">
                @if($product['price'])
                    <span class="text-2xl font-bold">{{ number_format((float) $product['price'], 2, ',', '.') }} &euro;</span>
                    @if($product['on_sale'] && $product['regular_price'])
                        <span class="text-lg text-muted-foreground line-through">
                            {{ number_format((float) $product['regular_price'], 2, ',', '.') }} &euro;
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </section>

    {{-- Info Bar --}}
    <section class="border-b bg-muted/10 py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                {{-- Stock status --}}
                <div class="flex items-center gap-2">
                    @if($product['stock_status'] === 'instock')
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        <span class="font-medium text-green-700 dark:text-green-400">{{ __('messages.shop.inStock') }}</span>
                    @else
                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                        <span class="font-medium text-red-700 dark:text-red-400">{{ __('messages.shop.outOfStock') }}</span>
                    @endif
                </div>

                {{-- VAT --}}
                <div>
                    <span class="text-muted-foreground">{{ __('messages.shop.vatIncluded') }}</span>
                </div>

                {{-- Categories --}}
                @if(count($product['categories']) > 0)
                    <div>
                        <span class="text-muted-foreground">{{ __('messages.events.category') }}:</span>
                        <span class="font-medium">
                            {{ collect($product['categories'])->pluck('name')->join(', ') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Product Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                {{-- Image Gallery --}}
                <div class="space-y-4" x-data="{ activeImage: 0 }">
                    {{-- Main Image --}}
                    <div class="aspect-square overflow-hidden rounded-xl border bg-muted">
                        @if(count($product['images']) > 0)
                            @foreach($product['images'] as $index => $image)
                                <img
                                    x-show="activeImage === {{ $index }}"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    src="{{ $image['src'] }}"
                                    alt="{{ $image['alt'] ?: $product['name'] }}"
                                    class="h-full w-full object-cover"
                                />
                            @endforeach
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <svg class="h-20 w-20 text-muted-foreground/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    @if(count($product['images']) > 1)
                        <div class="flex gap-2 overflow-x-auto pb-2">
                            @foreach($product['images'] as $index => $image)
                                <button
                                    @click="activeImage = {{ $index }}"
                                    :class="activeImage === {{ $index }} ? 'ring-2 ring-ocean' : 'opacity-60 hover:opacity-100'"
                                    class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border transition-all"
                                >
                                    <img src="{{ $image['src'] }}" alt="" class="h-full w-full object-cover" />
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Product Details --}}
                <div>
                    {{-- Price Block --}}
                    <div class="mb-6 rounded-xl border bg-card p-6">
                        <div class="flex items-baseline gap-3">
                            @if($product['price'])
                                <span class="text-3xl font-bold">{{ number_format((float) $product['price'], 2, ',', '.') }} &euro;</span>
                                @if($product['on_sale'] && $product['regular_price'])
                                    <span class="text-lg text-muted-foreground line-through">
                                        {{ number_format((float) $product['regular_price'], 2, ',', '.') }} &euro;
                                    </span>
                                    @php
                                        $discount = round((1 - (float)$product['price'] / (float)$product['regular_price']) * 100);
                                    @endphp
                                    <x-ui.badge variant="destructive">-{{ $discount }}%</x-ui.badge>
                                @endif
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-muted-foreground">{{ __('messages.shop.vatIncluded') }}</p>

                        {{-- Buy Button --}}
                        <div class="mt-6">
                            @if($product['stock_status'] === 'instock')
                                <x-ui.button
                                    variant="ocean"
                                    size="lg"
                                    href="{{ $product['permalink'] }}{{ str_contains($product['permalink'], '?') ? '&' : '?' }}lang={{ app()->getLocale() }}"
                                    class="w-full justify-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                        <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                    </svg>
                                    {{ __('messages.shop.buy') }}
                                </x-ui.button>
                            @else
                                <x-ui.button variant="outline" size="lg" class="w-full justify-center" disabled>
                                    {{ __('messages.shop.outOfStock') }}
                                </x-ui.button>
                            @endif
                        </div>
                    </div>

                    {{-- Short Description --}}
                    @if($product['short_description'])
                        <div class="prose prose-sm max-w-none dark:prose-invert">
                            {!! $product['short_description'] !!}
                        </div>
                    @endif

                    {{-- Full Description --}}
                    @if($product['description'])
                        <div class="mt-6">
                            <h2 class="mb-3 text-lg font-bold">{{ __('messages.shop.description') }}</h2>
                            <div class="prose prose-sm max-w-none dark:prose-invert">
                                {!! $product['description'] !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Back Link --}}
            <div class="mt-10">
                <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL($lojaRoute) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    {{ __('messages.shop.backToShop') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
