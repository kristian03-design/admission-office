<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PreventPublicFormSpam
{
    private const HONEYPOT_FIELDS = [
        'website',
        'homepage',
        'company',
        'url',
        '_hp',
    ];

    private const TIMING_FIELDS = [
        'form_started_at',
        '_form_started_at',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        foreach (self::HONEYPOT_FIELDS as $field) {
            if (trim((string) $request->input($field, '')) !== '') {
                Log::warning('Public form honeypot blocked.', [
                    'route' => optional($request->route())->uri(),
                    'ip' => $request->ip(),
                    'field' => $field,
                ]);

                return $this->tooManyRequests();
            }
        }

        $startedAt = $this->formStartedAt($request);
        if ($startedAt !== null && now()->timestamp - $startedAt < 3) {
            Log::warning('Public form timing blocked.', [
                'route' => optional($request->route())->uri(),
                'ip' => $request->ip(),
            ]);

            return $this->tooManyRequests();
        }

        $duplicateKey = $this->duplicateKey($request);
        if (Cache::has($duplicateKey)) {
            Log::notice('Public form duplicate blocked.', [
                'route' => optional($request->route())->uri(),
                'ip' => $request->ip(),
            ]);

            return $this->tooManyRequests('This request was already received. Please wait before trying again.');
        }

        Cache::put($duplicateKey, true, now()->addMinutes(5));

        return $next($request);
    }

    private function formStartedAt(Request $request): ?int
    {
        foreach (self::TIMING_FIELDS as $field) {
            $value = $request->input($field);
            if (is_numeric($value)) {
                return (int) $value;
            }
        }

        return null;
    }

    private function duplicateKey(Request $request): string
    {
        $payload = $request->except(array_merge(self::HONEYPOT_FIELDS, self::TIMING_FIELDS, [
            '_token',
        ]));

        ksort($payload);

        return 'public-form-duplicate:' . sha1(json_encode([
            optional($request->route())->uri(),
            $request->ip(),
            strtolower((string) ($request->input('email') ?? $request->input('applicant_email') ?? '')),
            $payload,
        ]));
    }

    private function tooManyRequests(string $message = 'Too many requests. Please wait a moment and try again.'): Response
    {
        return response()->json(['message' => $message], 429);
    }
}
