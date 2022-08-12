<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2022/1/19
 * Time : 4:48 PM.
 */

namespace App\Http\Requests;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return
            [
                'key' => 'required',
                'captcha' => 'required',
                'email' => 'required|min:2|max:20',
                'password' => 'required|min:6|max:20',
            ];
    }

    public function messages()
    {
        return [
            'key.required' => '参数不合格',
            'email.required' => '邮箱不能为空',
            'email.email' => '不是一个正确的邮箱',
            'password.required' => '密码不能为空',
            'password.min' => '密码不能低于6位',
            'password.max' => '密码不能高于20位',
            'captcha.required' => '验证码不能为空',
            'captcha.min' => '验证码不能为空',
            'key.captcha' => '验证码不合格',
        ];
    }
}
