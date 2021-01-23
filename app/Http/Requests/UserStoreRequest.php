<?php

namespace App\Http\Requests;


class UserStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:5|max:20|unique:users,name',
            'email'=>'required|email|unique:users,email',
            //'avatar'=>'required',
            'password'=>'required|min:6|max:20|confirmed:password_confirmation',
            //'password_confirmation'=>'required|min:6|max:20'
        ];
    }


    public function messages()
    {
        return [
            'name.required' => '用户名不能为空',
            'name.min' => '用户名不能低于5位数',
            'name.max' => '用户名不能大于20位数',
            'name.unique' => '该用户名已存在',
            'email.required' => '邮箱不能为空',
            'email.email' => '不是一个正确的邮箱',
            'email.unique' => '该邮箱已存在',
            'avatar.required' => '头像不能为空',
            'password.required' => '密码不能为空',
            'password.min' => '密码不能低于6位数',
            'password.max' => '密码不能大于20位数'
        ];
    }
}
