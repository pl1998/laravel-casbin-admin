<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/8/3
 * Time : 6:03 下午.
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'task_type' => ['required', Rule::in([1, 2, 3])],
            'task_name' => ['required', 'max:40'],
            'textarea' => ['required', 'max:255'],
        ];
        if (3 === $this->input('task_type')) {
            $rules = array_merge($rules, ['email' => 'required|email']);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'task_name.required' => '任务名称不能为空',
            'task_name.max' => '任务名称不能超过40个字',
            'textarea.required' => '脚本内容不能为空',
            'textarea.max' => '脚本内容不能超过255个字符',
            'email.required' => '邮箱不能为空',
            'email.email' => '不是一个可用邮箱',
        ];
    }
}
