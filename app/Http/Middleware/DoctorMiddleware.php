<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DoctorMiddleware
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

            if ($request->user()->user_type == 'doctor') {
                return $next($request);
            }

            return redirect('/')
                ->with('error', "You don't have access to that route.");
        }

        return redirect('/login');
    }
}
