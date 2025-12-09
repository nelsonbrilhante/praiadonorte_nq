<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Noticia::query();

        if ($request->has('entity')) {
            $query->where('entity', $request->entity);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        $query->whereNotNull('published_at')
              ->where('published_at', '<=', now())
              ->orderBy('published_at', 'desc');

        $perPage = $request->get('per_page', 10);
        $noticias = $query->paginate($perPage);

        return response()->json($noticias);
    }

    public function show(string $slug)
    {
        $noticia = Noticia::where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return response()->json($noticia);
    }

    public function latest(Request $request)
    {
        $limit = $request->get('limit', 5);
        $entity = $request->get('entity');

        $query = Noticia::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc');

        if ($entity) {
            $query->where('entity', $entity);
        }

        $noticias = $query->limit($limit)->get();

        return response()->json($noticias);
    }
}
