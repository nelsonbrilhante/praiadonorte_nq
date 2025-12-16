{{-- Search Spotlight - Pure Alpine.js Component --}}
{{-- Uses API endpoint instead of Livewire to avoid PHP built-in server issues --}}

@php
$locale = app()->getLocale();
$typeLabels = [
    'noticia' => __('messages.navigation.news'),
    'evento' => __('messages.navigation.events'),
    'surfer' => __('messages.navigation.surferWall'),
    'pagina' => __('messages.pages.pages') ?? 'Páginas',
];
@endphp

<div
    x-data="searchSpotlight()"
    x-show="show"
    x-on:keydown.escape.window="close()"
    x-on:keydown.meta.k.window.prevent="toggle()"
    x-on:keydown.ctrl.k.window.prevent="toggle()"
    @open-search.window="open()"
    x-cloak
    class="fixed inset-0 z-[100]"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    {{-- Modal --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-x-4 top-[15%] mx-auto max-w-xl"
    >
        <div class="overflow-hidden rounded-xl bg-background shadow-2xl ring-1 ring-border">
            {{-- Search Input --}}
            <div class="flex items-center border-b px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                <input
                    x-ref="searchInput"
                    x-model="query"
                    @input.debounce.300ms="search()"
                    type="text"
                    placeholder="{{ __('messages.search.placeholder') }}..."
                    class="h-14 w-full border-0 bg-transparent px-4 text-base placeholder:text-muted-foreground focus:outline-none focus:ring-0"
                />
                {{-- Loading indicator --}}
                <svg x-show="loading" class="animate-spin h-5 w-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <kbd x-show="!loading" class="hidden h-6 select-none items-center gap-1 rounded border bg-muted px-2 font-mono text-xs font-medium text-muted-foreground sm:flex">
                    ESC
                </kbd>
            </div>

            {{-- Results --}}
            <div class="max-h-[60vh] overflow-y-auto">
                {{-- Minimum characters hint --}}
                <template x-if="query.length < 2">
                    <div class="px-4 py-14 text-center">
                        <p class="text-sm text-muted-foreground">
                            {{ __('messages.search.hint') ?? 'Digite pelo menos 2 caracteres para pesquisar...' }}
                        </p>
                    </div>
                </template>

                {{-- Results list --}}
                <template x-if="query.length >= 2 && !loading && Object.keys(groupedResults).length > 0">
                    <div class="p-2">
                        <template x-for="(items, type) in groupedResults" :key="type">
                            <div class="mb-2">
                                <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wide text-muted-foreground" x-text="typeLabels[type] || type"></div>
                                <template x-for="result in items" :key="result.id">
                                    <a
                                        :href="'/' + locale + result.url"
                                        class="flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-accent transition-colors"
                                    >
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-muted">
                                            {{-- News icon --}}
                                            <template x-if="result.type === 'noticia'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-ocean">
                                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                                    <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                                                </svg>
                                            </template>
                                            {{-- Event icon --}}
                                            <template x-if="result.type === 'evento'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-performance">
                                                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                                    <line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/>
                                                    <line x1="3" x2="21" y1="10" y2="10"/>
                                                </svg>
                                            </template>
                                            {{-- Surfer icon --}}
                                            <template x-if="result.type === 'surfer'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-institutional">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                </svg>
                                            </template>
                                            {{-- Page icon --}}
                                            <template x-if="result.type === 'pagina'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                                                    <polyline points="14 2 14 8 20 8"/>
                                                </svg>
                                            </template>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate font-medium" x-text="getTitle(result)"></p>
                                            <p class="truncate text-sm text-muted-foreground" x-text="getDescription(result)"></p>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-muted-foreground">
                                            <path d="m9 18 6-6-6-6"/>
                                        </svg>
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- No results --}}
                <template x-if="query.length >= 2 && !loading && Object.keys(groupedResults).length === 0 && searched">
                    <div class="px-4 py-14 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-muted-foreground/50">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                        <p class="mt-4 text-sm text-muted-foreground">
                            {{ __('messages.search.noResults') ?? 'Nenhum resultado encontrado' }}
                        </p>
                    </div>
                </template>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between border-t bg-muted/50 px-4 py-2 text-xs text-muted-foreground">
                <div class="flex items-center gap-2">
                    <kbd class="rounded border bg-background px-1.5 py-0.5 font-mono">↵</kbd>
                    <span>{{ __('messages.search.select') ?? 'selecionar' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <kbd class="rounded border bg-background px-1.5 py-0.5 font-mono">esc</kbd>
                    <span>{{ __('messages.search.close') ?? 'fechar' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function searchSpotlight() {
    return {
        show: false,
        query: '',
        results: [],
        groupedResults: {},
        loading: false,
        searched: false,
        locale: '{{ $locale }}',
        typeLabels: @json($typeLabels),
        debounceTimer: null,

        open() {
            this.show = true;
            this.query = '';
            this.results = [];
            this.groupedResults = {};
            this.searched = false;
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });
        },

        close() {
            if (this.show) {
                this.show = false;
                this.query = '';
                this.results = [];
                this.groupedResults = {};
                this.searched = false;
            }
        },

        toggle() {
            if (this.show) {
                this.close();
            } else {
                this.open();
            }
        },

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.groupedResults = {};
                this.searched = false;
                return;
            }

            this.loading = true;

            try {
                const response = await fetch(`/api/v1/search?q=${encodeURIComponent(this.query)}`);
                const data = await response.json();

                this.results = data.results || [];
                this.groupedResults = this.groupResults(this.results);
                this.searched = true;
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
                this.groupedResults = {};
            } finally {
                this.loading = false;
            }
        },

        groupResults(results) {
            return results.reduce((groups, item) => {
                const type = item.type;
                if (!groups[type]) {
                    groups[type] = [];
                }
                groups[type].push(item);
                return groups;
            }, {});
        },

        getTitle(result) {
            if (typeof result.title === 'object' && result.title !== null) {
                return result.title[this.locale] || result.title['pt'] || '';
            }
            return result.title || result.name || '';
        },

        getDescription(result) {
            if (result.excerpt) {
                if (typeof result.excerpt === 'object' && result.excerpt !== null) {
                    return result.excerpt[this.locale] || result.excerpt['pt'] || '';
                }
                return result.excerpt;
            }
            if (result.location) return result.location;
            if (result.nationality) return result.nationality;
            if (result.entity) return result.entity;
            return '';
        }
    }
}
</script>
