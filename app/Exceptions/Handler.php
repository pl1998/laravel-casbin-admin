<?php

namespace App\Exceptions;

use App\Enum\MessageCode;
use App\Traits\ResponseApi;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseApi;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if(Str::lower($request->segment(1)) === 'api'){
            if ($exception instanceof ValidationException) {
                return $this->fail($exception->validator->errors()->first());
            } elseif ($exception instanceof ModelNotFoundException) {
                return $this->fail("一不小心数据走丢了～～～",MessageCode::DATA_ERROR,[],MessageCode::HTTP_OK);
            } else if ($exception instanceof NotFoundHttpException) {
                return $this->fail('路由未找到',MessageCode::ROUTE_EXITS,[],$exception->getStatusCode());
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->fail('请求方法不存在',MessageCode::FUNCTION_EXITS,[]);
            } else if ($exception instanceof UnauthorizedHttpException) { //这个在jwt.auth 中间件中抛出
                return $this->fail('无效的访问令牌',MessageCode::PERMISSION_EXITS,null,MessageCode::HTTP_PERMISSION);
            } elseif ($exception instanceof AuthenticationException) { //这个异常在 auth:api 中间件中抛出
                return $this->fail('无效的访问令牌',MessageCode::PERMISSION_EXITS,null,MessageCode::HTTP_PERMISSION);
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException &&
                $exception->getStatusCode() == MessageCode::HTTP_REFUSED){
                return $this->fail('没有访问权限，请联系管理员',MessageCode::PERMISSION_EXITS,null,$exception->getStatusCode());
            }
           return $this->fail($exception->getMessage().' '.$exception->getFile(). ' '.$exception->getLine(),MessageCode::CODE_ERROR,null);
        }
        return parent::render($request, $exception);
    }
}
