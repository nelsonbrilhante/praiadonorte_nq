<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pagina;
use Illuminate\Http\Request;

class PaginaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pagina::where('published', true);

        if ($request->has('entity')) {
            $query->where('entity', $request->entity);
        }

        $paginas = $query->orderBy('title->pt', 'asc')->get();

        return response()->json($paginas);
    }

    public function show(string $entity, string $slug)
    {
        $pagina = Pagina::where('entity', $entity)
            ->where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        return response()->json($pagina);
    }
}
