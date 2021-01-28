<?php

namespace App\Http\Requests;


class RoleStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|min:2|max:20',
            'node'=>'required',
           // 'status'=>'required|boolean',
            'description'=>'required',
        ];
    }

    /**
     * 错误信息
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'name.required' => '角色名不能为空',
            'name.min'=>'角色名长度不能低于2位',
            'name.max'=>'角色名长度不能高于20位',
            'permissions.required'=>'权限不能为空',
//            'status.required'=>'状态不能为空',
//            'status.boolean'=>'状态应该是个boolean值',
            'description.required'=>'角色描述不能为空',
        ];
    }
}
