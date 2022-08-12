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
            'name' => 'required|min:2|max:20',
            'avatar' => 'required',
            'password' => 'min:6|max:20',
            'confirm_password' => 'min:6|max:20',
            'new_password' => 'min:6|max:20|confirmed:confirm_password',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名不能为空',
            'name.min' => '用户名长度不能小于6位',
            'name.max' => '用户名长度不能大于20位',
            'new_password.min' => '密码长度不能小于6位',
            'new_password.max' => '密码长度不能大于20位',
            'password.confirm_password' => '新密码与重复密码不一致',
            'password.min' => '新密码长度不能小于6位',
            'password.max' => '新密码长度不能大于20位',
        ];
    }
}
