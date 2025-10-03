<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/minimal.css')}}">
    @yield('css')
</head>
<body>
    <header class="header">
        <a href="{{ route('products.index') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="ロゴ画像">
        </a>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>