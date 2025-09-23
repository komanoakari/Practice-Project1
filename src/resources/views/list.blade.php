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

    <div class="products">
        <nav class="products-tabs" role="tablist" aria-label="商品一覧タブ">
            <button type="button" id="tab-listed" class="products-tabs-tab" role="tab" aria-selected="true" aria-controls="panel-listed">おすすめ</button>
            <button type="button" id="tab-mylist" class="products-tabs-tab" role="tab" aria-selected="false" aria-controls="panel-mylist">マイリスト</button>
        </nav>

        <hr>

        <section class="products-panels">
            <div id="panel-listed" class="product-panel active" role="tabpanel" aria-labelledby="tab-listed">
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

                <div class="products-pagination">
                    {{ $products->links('pagination::simple-bootstrap-4') }}
                </div>
            </div>

            @auth
            <div id="panel-mylist" class="product-panel" role="tabpanel" aria-labelledby="tab-mylist">
                <div class="products-content">
                    @forelse ($mylistedProducts as $myproduct)
                        <div class="product-card">
                            <a href="{{ route('products.show', $myproduct) }}" class="product-card-link">
                                <img src="{{ Storage::url($myproduct->image) }}" alt="商品画像" class="product-card-image">
                                <div class="product-card-detail">
                                    <p>{{ $myproduct->name }}</p>
                                    <p class="product-card-status" aria-label="{{ $myproduct->is_sold ? '売り切れ' : '販売中' }}">{{ $myproduct->is_sold ? 'Sold' : '販売中' }}</p>
                                </div>
                            </a>
                        </div>
                    @empty
                        <p class="products-empty">マイリストはまだありません</p>
                    @endforelse
                </div>

                <div class="products-pagination">
                {{ $mylistedProducts->links('pagination::simple-bootstrap-4') }}
                </div>
            </div>
            @endauth

            @guest
            <div id="panel-mylist" class="product-panel" role="tabpanel" aria-labelledby="tab-mylist">
                <p class="product-login">マイリストを見るにはログインしてください</p>
                <a href="{{ route('login') }}" class="product-login-link">ログインはこちら</a>
            </div>
            @endguest
        </section>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.products-tabs-tab');   
        const panels = document.querySelectorAll('.product-panel');    

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {

                tabs.forEach(t => t.setAttribute('aria-selected', 'false'));
                panels.forEach(p => p.classList.remove('active'));

                tab.setAttribute('aria-selected', 'true');
                const targetId = tab.getAttribute('aria-controls');
                const targetPanel = document.getElementById(targetId);
                if (targetPanel) {
                    targetPanel.classList.add('active');
                }
            });
        });
    });
    </script>
@endsection
