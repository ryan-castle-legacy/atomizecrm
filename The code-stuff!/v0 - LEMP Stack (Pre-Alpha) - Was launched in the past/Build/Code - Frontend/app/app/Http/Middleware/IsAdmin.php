<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;
use Exception;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		try {
			if (Auth::user()->email == 'xxx') {
		        return $next($request);
			}
			throw new Exception('Not admin user');
		} catch (Exception $e) {
			return Redirect::route('home');
		}
    }
}
