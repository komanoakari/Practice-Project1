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
    @if (session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="top-contents">
        <div class="left-content">
            <img src="{{ asset($product->image) }}" alt="商品画像" class="img-content">
        </div>
        <div class="right-content">
            <div class="product-name">{{$product->name}}</div>
            <div class="product-brand">{{$product->brand}}</div>
            <div class="product-price">
                <span class="price-mark">¥</span>{{$product->price}}<span class="price-tax"> (税込)</span>
            </div>

            <div class="product-icons">
                <div class="product-icon">
                    <button id="like-btn" type="button"
                            data-liked="{{ $liked ? 'true' : 'false' }}"
                            data-url-like="{{ route('mylist.store', $product) }}"
                            data-url-unlike="{{ route('mylist.destroy', $product) }}"
                            data-url-likes="{{ route('likes.count', $product) }}"
                            @guest data-requires-login="true" @endguest
                            class="like-btn {{ $liked ? 'is-liked' : '' }}">
                            <svg class="icon star-icon" viewBox="0 0 24 24" fill="none">
                            <path d="M11.2691 4.41115C11.5006 3.89177 11.6164 3.63208 11.7776 3.55211C11.9176 3.48263 12.082 3.48263 12.222 3.55211C12.3832 3.63208 12.499 3.89177 12.7305 4.41115L14.5745 8.54808C14.643 8.70162 14.6772 8.77839 14.7302 8.83718C14.777 8.8892 14.8343 8.93081 14.8982 8.95929C14.9705 8.99149 15.0541 9.00031 15.2213 9.01795L19.7256 9.49336C20.2911 9.55304 20.5738 9.58288 20.6997 9.71147C20.809 9.82316 20.8598 9.97956 20.837 10.1342C20.8108 10.3122 20.5996 10.5025 20.1772 10.8832L16.8125 13.9154C16.6877 14.0279 16.6252 14.0842 16.5857 14.1527C16.5507 14.2134 16.5288 14.2807 16.5215 14.3503C16.5132 14.429 16.5306 14.5112 16.5655 14.6757L17.5053 19.1064C17.6233 19.6627 17.6823 19.9408 17.5989 20.1002C17.5264 20.2388 17.3934 20.3354 17.2393 20.3615C17.0619 20.3915 16.8156 20.2495 16.323 19.9654L12.3995 17.7024C12.2539 17.6184 12.1811 17.5765 12.1037 17.56C12.0352 17.5455 11.9644 17.5455 11.8959 17.56C11.8185 17.5765 11.7457 17.6184 11.6001 17.7024L7.67662 19.9654C7.18404 20.2495 6.93775 20.3915 6.76034 20.3615C6.60623 20.3354 6.47319 20.2388 6.40075 20.1002C6.31736 19.9408 6.37635 19.6627 6.49434 19.1064L7.4341 14.6757C7.46898 14.5112 7.48642 14.429 7.47814 14.3503C7.47081 14.2807 7.44894 14.2134 7.41394 14.1527C7.37439 14.0842 7.31195 14.0279 7.18708 13.9154L3.82246 10.8832C3.40005 10.5025 3.18884 10.3122 3.16258 10.1342C3.13978 9.97956 3.19059 9.82316 3.29993 9.71147C3.42581 9.58288 3.70856 9.55304 4.27406 9.49336L8.77835 9.01795C8.94553 9.00031 9.02911 8.99149 9.10139 8.95929C9.16534 8.93081 9.2226 8.8892 9.26946 8.83718C9.32241 8.77839 9.35663 8.70162 9.42508 8.54808L11.2691 4.41115Z" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="likes-count">{{ $product->likes_count ?? 0 }}</span>
                    </button>
                </div>

                <div class="product-icon">
                    <button id="comment-btn" class="comment-btn" aria-label="コメント">
                        <svg class="icon comment-icon" viewBox="0 0 42 42" aria-hidden="true">
                        <g transform="translate(-100,-255)">
                            <path d="M116,281 C114.832,281 113.704,280.864 112.62,280.633 L107.912,283.463 L107.975,278.824 C104.366,276.654 102,273.066 102,269 C102,262.373 108.268,257 116,257 C123.732,257 130,262.373 130,269 C130,275.628 123.732,281 116,281 L116,281 Z M116,255 C107.164,255 100,261.269 100,269 C100,273.419 102.345,277.354 106,279.919 L106,287 L113.009,282.747 C113.979,282.907 114.977,283 116,283 C124.836,283 132,276.732 132,269 C132,261.269 124.836,255 116,255 L116,255 Z" fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
                        </g>
                        </svg>
                        <span class="comments-count">{{ $product->comments_count ?? 0 }}</span>
                    </button>
                </div>
            </div>

            <div class="purchase-area">
                @if ( $product->is_sold)
                    <div class="sold-out">売り切れ</div>
                @else
                    @auth
                        <form action="{{ route('purchase.store', $product) }}" method="post">
                            @csrf
                            <button class="purchase-btn" type="submit">購入手続きへ</button>
                        </form>
                    @else
                        <a href="{{ route('login', ['redirect' => request()->getRequestUri()]) }}" class="purchase-btn is-guest">購入手続きへ</a>
                    @endauth
                @endif
            </div>

            <div class="product-detail">
                <h3>商品説明</h3>
                    <div class="product-description">{{$product->description}}</div>
            </div>

            <div class="product-detail">
                <h3>商品の情報</h3>
                    <div class="product-category">
                        <div class="product-category-title">カテゴリー</div>
                        <div class="product-category-tags">
                            @foreach ($product->categories as $cat)
                                <span class="category-tag">{{$cat->name}}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="product-condition">
                        <div class="product-condition-title">商品の状態</div>
                        <div class="product-condition-result">{{$product->condition}}</div>
                    </div>
            </div>


            <div class="comment-contents">
                <h3 class="comment-title">コメント({{ $product->comments_count }})</h3>
                @foreach ($product->comments as $c)
                    <div class="comment">
                        <div class="comment-header">
                            <div id="preview" class="avatar-preview">
                                @if($c->user->profile && $c->user->profile->image)
                                    <img src="{{ Storage::url($c->user->profile->image) }}" alt="{{ $c->user->name }}" class="comment-avatar">
                                @else
                                    <div class="comment-avatar-placeholder"></div>
                                @endif
                            </div>
                            <span class="comment-username">{{ $c->user->name }}</span>
                        </div>
                        <div class="comment-body">{{ $c->body }}</div>
                    </div>
                @endforeach

                <div class="comment-area" id="comment">商品へのコメント</div>
                    <form action="{{ route('comment.store', $product) }}" method="post">
                        @csrf
                        <textarea name="body"></textarea>
                        @error('body')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        @auth
                            <button type="submit" class="comment-submit-btn">コメントを送信する</button>
                        @else
                            <a href="{{ route('login', ['redirect' => request()->getRequestUri().'#comment']) }}" class="comment-submit-btn is-guest">コメントを送信する</a>
                        @endauth
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('like-btn');
    if (!btn) return;

    const countSpan = btn.querySelector('.likes-count');
    const requiresLogin = btn.dataset.requiresLogin === 'true';

    btn.addEventListener('click', async () => {
        console.log('like clicked');

        // 未ログインはフェッチせずログイン画面へ
        if (requiresLogin) {
        const currentUrl = window.location.pathname + window.location.search + window.location.hash;
        window.location.href = "{{ route('login') }}" + "?redirect=" + encodeURIComponent(currentUrl);
        return;
        }

        const liked  = btn.dataset.liked === 'true';
        const url    = liked ? btn.dataset.urlUnlike : btn.dataset.urlLike;
        const method = liked ? 'DELETE' : 'POST';

        try {
        const res = await fetch(url, {
            method,
            headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
            },
            credentials: 'same-origin',
        });

        if (res.status === 401) {
        const currentUrl = window.location.pathname + window.location.search + window.location.hash;
        window.location.href = "{{ route('login') }}" + "?redirect=" + encodeURIComponent(currentUrl);
        return;
}

        const ct = res.headers.get('content-type') || '';
        if (!res.ok || !ct.includes('application/json')) {
            console.error('Unexpected response', res.status, ct);
            alert('処理に失敗しました。通信状況を確認してください。');
            return;
        }

        const data = await res.json();
        // 数字と見た目をサーバーの真値で反映
        if (typeof data.likes_count === 'number') {
            countSpan.textContent = String(data.likes_count);
        }
        if (typeof data.liked === 'boolean') {
            btn.dataset.liked = data.liked ? 'true' : 'false';
            btn.classList.toggle('is-liked', data.liked);
        }
        } catch (e) {
        console.error(e);
        alert('通信に失敗しました。ネットワークをご確認ください。');
        }
    });

    // （任意）リアルタイム更新：数秒ごとに最新数を取得して上書き
    const urlLikes = btn.dataset.urlLikes;
    if (urlLikes) {
        const refreshLikes = async () => {
        try {
            const res = await fetch(urlLikes, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
            });
            if (!res.ok) return;
            const data = await res.json();
            if (typeof data.likes_count === 'number') {
            countSpan.textContent = String(data.likes_count);
            }
            // ログイン中ならliked状態も同期（未ログインはfalse固定）
            if (!requiresLogin && typeof data.liked === 'boolean') {
            btn.dataset.liked = data.liked ? 'true' : 'false';
            btn.classList.toggle('is-liked', data.liked);
            }
        } catch (_) {}
        };
        const POLL_MS = 8000;
        setInterval(refreshLikes, POLL_MS);
        document.addEventListener('visibilitychange', () => { if (!document.hidden) refreshLikes(); });
        refreshLikes(); // 初回
    }
    });
</script>
@endsection
