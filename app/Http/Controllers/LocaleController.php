<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch the application language and redirect back.
     */
    public function switch(string $locale)
    {
        if (in_array($locale, ['en', 'es'])) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
