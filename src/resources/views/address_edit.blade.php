@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address_edit.css') }}">
@endsection

@section('content')
<div class="address-edit-contents">
    <h2 class="address-edit-heading">住所の変更</h2>
    <div class="address-edit-content">
        <form action="{{ route('address.update', ['product' => $product->id]) }}" class="address-edit-form" method="post">
            @csrf
            @method('PUT')
            <div class="address-edit-group">
                <label for="shipping_postal_code" class="address-edit-label">郵便番号</label>
                <input type="text" name="shipping_postal_code" id="shipping_postal_code" class="address-edit-input">
                @error('shipping_postal_code')
                    <p class="address-edit-error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="address-edit-group">
                <label for="shipping_address" class="address-edit-label">住所</label>
                <input type="text" name="shipping_address" id="shipping_address" class="address-edit-input">
                @error('shipping_address')
                    <p class="address-edit-error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="address-edit-group">
                <label for="shipping_building" class="address-edit-label">建物名</label>
                <input type="text" name="shipping_building" id="shipping_building" class="address-edit-input">
                @error('shipping_building')
                    <p class="address-edit-error-message">{{ $message }}</p>
                @enderror
            </div>
            <input type="submit" class="address-edit-btn" value="更新する">
        </form>
    </div>
</div>
@endsection