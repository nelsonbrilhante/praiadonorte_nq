@props([
    'items' => [],
    'showHome' => true,
])

@php
    $locale = LaravelLocalization::getCurrentLocale();

    // Auto-generate breadcrumbs from request path if no items provided
    if (empty($items)) {
        $path = request()->path();
        $pathWithoutLocale = preg_replace('/^' . $locale . '\//', '', $path);

        if ($pathWithoutLocale && $pathWithoutLocale !== $locale) {
            $segments = explode('/', $pathWithoutLocale);
            $currentPath = '/' . $locale;

            foreach ($segments as $i => $segment) {
                if (empty($segment)) continue;

                $currentPath .= '/' . $segment;
                $items[] = [
                    'label' => __('messages.breadcrumbs.' . $segment, [], $locale) !== 'messages.breadcrumbs.' . $segment
                        ? __('messages.breadcrumbs.' . $segment)
                        : ucwords(str_replace('-', ' ', $segment)),
                    'href' => LaravelLocalization::localizeURL($currentPath),
                    'current' => $i === count($segments) - 1,
                ];
            }
        }
    }
@endphp

@if(count($items) > 0 || $showHome)
    <nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'py-3']) }}>
        <ol class="flex flex-wrap items-center gap-1.5 text-sm text-muted-foreground">
            @if($showHome)
                <li class="flex items-center gap-1.5">
                    <a href="{{ LaravelLocalization::localizeURL('/') }}" class="flex items-center gap-1 hover:text-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <span class="sr-only">{{ __('messages.breadcrumbs.home') }}</span>
                    </a>
                    @if(count($items) > 0)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground/50">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    @endif
                </li>
            @endif

            @foreach($items as $index => $item)
                <li class="flex items-center gap-1.5">
                    @if($item['current'] ?? false)
                        <span class="font-medium text-foreground" aria-current="page">
                            {{ $item['label'] }}
                        </span>
                    @else
                        <a href="{{ $item['href'] }}" class="hover:text-foreground transition-colors">
                            {{ $item['label'] }}
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground/50">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
