<x-layouts.app>
    {{-- Header --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('legal.terms.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('legal.terms.subtitle') }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                {{-- Last updated --}}
                <p class="mb-8 text-sm text-muted-foreground">
                    {{ __('legal.terms.lastUpdated') }}: {{ now()->format('d/m/Y') }}
                </p>

                {{-- Introduction --}}
                <div class="prose prose-lg max-w-none mb-12">
                    <p class="text-lg text-muted-foreground">{{ __('legal.terms.intro') }}</p>
                </div>

                {{-- Sections --}}
                <div class="space-y-8">
                    @php
                        $sections = __('legal.terms.sections');
                    @endphp

                    @foreach ($sections as $key => $section)
                        <x-ui.card id="{{ $key === 'disputes' ? 'litigios' : '' }}">
                            <x-ui.card-header>
                                <x-ui.card-title>{{ $section['title'] }}</x-ui.card-title>
                            </x-ui.card-header>
                            <x-ui.card-content>
                                <p class="text-muted-foreground">{{ $section['content'] }}</p>

                                @if (isset($section['items']))
                                    <ul class="mt-4 list-disc pl-6 space-y-2 text-muted-foreground">
                                        @foreach ($section['items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if (isset($section['extra']))
                                    <p class="mt-4 text-muted-foreground">{{ $section['extra'] }}</p>
                                @endif

                                @if (isset($section['odrLink']))
                                    <p class="mt-4">
                                        <a href="{{ $section['odrLink'] }}" target="_blank" rel="noopener noreferrer" class="text-ocean font-medium hover:underline">
                                            {{ $section['odrLink'] }}
                                            <span class="text-xs" aria-hidden="true">&#8599;</span>
                                        </a>
                                    </p>
                                @endif

                                @if (isset($section['formTitle']))
                                    <div class="mt-6 rounded-lg border border-border bg-muted/50 p-6">
                                        <h4 class="mb-3 font-semibold text-foreground">{{ $section['formTitle'] }}</h4>
                                        <p class="text-sm text-muted-foreground whitespace-pre-line">{{ $section['formContent'] }}</p>
                                    </div>
                                @endif
                            </x-ui.card-content>
                        </x-ui.card>
                    @endforeach
                </div>

                {{-- Back link --}}
                <div class="mt-12 text-center">
                    <a href="{{ route('home') }}" class="text-ocean hover:underline">
                        &larr; {{ __('messages.navigation.home') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
