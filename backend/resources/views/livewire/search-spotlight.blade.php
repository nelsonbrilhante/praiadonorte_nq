<div
    x-data="{ show: @entangle('isOpen') }"
    x-show="show"
    x-on:keydown.escape.window="show = false; $wire.close()"
    x-on:keydown.meta.k.window.prevent="show = !show; if(show) $wire.open()"
    x-on:keydown.ctrl.k.window.prevent="show = !show; if(show) $wire.open()"
    @open-search.window="show = true; $wire.open()"
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
        @click="show = false; $wire.close()"
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
                    x-init="$watch('show', value => { if(value) setTimeout(() => $refs.searchInput.focus(), 100) })"
                    wire:model.live.debounce.300ms="query"
                    type="text"
                    placeholder="{{ __('messages.search.placeholder') }}..."
                    class="h-14 w-full border-0 bg-transparent px-4 text-base placeholder:text-muted-foreground focus:outline-none focus:ring-0"
                />
                <kbd class="hidden h-6 select-none items-center gap-1 rounded border bg-muted px-2 font-mono text-xs font-medium text-muted-foreground sm:flex">
                    ESC
                </kbd>
            </div>

            {{-- Results --}}
            <div class="max-h-[60vh] overflow-y-auto">
                @if(strlen($query) >= 2)
                    @if(count($results) > 0)
                        <div class="p-2">
                            @php
                                $groupedResults = collect($results)->groupBy('type');
                            @endphp

                            @foreach($groupedResults as $type => $items)
                                <div class="mb-2">
                                    <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                                        @switch($type)
                                            @case('noticia')
                                                {{ __('messages.navigation.news') }}
                                                @break
                                            @case('evento')
                                                {{ __('messages.navigation.events') }}
                                                @break
                                            @case('surfer')
                                                {{ __('messages.navigation.surferWall') }}
                                                @break
                                            @case('pagina')
                                                {{ __('messages.pages.pages') ?? 'Páginas' }}
                                                @break
                                        @endswitch
                                    </div>
                                    @foreach($items as $result)
                                        <a
                                            href="{{ $result['url'] }}"
                                            class="flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-accent transition-colors"
                                        >
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-muted">
                                                @switch($result['icon'])
                                                    @case('newspaper')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-ocean">
                                                            <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                                            <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                                                        </svg>
                                                        @break
                                                    @case('calendar')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-performance">
                                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                                            <line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/>
                                                            <line x1="3" x2="21" y1="10" y2="10"/>
                                                        </svg>
                                                        @break
                                                    @case('user')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-institutional">
                                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                                            <circle cx="12" cy="7" r="4"/>
                                                        </svg>
                                                        @break
                                                    @case('document')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                                                            <polyline points="14 2 14 8 20 8"/>
                                                        </svg>
                                                        @break
                                                @endswitch
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate font-medium">{{ $result['title'] }}</p>
                                                <p class="truncate text-sm text-muted-foreground">{{ Str::limit($result['description'], 60) }}</p>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-muted-foreground">
                                                <path d="m9 18 6-6-6-6"/>
                                            </svg>
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-14 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-muted-foreground/50">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                            <p class="mt-4 text-sm text-muted-foreground">
                                {{ __('messages.search.noResults') ?? 'Nenhum resultado encontrado' }}
                            </p>
                        </div>
                    @endif
                @else
                    <div class="px-4 py-14 text-center">
                        <p class="text-sm text-muted-foreground">
                            {{ __('messages.search.hint') ?? 'Digite pelo menos 2 caracteres para pesquisar...' }}
                        </p>
                    </div>
                @endif
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
