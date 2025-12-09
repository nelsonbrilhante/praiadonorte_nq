<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use App\Models\Evento;
use App\Models\Surfer;
use App\Models\Pagina;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $limit = min($request->input('limit', 10), 50);

        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'total' => 0,
            ]);
        }

        $results = [];

        // Search Noticias (JSON fields stored as text - search in raw JSON)
        $noticias = Noticia::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%")
                    ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get(['id', 'slug', 'title', 'excerpt', 'entity', 'published_at']);

        foreach ($noticias as $noticia) {
            $results[] = [
                'type' => 'noticia',
                'id' => $noticia->id,
                'slug' => $noticia->slug,
                'title' => $noticia->title,
                'excerpt' => $noticia->excerpt,
                'entity' => $noticia->entity,
                'url' => "/noticias/{$noticia->slug}",
            ];
        }

        // Search Eventos (JSON fields stored as text - search in raw JSON)
        $eventos = Evento::where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('location', 'LIKE', "%{$query}%");
            })
            ->orderBy('start_date', 'desc')
            ->limit($limit)
            ->get(['id', 'slug', 'title', 'entity', 'start_date', 'location']);

        foreach ($eventos as $evento) {
            $results[] = [
                'type' => 'evento',
                'id' => $evento->id,
                'slug' => $evento->slug,
                'title' => $evento->title,
                'entity' => $evento->entity,
                'location' => $evento->location,
                'url' => "/eventos/{$evento->slug}",
            ];
        }

        // Search Surfers (JSON field bio stored as text - search in raw JSON)
        $surfers = Surfer::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('bio', 'LIKE', "%{$query}%")
                ->orWhere('nationality', 'LIKE', "%{$query}%");
        })
            ->orderBy('featured', 'desc')
            ->orderBy('name', 'asc')
            ->limit($limit)
            ->get(['id', 'slug', 'name', 'nationality', 'featured']);

        foreach ($surfers as $surfer) {
            $results[] = [
                'type' => 'surfer',
                'id' => $surfer->id,
                'slug' => $surfer->slug,
                'title' => ['pt' => $surfer->name, 'en' => $surfer->name],
                'nationality' => $surfer->nationality,
                'featured' => $surfer->featured,
                'url' => "/surfer-wall/{$surfer->slug}",
            ];
        }

        // Search Paginas (JSON fields stored as text - search in raw JSON)
        $paginas = Pagina::where('published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->limit($limit)
            ->get(['id', 'slug', 'title', 'entity']);

        foreach ($paginas as $pagina) {
            $entityPath = match ($pagina->entity) {
                'praia-norte' => '',
                'carsurf' => '/carsurf',
                'nazare-qualifica' => '/nazare-qualifica',
                default => '',
            };
            $results[] = [
                'type' => 'pagina',
                'id' => $pagina->id,
                'slug' => $pagina->slug,
                'title' => $pagina->title,
                'entity' => $pagina->entity,
                'url' => "{$entityPath}/{$pagina->slug}",
            ];
        }

        return response()->json([
            'results' => $results,
            'total' => count($results),
            'query' => $query,
        ]);
    }
}
