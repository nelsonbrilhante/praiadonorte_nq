@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $webcams = [
        [
            'name' => __('messages.webcams.cameras.panoramica'),
            'stream' => 'https://video-auth1.iol.pt/beachcam/nazareparadonorte/playlist.m3u8',
            'source_url' => 'https://beachcam.meo.pt/livecams/praia-do-norte/',
        ],
        [
            'name' => __('messages.webcams.cameras.canhao'),
            'stream' => 'https://video-auth1.iol.pt/beachcam/canhaonazare/playlist.m3u8',
            'source_url' => 'https://beachcam.meo.pt/livecams/praia-do-norte-canhao-nazare/',
        ],
        [
            'name' => __('messages.webcams.cameras.vila'),
            'stream' => 'https://video-auth1.iol.pt/beachcam/nazarepraiadavila/playlist.m3u8',
            'source_url' => 'https://beachcam.meo.pt/livecams/praia-da-nazare/',
        ],
    ];
@endphp

<x-layouts.app>
    {{-- HLS.js library --}}
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest/dist/hls.min.js"></script>

    <div x-data="{
        modalOpen: false,
        modalLoading: true,
        modalStream: '',
        modalName: '',
        modalHls: null,
        openModal(stream, name) {
            this.modalStream = stream;
            this.modalName = name;
            this.modalOpen = true;
            this.modalLoading = true;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => {
                const video = document.getElementById('modal-video');
                const self = this;
                video.addEventListener('playing', () => { self.modalLoading = false; }, { once: true });
                if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                    this.modalHls = new Hls({ enableWorker: true, lowLatencyMode: true });
                    this.modalHls.loadSource(stream);
                    this.modalHls.attachMedia(video);
                    this.modalHls.on(Hls.Events.MANIFEST_PARSED, () => { video.play().catch(() => {}); });
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = stream;
                    video.play().catch(() => {});
                }
            });
        },
        closeModal() {
            this.modalOpen = false;
            document.body.style.overflow = '';
            if (this.modalHls) { this.modalHls.destroy(); this.modalHls = null; }
            const video = document.getElementById('modal-video');
            if (video) { video.src = ''; }
        }
    }" @keydown.escape.window="closeModal()" @open-webcam.window="openModal($event.detail.stream, $event.detail.name)">

        {{-- Hero --}}
        <x-praia-norte.page-hero title="{{ __('messages.webcams.title') }}" subtitle="{{ __('messages.webcams.subtitle') }}" entity="praia-norte" image="{{ asset('images/forte/intro-aerea.jpg') }}" />

        {{-- Webcams Grid --}}
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="mx-auto max-w-7xl">
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        @foreach($webcams as $index => $webcam)
                        <div class="overflow-hidden rounded-lg border bg-card shadow-sm">
                            {{-- Header with LIVE badge --}}
                            <div class="flex items-center gap-2 border-b px-4 py-3">
                                <span class="relative flex h-3 w-3">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
                                </span>
                                <h3 class="text-sm font-semibold text-foreground">{{ $webcam['name'] }}</h3>
                            </div>

                            {{-- Video Player --}}
                            <div x-data="{ loading: true, error: false }" x-init="
                                $nextTick(() => {
                                    const video = $refs.video;
                                    const streamUrl = '{{ $webcam['stream'] }}';
                                    video.addEventListener('playing', () => { loading = false; }, { once: true });
                                    if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                                        const hls = new Hls({ enableWorker: true, lowLatencyMode: true, maxBufferLength: 10, maxMaxBufferLength: 20 });
                                        hls.loadSource(streamUrl);
                                        hls.attachMedia(video);
                                        hls.on(Hls.Events.MANIFEST_PARSED, () => { video.play().catch(() => {}); });
                                        hls.on(Hls.Events.ERROR, (e, data) => { if (data.fatal) { loading = false; error = true; hls.destroy(); } });
                                    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                                        video.src = streamUrl;
                                        video.addEventListener('error', () => { loading = false; error = true; });
                                        video.play().catch(() => {});
                                    } else { loading = false; error = true; }
                                })
                            " class="relative aspect-video w-full bg-black cursor-pointer" @click="if (!error) $dispatch('open-webcam', { stream: '{{ $webcam['stream'] }}', name: '{{ $webcam['name'] }}' })">
                                <video
                                    x-ref="video"
                                    class="h-full w-full pointer-events-none"
                                    muted
                                    playsinline
                                    autoplay
                                ></video>

                                {{-- Expand icon overlay --}}
                                <div x-show="!loading && !error" class="absolute top-2 right-2 rounded bg-black/60 p-1.5 text-white/70 hover:text-white transition-opacity pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>
                                    </svg>
                                </div>

                                {{-- Loading state --}}
                                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-black/50 pointer-events-none">
                                    <svg class="h-8 w-8 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>

                                {{-- Error state / Fallback --}}
                                <div x-show="error" x-cloak class="absolute inset-0 flex flex-col items-center justify-center gap-4 bg-muted p-4 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                        <path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                        <path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                    </svg>
                                    <p class="text-sm text-muted-foreground">{{ __('messages.webcams.unavailable') }}</p>
                                    <a href="{{ $webcam['source_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 rounded-md border px-3 py-1.5 text-sm font-medium text-foreground hover:bg-muted transition-colors">
                                        {{ __('messages.webcams.watch_external') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Credits --}}
                            <div class="flex items-center justify-between border-t px-4 py-2">
                                <span class="text-xs text-muted-foreground">{{ __('messages.webcams.source') }}:</span>
                                <a href="{{ $webcam['source_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-ocean hover:text-ocean-deep transition-colors">
                                    BeachCam MEO
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Fullscreen Modal --}}
        <div x-show="modalOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 p-4 sm:p-8" @click.self="closeModal()">
            <div class="relative w-full max-w-6xl" @click.stop>
                {{-- Close button --}}
                <button @click="closeModal()" class="absolute -top-10 right-0 flex items-center gap-1 text-sm text-white/70 hover:text-white transition-colors">
                    <span class="hidden sm:inline">{{ __('messages.webcams.close') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>

                {{-- Camera name --}}
                <div class="mb-2 flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    </span>
                    <span class="text-sm font-medium text-white" x-text="modalName"></span>
                </div>

                {{-- Modal video --}}
                <div class="relative aspect-video w-full overflow-hidden rounded-lg bg-black">
                    <video id="modal-video" class="h-full w-full" muted playsinline autoplay></video>
                    {{-- Loading spinner --}}
                    <div x-show="modalLoading" class="absolute inset-0 flex items-center justify-center bg-black/60">
                        <svg class="h-10 w-10 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
