<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add API routes that don't need CSRF (if any)
        // 'api/*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        // Ensure session token exists (prevents "Page Expired" on first load)
        if (!$request->session()->has('_token')) {
            $request->session()->regenerateToken();
        }

        // Proceed with parent CSRF validation
        return parent::handle($request, $next);
    }

    /**
     * Determine if the session and input CSRF tokens match.
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);

        // Allow missing token for specific safe methods (like GET, OPTIONS)
        if (in_array($request->method(), ['HEAD', 'GET', 'OPTIONS'])) {
            return true;
        }

        return is_string($request->session()->token()) &&
               is_string($token) &&
               hash_equals($request->session()->token(), $token);
    }

    /**
     * Extract CSRF token from request (handles Blade + AJAX).
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (! $token && $header = $request->header('X-XSRF-TOKEN')) {
            try {
                $token = $this->encrypter->decrypt($header, static::serialized());
            } catch (\Exception $e) {
                // Token decrypt failed, fallback gracefully
                $token = null;
            }
        }

        return $token;
    }
}
