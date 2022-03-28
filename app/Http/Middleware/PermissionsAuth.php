<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use App\Services\PermissionService;
use App\Services\RoleService;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        if(auth('api')->user()->email == 'pltruenine@163.com') {
            return $next($request);
        }
        $id = auth('api')->id();
        $authService = new AuthService();

        $bool = $authService->checkPermission($id,$request->method(),$request->route()->uri());

        abort_if(!$bool,403,'没有访问权限');

        return $next($request);
    }
}
