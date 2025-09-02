<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>
<body>
    <div class="app">
        <header class="header">
            <img src="{{ asset('images/logo.svg') }}" alt="ロゴ">
            @yield('link')
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
    @yield('scripts')
</body>
</html>