<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>
<body>
    <header class="header">
        <a href="{{ route('products.index') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="ロゴ画像">
        </a>

        <form action="{{ route('search') }}" method="GET" class="header-search" role="search">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="なにをお探しですか？" class="header-search-input">
        </form>

        <nav class="header-nav">
            @auth
                <form action="{{ route('logout') }}" method="POST" class="header-nav-item">
                    @csrf
                    <button type="submit" class="header-btn">ログアウト</button>
                </form>
                <a href="{{ route('profile.show') }}" class="header-nav-item">マイページ</a>
                <a href="{{ route('sell') }}" class="header-listing-link">出品</a>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="header-nav-item">ログイン</a>
                <a href="{{ route('profile.show') }}" class="header-nav-item">マイページ</a>
                <a href="{{ route('sell') }}" class="header-listing-link">出品</a>
            @endguest
        </nav>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const input = document.querySelector('.header-search-input');
                if (!input) return;

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && !e.isComposing) {
                    e.preventDefault();
                    input.form && input.form.submit();
                    }
                });
            });
        </script>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>