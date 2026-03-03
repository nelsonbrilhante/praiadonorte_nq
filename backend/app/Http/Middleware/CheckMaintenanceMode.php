<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! SiteSetting::isMaintenanceMode()) {
            return $next($request);
        }

        if ($request->user()) {
            return $next($request);
        }

        $locale = $this->detectLocale($request);
        $message = SiteSetting::getMaintenanceMessage();

        return response()->view('maintenance', [
            'locale' => $locale,
            'message' => $message,
        ], 503);
    }

    private function detectLocale(Request $request): string
    {
        $firstSegment = $request->segment(1);

        if (in_array($firstSegment, ['pt', 'en'])) {
            return $firstSegment;
        }

        $acceptLanguage = $request->header('Accept-Language', '');

        if (str_contains($acceptLanguage, 'pt')) {
            return 'pt';
        }

        return 'pt';
    }
}
