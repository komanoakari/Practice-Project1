@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css')}}">
@endsection

@section('link')
<form action="{{ route('logout') }}" method="POST" class="logout-form">
    @csrf
    <button class="header-link-logout" type="submit">ログアウト</button>
</form>

<a class="header-link-mypage" href="/mypage">マイページ</a>
<a class="header-link-sell" href="/sell">出品</a>
@endsection

@section('content')
<div class="all-contents">
    <div class="tabs">
        <a href="{{ route('products.index', ['tab' => 'recommended']) }}" class="tab-link {{ ($tab??'recommended') === 'recommended' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('products.index', ['tab' => 'mylist']) }}" class="tab-link-mylist {{ ($tab??'recommended') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
    <hr>
    <div class="product-contents">
        @foreach ($products as $product)
        <div class="product-content">
            <a href="/products/detail/{{$product->id}}" class="product-link"></a>
            <img src="{{ asset($product->image) }}" alt="商品画像" class="img-content">
            <div class="detail-content">
                <p>{{$product->name}}</p>
                <p class="sales-status">{{ $product->is_sold ? 'Sold' : '販売中' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
