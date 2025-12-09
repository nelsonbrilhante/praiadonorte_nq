<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $query = Evento::query();

        if ($request->has('entity')) {
            $query->where('entity', $request->entity);
        }

        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        if ($request->has('upcoming')) {
            $query->where('start_date', '>=', now()->toDateString());
        }

        if ($request->has('past')) {
            $query->where('start_date', '<', now()->toDateString());
        }

        $query->orderBy('start_date', 'asc');

        $perPage = $request->get('per_page', 10);
        $eventos = $query->paginate($perPage);

        return response()->json($eventos);
    }

    public function show(string $slug)
    {
        $evento = Evento::where('slug', $slug)->firstOrFail();

        return response()->json($evento);
    }

    public function upcoming(Request $request)
    {
        $limit = $request->get('limit', 5);
        $entity = $request->get('entity');

        $query = Evento::where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc');

        if ($entity) {
            $query->where('entity', $entity);
        }

        $eventos = $query->limit($limit)->get();

        return response()->json($eventos);
    }
}
