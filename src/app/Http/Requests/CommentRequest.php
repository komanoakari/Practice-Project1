<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => ['required', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '入力してください',
            'body.max' => '255文字以下で入力してください',
        ];
    }
}
