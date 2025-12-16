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
                    {{-- 1. Responsible --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.privacy.sections.responsible.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.privacy.sections.responsible.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 2. Data Collected --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.privacy.sections.dataCollected.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="mb-4 text-muted-foreground">{{ __('legal.privacy.sections.dataCollected.content') }}</p>
                            <ul class="list-disc pl-6 space-y-2 text-muted-foreground">
                                @foreach(__('legal.privacy.sections.dataCollected.items') as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 3. Purpose --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.privacy.sections.purpose.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="mb-4 text-muted-foreground">{{ __('legal.privacy.sections.purpose.content') }}</p>
                            <ul class="list-disc pl-6 space-y-2 text-muted-foreground">
                                @foreach(__('legal.privacy.sections.purpose.items') as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 4. Legal Basis --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.privacy.sections.legalBasis.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.privacy.sections.legalBasis.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 5. Retention --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.privacy.sections.retention.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.privacy.sections.retention.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 6. Rights --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.privacy.sections.rights.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="mb-4 text-muted-foreground">{{ __('legal.privacy.sections.rights.content') }}</p>
                            <ul class="list-disc pl-6 space-y-2 text-muted-foreground">
                                @foreach(__('legal.privacy.sections.rights.items') as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 7. Security --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.privacy.sections.security.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.privacy.sections.security.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 8. Contact --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.privacy.sections.contact.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.privacy.sections.contact.content') }}</p>
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
