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
        '/register', // TODO: this for DEVELOPMENT TESTING, will be commented out in production
        '/login',    // TODO: this for DEVELOPMENT TESTING, will be commented out in production
    ];
}
