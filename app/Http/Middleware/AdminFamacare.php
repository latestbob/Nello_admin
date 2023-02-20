<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class AdminFamacare
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
        if (Auth::check()) {
            if (($userType = $request->user()->user_type) == 'admin' ||
            ($userType = $request->user()->email) == 'admin@famacare.com') {
                return $next($request);
            }
            
        }
        return redirect('/login');
    }
}
