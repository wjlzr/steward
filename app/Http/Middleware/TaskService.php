<?php

namespace App\Http\Middleware;

use Closure;


class TaskService
{

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $return_data = $next($request);
        return $return_data;

    }


}
