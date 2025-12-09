<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surfer;
use Illuminate\Http\Request;

class SurferController extends Controller
{
    public function index(Request $request)
    {
        $query = Surfer::with('surfboards');

        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        $surfers = $query->orderBy('order', 'asc')->get();

        return response()->json($surfers);
    }

    public function show(string $slug)
    {
        $surfer = Surfer::with('surfboards')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($surfer);
    }

    public function featured()
    {
        $surfers = Surfer::with('surfboards')
            ->where('featured', true)
            ->orderBy('order', 'asc')
            ->get();

        return response()->json($surfers);
    }
}
