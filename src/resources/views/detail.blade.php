@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
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
        <div class="left-content">
            <div id="list" class="img-content">
        </div>
        <div class="right-content">
            <h2 class="product-name">{{$product->name}}</h2>
            <div class="product-brand">{{$product->brand}}</div>
            <div class="product-price">
                <span class="price-mark">¥</span>{{$product->price}}<span class="price-tax">(税込)</span>
            </div>
            <div class="product-icon">
                <img src="{{ asset('storage/images/41363.png') }}" alt="いいねアイコン" class="product-icon-likes">
                <img src="{{ asset('/images/41363.png)" alt="いいねアイコン" class="product-icon-likes">

            </div>
        </div>
    </div>