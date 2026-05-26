<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share CSP nonce with all views
        View::composer('*', function ($view) {
            $view->with('cspNonce', request()->attributes->get('csp_nonce', ''));
        });
    }
}
