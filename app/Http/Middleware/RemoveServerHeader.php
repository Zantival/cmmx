<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveServerHeader
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
            header_remove('Server');
        }

        $response = $next($request);

        // Try to remove headers that reveal server info. Note: web server may add 'Server' after PHP.
        if ($response->headers->has('Server')) {
            $response->headers->remove('Server');
        }

        if ($response->headers->has('X-Powered-By')) {
            $response->headers->remove('X-Powered-By');
        }

        return $response;
    }
}
