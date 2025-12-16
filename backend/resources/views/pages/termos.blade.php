<x-layouts.app>
    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

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
                    {{-- 1. Acceptance --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.terms.sections.acceptance.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.terms.sections.acceptance.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 2. Services --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.terms.sections.services.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.terms.sections.services.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 3. Intellectual Property --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.terms.sections.intellectualProperty.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.terms.sections.intellectualProperty.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 4. User Conduct --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.terms.sections.userConduct.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="mb-4 text-muted-foreground">{{ __('legal.terms.sections.userConduct.content') }}</p>
                            <ul class="list-disc pl-6 space-y-2 text-muted-foreground">
                                @foreach(__('legal.terms.sections.userConduct.items') as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 5. Liability --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.terms.sections.liability.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.terms.sections.liability.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 6. Links --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.terms.sections.links.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.terms.sections.links.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 7. Modifications --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.terms.sections.modifications.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.terms.sections.modifications.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 8. Law --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.terms.sections.law.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.terms.sections.law.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>
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
