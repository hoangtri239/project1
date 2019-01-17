<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuthToken
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
        if(null === $request->get('token')){
            return response('Not valid token provider.', 401);
        }
        $token = $request->get('token');
        $user = \App\User::where('api_token', $token)->first();        
        if(!$user){
            return response('Not valid token provider.', 401);
        }
        $user = $user->toArray();
        $request->merge(array("auth" => $user));
        return $next($request); //pass checking authen
    }
}
