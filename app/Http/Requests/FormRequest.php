<?php


namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    # 自定义TestRequest的错误响应格式
    # TestRequest.php 修改继承方法
    protected function failedValidation(Validator $validator)
    {
        $message = $validator->getMessageBag()->first();
        //$response = JsonResponse::create(['data' => [], 'code' => 400, 'message' => "warning | $message"],500);
        $response = JsonResponse::fromJsonString(collect(['data' => [], 'code' => 400, 'message' => "$message"]),200);
        throw new HttpResponseException($response);
    }
}
