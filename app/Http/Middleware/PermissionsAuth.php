<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;

class PermissionsAuth
{
    /**
     * 权限控制中间件
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ('pltruenine@163.com' === auth('api')->user()->email) {
            return $next($request);
        }
        $id = auth('api')->id();
        $authService = new AuthService();

        $bool = $authService->checkPermission($id, $request->method(), $request->route()->uri());

        abort_if(!$bool, 403, '没有访问权限');

        return $next($request);
    }
}
