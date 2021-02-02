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
		'widgets/messenger',
		'widgets/submit-contact-data',
		'widgets/send-message-from-contact',
		'dashboard/organisations/connected-websites',
    ];
}
