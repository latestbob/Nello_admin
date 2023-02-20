<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (($check = Auth::guard($guard)->check()) && Auth::user()->user_type != 'customer') {
            return redirect(RouteServiceProvider::HOME);
        }

        elseif (($check = Auth::guard($guard)->check()) && Auth::user()->email == 'admin@owcappointment.com') {
            return redirect(route('owcadmin'));
        }

        elseif (($check = Auth::guard($guard)->check()) && Auth::user()->email == 'admin@famacare.com') {
            return redirect(route('famacareadmin'));
        }

        if ($check) Auth::logout();

        return $next($request);
    }
}
