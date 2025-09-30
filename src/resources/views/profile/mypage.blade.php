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


<div class="mypage">
    <header class="mypage-header">
        <img src="{{ $profile?->image ? Storage::url($profile->image) : asset('images/placeholder.png') }}" alt="プロフィール画像" class="mypage-header-avatar">

        <h2 class="mypage-header-name">{{ $profile->user_name ?? $user->name ?? 'ゲスト' }}</h2>

        <a href="{{ route('profile.edit', ['back' => 'mypage']) }}" class="mypage-header-link">プロフィールを編集</a>
    </header>

    <nav class="mypage-tabs" role="tablist" aria-label="マイページタブ">
        <button type="button" id="tab-listed" class="mypage-tabs-tab" role="tab" aria-selected="true" aria-controls="panel-listed" tabindex="0">出品した商品</button>
        <button type="button" id="" class="mypage-tabs-tab" role="tab" aria-selected="false" aria-controls="panel-orders" tabindex="-1">購入した商品</button>
    </nav>
    <hr>

    <section class="mypage-panels">
        <div id="panel-listed" class="mypage-panels-panel active" role="tabpanel" aria-labelledby="tab-listed">
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

            <div class="mypage-product-paginate">
            {{ $listedProducts->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>

        <div id="panel-orders" class="mypage-panels-panel" role="tabpanel" aria-labelledby="tab-orders">
            <div class="mypage-products">
                @forelse ($orders as $q)
                    <div class="mypage-product">
                        <a href="{{ url('/item/'.$q->product->id) }}" class="mypage-product-link">
                            <img src="{{ Storage::url($q->product->image) }}" alt="商品画像" class="mypage-product-image">
                            <div class="mypage-product-detail">
                                <p>{{ $q->product->name }}</p>
                                <p class="mypage-product-status">{{ $q->product->is_sold ? 'Sold' : '販売中' }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="mypage-product-content">購入履歴はまだありません</p>
                @endforelse
            </div>

            <div class="mypage-product-paginate">
            {{ $orders->links() }}
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.mypage-tabs-tab');
    const panels = document.querySelectorAll('.mypage-panels-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
        // 全部リセット
        tabs.forEach(t => t.setAttribute('aria-selected', 'false'));
        panels.forEach(p => p.classList.remove('active'));

        // 今のタブをアクティブに
        tab.setAttribute('aria-selected', 'true');
        const targetId = tab.getAttribute('aria-controls');
        document.getElementById(targetId).classList.add('active');
        });
    });
    });
    </script>
</div>
@endsection


