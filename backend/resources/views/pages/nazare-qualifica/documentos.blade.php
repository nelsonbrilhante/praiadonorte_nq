@php
    $locale = app('laravellocalization')->getCurrentLocale();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ __('messages.nq.documentos.title') }}" subtitle="{{ __('messages.nq.documentos.subtitle') }}" entity="nazare-qualifica">
        <div class="flex flex-wrap gap-4">
            <x-ui.button href="{{ route('nq.sobre') }}" class="bg-white text-institutional hover:bg-white/90">
                {{ __('messages.pages.about') }}
            </x-ui.button>
            <x-ui.button href="{{ route('nq.sobre') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                {{ __('messages.nq.services.title') }}
            </x-ui.button>
        </div>
    </x-praia-norte.page-hero>

    {{-- Breadcrumbs --}}
    <div class="container mx-auto px-4 py-4">
        <x-ui.breadcrumbs :items="[
            ['label' => __('messages.breadcrumbs.home'), 'href' => route('home')],
            ['label' => __('messages.breadcrumbs.nazare-qualifica'), 'href' => route('nq.sobre')],
            ['label' => __('messages.breadcrumbs.documentos'), 'current' => true],
        ]" />
    </div>

    {{-- Categories Accordion --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.nq.documentos.pageTitle') }}</h2>

                <div class="space-y-3">
                    @foreach($categories as $category)
                        <div
                            x-data="{ open: false }"
                            class="rounded-lg border border-border bg-card overflow-hidden transition-colors"
                            :class="open ? 'border-institutional/30' : ''"
                        >
                            {{-- Category Header --}}
                            <button
                                @click="open = !open"
                                class="flex w-full items-center justify-between px-6 py-4 text-left transition-colors hover:bg-muted/50"
                            >
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
                                    </svg>
                                    <span class="font-semibold">{{ $category->name[$locale] ?? $category->name['pt'] ?? '' }}</span>
                                    <span class="inline-flex items-center rounded-full bg-institutional/10 px-2.5 py-0.5 text-xs font-medium text-institutional">
                                        {{ $category->documents->count() }} {{ __('messages.nq.documentos.documents') }}
                                    </span>
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-muted-foreground transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                >
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>

                            {{-- Documents List --}}
                            <div
                                x-show="open"
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="border-t border-border"
                            >
                                @if($category->documents->isEmpty())
                                    <div class="px-6 py-6 text-center text-sm text-muted-foreground">
                                        {{ __('messages.nq.documentos.noDocuments') }}
                                    </div>
                                @else
                                    <div class="divide-y divide-border">
                                        @foreach($category->documents as $document)
                                            <div class="flex items-center justify-between gap-4 px-6 py-3 transition-colors hover:bg-muted/30">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                    </svg>
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium truncate">
                                                            {{ $document->title[$locale] ?? $document->title['pt'] ?? '' }}
                                                        </p>
                                                        @if($document->published_at)
                                                            <p class="text-xs text-muted-foreground">
                                                                {{ __('messages.nq.documentos.publishedAt', ['date' => $document->published_at->format('d/m/Y')]) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <a
                                                    href="{{ asset('storage/' . $document->file) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-md bg-institutional/10 px-3 py-1.5 text-xs font-medium text-institutional transition-colors hover:bg-institutional/20"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                        <polyline points="7 10 12 15 17 10"/>
                                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                                    </svg>
                                                    {{ __('messages.nq.documentos.download') }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-institutional py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.documentos.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.documentos.cta.subtitle') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="{{ route('contacto') }}" class="bg-white text-institutional hover:bg-white/90">
                    {{ __('messages.navigation.contact') }}
                </x-ui.button>
                <x-ui.button href="{{ route('nq.sobre') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.pages.about') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
