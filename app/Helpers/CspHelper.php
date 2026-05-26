<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;

class CspHelper
{
    /**
     * Get the current CSP nonce for inline scripts/styles
     */
    public static function nonce(): string
    {
        return Request::attributes->get('csp_nonce', '');
    }

    /**
     * Get the nonce attribute string for use in script/style tags
     */
    public static function nonceAttr(): string
    {
        $nonce = self::nonce();
        return $nonce ? "nonce=\"{$nonce}\"" : '';
    }
}
