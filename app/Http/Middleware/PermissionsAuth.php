<?php

namespace App\Http\Middleware;

use Closure;

class PermissionsAuth
{
    /**
     * 权限控制中间件
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
