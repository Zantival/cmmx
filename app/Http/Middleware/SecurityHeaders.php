<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate a unique nonce for this request
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);

        $response = $next($request);

        // Remove server-revealing header if present
        if ($response->headers->has('X-Powered-By')) {
            $response->headers->remove('X-Powered-By');
        }

        // Basic security headers to mitigate common issues flagged by scanners
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');

        // Content Security Policy - strict without unsafe-inline/unsafe-eval
        // Use nonce for any inline scripts/styles needed
        $csp = "default-src 'self'; "
            . "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net; "
            . "style-src 'self' 'nonce-{$nonce}' https://fonts.googleapis.com https://cdn.jsdelivr.net https://fonts.bunny.net; "
            . "img-src 'self' data: https:; "
            . "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:; "
            . "connect-src 'self'; "
            . "frame-ancestors 'self'; "
            . "object-src 'none'; "
            . "base-uri 'self'; "
            . "form-action 'self';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        // Only add HSTS when request is secure (avoid breaking local dev)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        }

        return $response;
    }
}
