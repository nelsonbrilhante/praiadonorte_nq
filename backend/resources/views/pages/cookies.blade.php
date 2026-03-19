<x-layouts.app>
    {{-- Header --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ $title }}</h1>
            <p class="text-xl opacity-90">{{ $subtitle ?? '' }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                @if($lastUpdated)
                    <p class="mb-8 text-sm text-muted-foreground">
                        {{ __('legal.cookies.lastUpdated') }}: {{ \Carbon\Carbon::parse($lastUpdated)->format('d/m/Y') }}
                    </p>
                @endif

                <div class="prose prose-lg max-w-none dark:prose-invert">
                    {!! $content !!}
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('home') }}" class="text-ocean hover:underline">
                        &larr; {{ __('messages.navigation.home') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
