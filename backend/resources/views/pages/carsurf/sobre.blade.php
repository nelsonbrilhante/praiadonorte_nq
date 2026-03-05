@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $pagina = \App\Models\Pagina::where('entity', 'carsurf')->where('slug', 'sobre')->first();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero
        title="{{ __('messages.carsurf.about.pageTitle') }}"
        subtitle="{{ __('messages.carsurf.about.pageSubtitle') }}"
        entity="carsurf"
        image="{{ asset('images/carsurf/female-surfer.jpg') }}"
    />

    {{-- Intro: Logo + Mission --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <img
                    src="{{ asset('images/logos/CARSURF_001.png') }}"
                    alt="Carsurf"
                    class="h-32 md:h-40 w-auto mx-auto"
                />
            </div>
            <div class="mx-auto max-w-3xl">
                <div class="prose max-w-none dark:prose-invert">
                    <h2>{{ __('messages.carsurf.about.mission.title') }}</h2>
                    <p>{{ __('messages.carsurf.about.mission.text') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- YouTube Video --}}
    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-900 dark:text-white">
                {{ __('messages.carsurf.about.video.title') }}
            </h2>
            <div class="mx-auto max-w-4xl">
                <div class="relative w-full overflow-hidden rounded-xl shadow-lg" style="padding-top: 56.25%">
                    <iframe
                        class="absolute inset-0 h-full w-full"
                        src="https://www.youtube.com/embed/dUqKdF-AcCQ"
                        title="{{ __('messages.carsurf.about.video.title') }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        loading="lazy"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- Vista Inspiradora --}}
    <section class="relative min-h-[400px] flex items-center justify-center overflow-hidden">
        <img
            src="{{ asset('images/carsurf/carsurf-big-09.jpg') }}"
            alt="{{ __('messages.carsurf.about.vista.title') }}"
            class="absolute inset-0 h-full w-full object-cover"
            loading="lazy"
        />
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative container mx-auto px-4 py-16 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                {{ __('messages.carsurf.about.vista.title') }}
            </h2>
            <p class="mx-auto max-w-3xl text-lg md:text-xl opacity-90 leading-relaxed">
                {{ __('messages.carsurf.about.vista.text') }}
            </p>
        </div>
    </section>

    {{-- History + Values --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <div class="prose max-w-none dark:prose-invert">
                    <h2>{{ __('messages.carsurf.about.history.title') }}</h2>
                    <p>{{ __('messages.carsurf.about.history.text') }}</p>

                    <h2>{{ __('messages.carsurf.about.values.title') }}</h2>
                    <ul>
                        <li>{{ __('messages.carsurf.about.values.item1') }}</li>
                        <li>{{ __('messages.carsurf.about.values.item2') }}</li>
                        <li>{{ __('messages.carsurf.about.values.item3') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Facilities Gallery --}}
    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-900 dark:text-white">
                {{ __('messages.carsurf.about.gallery.title') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-1">
                {{-- Row 1 --}}
                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/instalacoes-01.jpg') }}" alt="Alojamento" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                </div>

                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/instalacoes-02.jpg') }}" alt="Carsurf" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-performance-600/80 flex flex-col items-center justify-center text-white p-4">
                        <img src="{{ asset('images/logos/CARSURF_001.png') }}" alt="Carsurf" class="h-16 md:h-20 w-auto mb-4" />
                        <p class="text-sm md:text-base text-center leading-snug max-w-[220px]">
                            {{ __('messages.carsurf.about.gallery.tagline') }}
                        </p>
                    </div>
                </div>

                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/instalacoes-05.jpg') }}" alt="Piscina" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                </div>

                {{-- Row 2 --}}
                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/complexo_desportivo.jpg') }}" alt="Complexo desportivo" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                </div>

                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/pavilhao.jpg') }}" alt="Pavilhão gimnodesportivo" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                </div>

                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/fc_porto.jpg') }}" alt="Ginásio" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                </div>

                {{-- Row 3 --}}
                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/skate_park.jpg') }}" alt="Estádio" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                </div>

                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/about-gallery-7.jpg') }}" alt="Surf" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                </div>

                <div class="relative aspect-[4/3] overflow-hidden group">
                    <img src="{{ asset('images/carsurf/anastase-maragos.jpg') }}" alt="Fitness" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-performance-600 py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">{{ __('messages.carsurf.about.cta.title') }}</h2>
            <p class="text-lg opacity-90 mb-8">{{ __('messages.carsurf.about.cta.subtitle') }}</p>
            <a href="{{ LaravelLocalization::localizeUrl('/contacto') }}"
               class="inline-block rounded-lg bg-white px-8 py-3 font-semibold text-performance-600 transition hover:bg-gray-100">
                {{ __('messages.carsurf.about.cta.button') }}
            </a>
        </div>
    </section>

</x-layouts.app>
