<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Sessiom;
use Illuminate\Support\Facades\Auth;
use Closure;
use Redirect;
use Session;
use DB;

class CheckSingleSession {

    public function handle($request, Closure $next) {
        // Get last session
        $lastSession = Auth::user()->last_session;
        // Check if last_session does not match the current session ID
        if ($lastSession !== Session::getId()) {
            // Log user out
            Auth::logout();
            // Redirect to the login
            return Redirect::route('userAccounts-login')->with('info', 'You have been logged out.');
        }
        // Continue
        return $next($request);
    }

}
