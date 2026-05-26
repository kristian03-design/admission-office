<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeRequestInput
{
    /**
     * Request fields that should be exempted from sanitization.
     *
     * @var array<int, string>
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        $this->sanitizeArray($input);

        $request->merge($input);

        return $next($request);
    }

    /**
     * Recursively sanitize all string elements in an array.
     *
     * @param  array  $array
     * @return void
     */
    protected function sanitizeArray(array &$array): void
    {
        foreach ($array as $key => &$value) {
            // Skip sensitive password and confirmation fields to preserve complexity characters
            if (in_array($key, $this->except, true)) {
                continue;
            }

            if (is_array($value)) {
                $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                // 1. Eliminate <script> blocks and their content completely
                $cleaned = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value) ?? $value;
                
                // 2. Eliminate <iframe> blocks and their content completely
                $cleaned = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $cleaned) ?? $cleaned;

                // 3. Strip all other HTML tags to prevent HTML Injection and XSS
                $cleaned = strip_tags($cleaned);
                
                // 4. Trim excess whitespace
                $array[$key] = trim($cleaned);
            }
        }
    }
}
