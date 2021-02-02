<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/user/create',
        '/user/check-login',
		'/user/reset-password',
		'/user/update-password',
		'/organisation/create',
		'/messenger-instance/create',
		'/messenger-instance/add-contact-information',
		'/messenger-instance/add-message-from-contact',
		'/organisation/domains/create',
    ];
}
