<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
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
        $auth = $request->instance()->query('auth');
        if($auth['role'] != "ADMIN"){
            return response('No Permission', 401);
        }
        return $next($request);
    }
}
