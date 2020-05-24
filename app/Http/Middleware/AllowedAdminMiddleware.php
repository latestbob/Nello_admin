<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AllowedAdminMiddleware
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
                $userType == 'agent' || $userType == 'doctor') {
                return $next($request);
            }
            Auth::logout();
            return redirect($userType == 'customer' ? '/login' : '/')
                ->with('error', "Sorry only administrators are allowed to login from this section.");
        }
        return redirect('/login');
    }
}
