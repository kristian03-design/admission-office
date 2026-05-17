<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CachePublicResponseHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && $response->isSuccessful()) {
            $seconds = max(60, (int) env('PUBLIC_HTTP_CACHE_TTL', 300));
            $response->headers->set('Cache-Control', "public, max-age={$seconds}, stale-while-revalidate={$seconds}");
        }

        return $response;
    }
}
