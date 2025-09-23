@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
    @if (session('status'))
        <div class="alert-success" style="color:#ff0000; font-size:24px;">
            {{ session('status') }}
        </div>
    @endif

    <div class="purchase-contents">
        <div class="purchase-content">
            <form action="{{ route('purchase.store', ['product' => $product->id]) }}" class="purchase-form-inner" method="post">
                @csrf
                <div class="top-contents">
                    <div class="left-content">
                        <div class="product-basic">
                            <img src="{{ Storage::url($product->image) }}" alt="商品画像" class="product-image">
                            <div class="product-basic-info">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-price">
                                    <span class="price-mark">¥</span> {{ number_format($product->price) }}
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="payment-method-contents">
                            <div class="payment-method-title">支払い方法</div>
                            <div class="payment-options">
                                <div class="select-wrap">
                                    <select name="payment_method" class="payment-option-select" id="payment_method" required>
                                        <option value="" disabled {{ old('payment_method') ? '' : 'selected' }} hidden>選択してください</option>
                                        <option value="コンビニ支払い" {{ old('payment_method') === 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
                                        <option value="カード支払い" {{ old('payment_method') === 'カード支払い'   ? 'selected' : '' }}>カード支払い</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="payment-error-message">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="shipping-section">
                            <div class="shipping-header">
                                <div class="shipping-title">配送先</div>
                                <a href="{{ route('address.edit', ['product' => $product->id]) }}" class="shipping-edit">変更する</a>
                            </div>
                            <div class="shipping-details">
                                <div class="shipping-postal_code">〒 {{ $shipping['shipping_postal_code'] ?: '未設定' }}</div>
                                <div class="shipping-address">
                                    {{ $shipping['shipping_address'] ?: '未設定' }}
                                    @if(!empty($shipping['shipping_building']))
                                        {{ $shipping['shipping_building'] }}
                                    @endif
                                </div>
                                @error('shipping_postal_code')
                                    <div class="shipping-error-message">{{ $message }}</div>
                                @enderror

                            </div>
                            <hr>
                        </div>
                    </div>

                    <div class="right-content">
                        <table class="purchase-summary">
                            <tr>
                                <th>商品代金</th>
                                <td>¥ {{ number_format($product->price) }}</td>
                            </tr>
                            <tr>
                                <th>支払い方法</th>
                                <td id="summary-payment">未選択</td>
                            </tr>
                        </table>
                        <button type="submit" class="purchase-btn">購入する</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script>
    const sel = document.getElementById('payment_method');
    const summary = document.getElementById('summary-payment');
    if (sel && summary) {
        sel.addEventListener('change', () => {
        const text = sel.options[sel.selectedIndex]?.text || '未選択';
        summary.textContent = text;
        });
    }
</script>
@endsection

