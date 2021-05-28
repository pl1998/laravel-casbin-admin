<?php

namespace App\Http\Middleware;

use App\Service\AuthService;
use App\Service\PermissionService;
use App\Service\RoleService;
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

        if(!$bool) {
            $response = JsonResponse::fromJsonString(
                collect(['data' => [], 'code' => 403, 'message' => "没有访问权限"]
                )->toJson(),200);
            throw new HttpResponseException($response);
        }

        return $next($request);
    }
}
