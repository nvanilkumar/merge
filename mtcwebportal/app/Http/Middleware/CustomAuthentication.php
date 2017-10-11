<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * To check the login user related session 
 */
class CustomAuthentication
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

        $user_id = session('user_id');

        if (!$user_id) {
            return redirect('/staff/login');
        }
        
        return $next($request);
    }

}
