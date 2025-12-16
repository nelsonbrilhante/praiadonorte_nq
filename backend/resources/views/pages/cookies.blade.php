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
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('legal.cookies.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('legal.cookies.subtitle') }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                {{-- Last updated --}}
                <p class="mb-8 text-sm text-muted-foreground">
                    {{ __('legal.cookies.lastUpdated') }}: {{ now()->format('d/m/Y') }}
                </p>

                {{-- Introduction --}}
                <div class="prose prose-lg max-w-none mb-12">
                    <p class="text-lg text-muted-foreground">{{ __('legal.cookies.intro') }}</p>
                </div>

                {{-- Sections --}}
                <div class="space-y-8">
                    {{-- 1. What are Cookies --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.cookies.sections.whatAreCookies.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.cookies.sections.whatAreCookies.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 2. Types of Cookies --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.cookies.sections.typesOfCookies.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="space-y-6">
                                {{-- Essential --}}
                                <div>
                                    <h4 class="font-semibold mb-2">{{ __('legal.cookies.sections.typesOfCookies.essential.title') }}</h4>
                                    <p class="text-muted-foreground">{{ __('legal.cookies.sections.typesOfCookies.essential.content') }}</p>
                                </div>
                                {{-- Analytics --}}
                                <div>
                                    <h4 class="font-semibold mb-2">{{ __('legal.cookies.sections.typesOfCookies.analytics.title') }}</h4>
                                    <p class="text-muted-foreground">{{ __('legal.cookies.sections.typesOfCookies.analytics.content') }}</p>
                                </div>
                                {{-- Functional --}}
                                <div>
                                    <h4 class="font-semibold mb-2">{{ __('legal.cookies.sections.typesOfCookies.functional.title') }}</h4>
                                    <p class="text-muted-foreground">{{ __('legal.cookies.sections.typesOfCookies.functional.content') }}</p>
                                </div>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 3. Third Party --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.cookies.sections.thirdParty.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="mb-4 text-muted-foreground">{{ __('legal.cookies.sections.thirdParty.content') }}</p>
                            <ul class="list-disc pl-6 space-y-2 text-muted-foreground">
                                @foreach(__('legal.cookies.sections.thirdParty.items') as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 4. Management --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.cookies.sections.management.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.cookies.sections.management.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 5. Consent --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.cookies.sections.consent.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.cookies.sections.consent.content') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- 6. More Info --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('legal.cookies.sections.moreInfo.title') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-muted-foreground">{{ __('legal.cookies.sections.moreInfo.content') }}</p>
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
