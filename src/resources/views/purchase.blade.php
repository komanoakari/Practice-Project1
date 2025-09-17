@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
    <div class="purchase-contents">
        <div class="purchase-content">
            <form action="{{ route('purchase.store', ['product' => $product->id]) }}" class="purchase-form-inner" method="post">
                @csrf
                <div class="top-contents">
                    <div class="left-content">
                        <div class="product-basic">
                            <img src="{{ ($product->image) }}" alt="商品画像" class="product-image">
                            <div class="product-basic-info">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-price">
                                    <span class="price-mark">¥ {{ number_format($product->price) }}</span>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="payment-method-contents">
                            <div class="payment-method-title">支払い方法</div>
                            <div class="payment-options">
                                <div class="select-wrap">
                                    <select name="payment_method" class="payment-option-select" id="payment_method">
                                        <option disabled selected hidden>選択してください</option>
                                        <option value="コンビニ支払い">コンビニ支払い</option>
                                        <option value="カード支払い">カード支払い</option>
                                    </select>
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
                            </div>
                            <hr>

                            <input type="hidden" name="shipping_postal_code" value="{{ $shipping['shipping_postal_code'] }}">
                            <input type="hidden" name="shipping_address" value="{{ $shipping['shipping_address'] }}">
                            <input type="hidden" name="shipping_building" value="{{ $shipping['shipping_building'] }}">
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
@endsection

@section('scripts')
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
