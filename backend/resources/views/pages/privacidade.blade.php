<x-layouts.app>
    {{-- Header --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('legal.privacy.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('legal.privacy.subtitle') }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                {{-- Last updated --}}
                <p class="mb-8 text-sm text-muted-foreground">
                    {{ __('legal.privacy.lastUpdated') }}: {{ now()->format('d/m/Y') }}
                </p>

                {{-- Introduction --}}
                <div class="prose prose-lg max-w-none mb-12">
                    <p class="text-lg text-muted-foreground">{{ __('legal.privacy.intro') }}</p>
                </div>

                {{-- Sections --}}
                <div class="space-y-8">
                    @php
                        $sections = __('legal.privacy.sections');
                    @endphp

                    @foreach ($sections as $key => $section)
                        <x-ui.card>
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
