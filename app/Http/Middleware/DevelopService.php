<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Redis as Redis;

class DevelopService
{
    
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = Redis::get('ST_DEV_USER_ID_' . session()->getId());
        if ( !$user) {
            return redirect('/develop/login?redirect_url=' . urlencode($request->url()));
        }
        return $next($request);

    }
    
}
