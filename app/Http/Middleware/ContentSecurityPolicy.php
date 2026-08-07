<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    // ponytail: removed $noCOOPRoutes since COOP is now unconditionally set.

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Build CSP string
        $cspConfig = config('csp.policy');
        $directives = [];
        foreach ($cspConfig as $directive => $sources) {
            $directives[] = $directive . ' ' . implode(' ', $sources);
        }
        $response->headers->set('Content-Security-Policy', implode('; ', $directives));

        // ponytail: unconditionally set COOP to allow Firebase popups. No route whitelist needed.
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');

        return $response;
    }
}
