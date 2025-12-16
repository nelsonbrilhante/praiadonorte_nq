<?php

namespace App\Livewire;

use App\Models\Noticia;
use App\Models\Evento;
use App\Models\Surfer;
use App\Models\Pagina;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SearchSpotlight extends Component
{
    public string $query = '';
    public array $results = [];

    public function updatedQuery(): void
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        $locale = LaravelLocalization::getCurrentLocale();
        $this->results = [];

        // Search Noticias
        $noticias = Noticia::where('published_at', '<=', now())
            ->where(function ($q) use ($locale) {
                $q->where("title->{$locale}", 'like', "%{$this->query}%")
                  ->orWhere("excerpt->{$locale}", 'like', "%{$this->query}%");
            })
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($noticias as $noticia) {
            $this->results[] = [
                'type' => 'noticia',
                'icon' => 'newspaper',
                'title' => $noticia->title[$locale] ?? $noticia->title['pt'] ?? '',
                'description' => $noticia->excerpt[$locale] ?? $noticia->excerpt['pt'] ?? '',
                'url' => LaravelLocalization::localizeURL('/noticias/' . $noticia->slug),
            ];
        }

        // Search Eventos
        $eventos = Evento::where(function ($q) use ($locale) {
                $q->where("title->{$locale}", 'like', "%{$this->query}%")
                  ->orWhere("description->{$locale}", 'like', "%{$this->query}%")
                  ->orWhere('location', 'like', "%{$this->query}%");
            })
            ->orderBy('start_date', 'desc')
            ->limit(3)
            ->get();

        foreach ($eventos as $evento) {
            $this->results[] = [
                'type' => 'evento',
                'icon' => 'calendar',
                'title' => $evento->title[$locale] ?? $evento->title['pt'] ?? '',
                'description' => $evento->location,
                'url' => LaravelLocalization::localizeURL('/eventos/' . $evento->slug),
            ];
        }

        // Search Surfers
        $surfers = Surfer::where('name', 'like', "%{$this->query}%")
            ->orWhere('nationality', 'like', "%{$this->query}%")
            ->orderBy('name')
            ->limit(3)
            ->get();

        foreach ($surfers as $surfer) {
            $this->results[] = [
                'type' => 'surfer',
                'icon' => 'user',
                'title' => $surfer->name,
                'description' => $surfer->nationality,
                'url' => LaravelLocalization::localizeURL('/surfer-wall/' . $surfer->slug),
            ];
        }

        // Search Paginas
        $paginas = Pagina::where('published', true)
            ->where(function ($q) use ($locale) {
                $q->where("title->{$locale}", 'like', "%{$this->query}%");
            })
            ->limit(3)
            ->get();

        foreach ($paginas as $pagina) {
            $url = match($pagina->entity) {
                'carsurf' => '/carsurf' . ($pagina->slug !== 'homepage' ? '/' . $pagina->slug : ''),
                'nazare-qualifica' => '/nazare-qualifica/' . $pagina->slug,
                default => '/' . $pagina->slug,
            };

            $this->results[] = [
                'type' => 'pagina',
                'icon' => 'document',
                'title' => $pagina->title[$locale] ?? $pagina->title['pt'] ?? '',
                'description' => ucfirst(str_replace('-', ' ', $pagina->entity)),
                'url' => LaravelLocalization::localizeURL($url),
            ];
        }
    }

    public function render()
    {
        return view('livewire.search-spotlight');
    }
}
