<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Terapkan HTTP Security Headers pada setiap response web.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        
        if (app()->environment('local')) {
            $response->headers->set('Cross-Origin-Opener-Policy', 'unsafe-none');
        } else {
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        }
        
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');
        $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://apis.google.com https://*.googleapis.com https://*.firebaseapp.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data: https:; connect-src 'self' https://www.google.com https://www.gstatic.com https://securetoken.googleapis.com https://identitytoolkit.googleapis.com https://apis.google.com https://*.googleapis.com https://*.firebaseapp.com https://*.firebaseio.com; frame-src 'self' https://www.google.com https://*.firebaseapp.com https://accounts.google.com";

        if (app()->environment('local')) {
            $csp = str_replace("script-src ", "script-src http://localhost:5173 http://127.0.0.1:5173 ", $csp);
            $csp = str_replace("style-src ", "style-src http://localhost:5173 http://127.0.0.1:5173 ", $csp);
            $csp = str_replace("connect-src ", "connect-src http://localhost:5173 ws://localhost:5173 ws://127.0.0.1:5173 ", $csp);
        }

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
