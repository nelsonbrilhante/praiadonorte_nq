<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ __('messages.nq.contraordenacoes.identificacaoCondutor.title') }}" subtitle="{{ __('messages.nq.contraordenacoes.identificacaoCondutor.subtitle') }}" entity="nazare-qualifica">
        <div class="flex flex-wrap gap-4">
            <x-ui.button href="{{ route('nq.contraordenacoes') }}" class="bg-white text-institutional hover:bg-white/90">
                {{ __('messages.nq.contraordenacoes.title') }}
            </x-ui.button>
            <x-ui.button href="{{ route('nq.apresentacao-defesa') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                {{ __('messages.nq.contraordenacoes.apresentacaoDefesa.title') }}
            </x-ui.button>
        </div>
    </x-praia-norte.page-hero>

    {{-- Introduction --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="mb-6 text-3xl font-bold">{{ __('messages.nq.contraordenacoes.identificacaoCondutor.title') }}</h2>
                <p class="text-lg text-muted-foreground">
                    {{ __('messages.nq.contraordenacoes.identificacaoCondutor.intro') }}
                </p>
            </div>
        </div>
    </section>

    {{-- Iframe --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl" x-data="{ iframeLoaded: false }">
                <div class="relative w-full overflow-hidden rounded-lg border border-white/10 bg-white shadow-lg">
                    {{-- Loading spinner --}}
                    <div x-show="!iframeLoaded" class="absolute inset-0 flex items-center justify-center bg-white">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="h-8 w-8 animate-spin text-institutional" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm text-muted-foreground">{{ __('messages.common.loading') }}</span>
                        </div>
                    </div>
                    <iframe
                        src="https://ace.urbanmotion.pt/ic?entity=NAZARE%20QUALIFICA&branding=false"
                        class="h-[800px] w-full border-0"
                        title="{{ __('messages.nq.contraordenacoes.identificacaoCondutor.title') }}"
                        loading="lazy"
                        @load="iframeLoaded = true"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-institutional py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.contraordenacoes.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.contraordenacoes.cta.subtitle') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="{{ route('nq.contraordenacoes') }}" class="bg-white text-institutional hover:bg-white/90">
                    {{ __('messages.nq.contraordenacoes.title') }}
                </x-ui.button>
                <x-ui.button href="{{ route('contacto') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.navigation.contact') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
