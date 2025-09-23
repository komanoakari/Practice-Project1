<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'brand' => ['nullable'],
            'description' => ['required', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png'],
            'condition' => ['required'],
            'price' => ['required', 'integer', 'min:0'],

            'category' => ['required', 'array'],
            'category.*' => ['integer', Rule::exists('categories', 'id')],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '255文字以下で入力してください',
            'image.required' => '商品画像を登録してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '数値で入力してください',
            'price.min' => '0円以上にしてください',
            'category.required' => 'カテゴリーを選択してください',
        ];
    }
}
