<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class PermissionStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        if (1 === $request->status) {
            return [
                'name' => 'required|min:2',
                'icon' => 'required',
                'path' => 'required',
                'status' => 'required|boolean',
                'method' => 'required',
                'p_id' => 'required',
                'hidden' => 'required',
            ];
        }

        return [
            'name' => 'required|min:2',
            'url' => 'required',
            'method' => 'required',
            'p_id' => 'required',
            'hidden' => 'required',
        ];
    }
}
