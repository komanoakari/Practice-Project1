<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation():void
    {
        $user = Auth::user();
        $profile = $user && $user->profile ? $user->profile : null;

        $profileShipping = [
            'shipping_postal_code' => $profile ? ($profile->postal_code ?? '') : '',
            'shipping_address' => $profile ? ($profile->address ?? '') : '',
            'shipping_building' => $profile ? ($profile->building ?? '') : '',
        ];

        $shipping = session('checkout.shipping', $profileShipping);

        $this->merge($shipping);
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
            'shipping_postal_code.required' => '郵便番号を入力してください',
            'shipping_postal_code.address' => '配送先を指定してください',
        ];
    }
}
