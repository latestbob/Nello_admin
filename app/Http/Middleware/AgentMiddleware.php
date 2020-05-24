<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AgentMiddleware
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
            if (($userType = $request->user()->user_type) == 'agent') {
                return $next($request);
            }
            if ($userType == 'customer' || $userType == 'rider') Auth::logout();
            return redirect($userType == 'customer' ? '/login' : '/')
                ->with('error', $userType == 'customer' ?
                    "You don't have access to that route, login and try again." :
                    "You don't have access to that route.");
        }
        return redirect('/login');
    }
}
