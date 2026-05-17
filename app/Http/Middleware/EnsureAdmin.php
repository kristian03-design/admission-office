<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            Log::warning('Forbidden admin access attempt.', [
                'path' => $request->path(),
                'ip' => $request->ip(),
                'user_id' => $user?->id,
            ]);

            abort(403, 'This action requires an administrator account.');
        }

        return $next($request);
    }
}
