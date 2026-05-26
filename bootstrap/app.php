<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // 'role' alias removed — use 'has.role' (EnsureRole) which supports multiple roles: has.role:Admin,Analyst
            'buyer'    => \App\Http\Middleware\EnsureBuyerIsAuthenticated::class,
            'has.role' => \App\Http\Middleware\EnsureRole::class,
            'locale'   => \App\Http\Middleware\LocaleMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
        ]);

        $middleware->append([
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\RemoveServerHeader::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
