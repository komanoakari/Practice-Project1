<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
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

    public function rules(): array
    {
        return [
            'payment_method' => ['required'],
            'shipping_postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address' => ['required', 'string'],
            'shipping_building' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'shipping_postal_code.required' => '郵便番号を入力してください',
            'shipping_postal_code.regex'    => '郵便番号は「123-4567」の形式で入力してください',
            'shipping_address.required' => '配送先を指定してください',
        ];
    }
}
