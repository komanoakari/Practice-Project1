@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css')}}">
@endsection

@section('content')
    @if (session('status'))
        <div class="alert-success" style="padding:5px; font-size:20px;">
            {{ session('status') }}
        </div>
    @endif

    @php
        $active = request('tab', 'recommended');
        $q = request('q');
    @endphp

    <div class="products">
        <nav class="products-tabs">
            <a href="{{ $q ? route('search', ['q' => $q]) : route('products.index') }}" class="products-tabs-tab {{ $active === 'recommended' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ $q ? route('search', ['q' => $q, 'tab' => 'mylist']) : route('products.index', ['tab' => 'mylist']) }}" class="products-tabs-tab {{ $active === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </nav>
        <hr>

        @if ($active === 'recommended')
            <section class="products-panels">
                <div id="panel-listed" class="product-panel {{ $active === 'recommended' ? 'active' : '' }}">
                    <div class="products-content">
                        @foreach ($products as $product)
                            <div class="product-card">
                                <a href="{{ route('products.show', $product) }}" class="product-card-link">
                                    <img src="{{ Storage::url($product->image) }}" alt="商品画像" class="product-card-image">
                                    <div class="product-card-detail">
                                        <p>{{ $product->name }}</p>
                                        <p class="product-card-status">{{ $product->is_sold ? 'Sold' : '販売中' }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif ($active === 'mylist')
            <section class="products-panels">
                @auth
                    <div id="panel-mylist" class="product-panel {{ $active === 'mylist' ? 'active' : '' }}">
                        <div class="products-content">
                            @forelse ($mylistedProducts as $myproduct)
                                <div class="product-card">
                                    <a href="{{ route('products.show', $myproduct) }}" class="product-card-link">
                                        <img src="{{ Storage::url($myproduct->image) }}" alt="商品画像" class="product-card-image">
                                        <div class="product-card-detail">
                                            <p>{{ $myproduct->name }}</p>
                                            <p class="product-card-status">{{ $myproduct->is_sold ? 'Sold' : '販売中' }}</p>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <p class="products-empty">マイリストはまだありません</p>
                            @endforelse
                        </div>
                    </div>
                @endauth

                @guest
                <div id="panel-mylist" class="product-panel {{ $active === 'mylist' ? 'active' : '' }}">
                    <p class="product-login">マイリストを見るにはログインしてください</p>
                    <a href="{{ route('login') }}" class="product-login-link">ログインはこちら</a>
                </div>
                @endguest
            </section>
        @endif
    </div>
@endsection
