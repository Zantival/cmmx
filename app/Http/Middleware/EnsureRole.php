<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // Support both comma-joined and separate args: has.role:Admin,Analyst
        $allowed = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $rr) {
                $allowed[] = trim($rr);
            }
        }

        if (!in_array($userRole, $allowed)) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}
