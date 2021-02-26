<?php

namespace App\Http\Requests;


class UserUpdateRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|min:2|max:20',
            'email'=>'required|email',
           // 'avatar'=>'required|email',
            'confirm_password'=>'min:6|max:20',
            'password'=>'min:6|max:20|confirmed:confirm_password',
        ];
    }

    public function messages()
    {
        return[
            'name.required'=>'用户名不能为空',
            'name.min'=>'用户名长度不能小于6位',
            'name.max'=>'用户名长度不能大于20位',
            'email.required'=>'邮箱不能为空',
            'email.email'=>'不是一个合格的邮箱',
            'password.required'=>'密码不能为空',
            'password.min'=>'密码长度不能小于6位',
            'password.max'=>'密码长度不能大于20位',
            'password.confirm_password'=>'密码与重复密码长度',
            'confirm_password.required'=>'密码不能为空',
            'confirm_password.min'=>'重复密码长度不能小于6位',
            'confirm_password.max'=>'重复密码长度不能大于20位',
        ];
    }
}
