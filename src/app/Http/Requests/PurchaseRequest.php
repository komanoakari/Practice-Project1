<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => ['required'],
            'shipping_postal_code' => ['required'],
            'shipping_address' => ['required'],
            'shipping_building' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'shipping_postal_code.required' => '配送先を指定してください',
            'shipping_postal_code.address' => '配送先を指定してください',
        ];
    }
}
