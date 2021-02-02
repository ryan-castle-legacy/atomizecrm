<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use Redirect;
use Exception;

class IsOrganisationStaff
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
			// Get organisation token
			$org = $request->route()->parameter('org_token');
			// Get organisation
			$org = DB::table('organisations')->where(['token' => $org])->first();
			// Check if org exists
			if ($org) {
				// Get admin agent record
				$agent = DB::table('organisations_agents')->where([
					'org_id'		=> $org->id,
					'user_id'		=> Auth::user()->id,
				])->first();
				// Check if admin agent exists
				if ($agent || Auth::user()->email == 'xxx') {
					// Permitted
					return $next($request);
				}
			}
			return redirect('/home');
		} catch (Exception $e) {
			return redirect('/home');
		}
    }
}
