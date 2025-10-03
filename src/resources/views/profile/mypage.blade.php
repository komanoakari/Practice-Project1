@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css')}}">
@endsection

@section('content')
    @if (session('status'))
        <div class="alert-success" style="font-size:24px;">
            {{ session('status') }}
        </div>
    @endif

    @php
        $active = request('page', 'sell');
    @endphp

    <div class="mypage">
        <header class="mypage-header">
            <img src="{{ $profile?->image ? Storage::url($profile->image) : asset('images/placeholder.png') }}" class="mypage-header-avatar">

            <h2 class="mypage-header-name">{{ $profile->user_name ?? $user->name ?? 'ゲスト' }}</h2>

            <a href="{{ route('profile.edit', ['back' => 'mypage']) }}" class="mypage-header-link">プロフィールを編集</a>
        </header>

        <nav class="mypage-tabs">
            <a href="{{ route('profile.show', ['page' => 'sell']) }}" class="mypage-tabs-tab {{ $active === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('profile.show', ['page' => 'buy']) }}" class="mypage-tabs-tab {{ $active === 'buy' ? 'active' : '' }}">購入した商品</a>
        </nav>
        <hr>

        @if ($active === 'sell')
            <section class="mypage-panels">
                <div id="panel-listed" class="mypage-panels-panel {{ $active === 'sell' ? 'active' : '' }}">
                    <div class="mypage-products">
                        @forelse ($listedProducts as $p)
                            <div class="mypage-product">
                                <a href="{{ url('/item/'.$p->id) }}" class="mypage-product-link">
                                    <img src="{{ Storage::url($p->image) }}" alt="商品画像" class="mypage-product-image">
                                    <div class="mypage-product-detail">
                                        <p>{{ $p->name }}</p>
                                        <p class="mypage-product-status">{{ $p->is_sold ? 'Sold' : '販売中' }}</p>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p class="mypage-product-content">出品はまだありません</p>
                        @endforelse
                    </div>
                </div>
            </section>

        @elseif ($active === 'buy')
            <section class="mypage-panels">
                <div id="panel-orders" class="mypage-panels-panel {{ $active === 'buy' ? 'active' : '' }}">
                    <div class="mypage-products">
                        @forelse ($orders as $order)
                            <div class="mypage-product">
                                <a href="{{ url('/item/'.$order->product->id) }}" class="mypage-product-link">
                                    <img src="{{ Storage::url($order->product->image) }}" alt="商品画像" class="mypage-product-image">
                                    <div class="mypage-product-detail">
                                        <p>{{ $order->product->name }}</p>
                                        <p class="mypage-product-status">{{ $order->product->is_sold ? 'Sold' : '販売中' }}</p>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p class="mypage-product-content">購入履歴はまだありません</p>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection


