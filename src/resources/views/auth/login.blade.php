@extends('layouts.minimal')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css')}}">
@endsection

@section('content')
<div>
    <h2 class="login-form-heading">ログイン</h2>

    <div class="login-form-inner">
        <form class="login-form-form" action="{{ route('login.store') }}" method="post" novalidate>
        @csrf
            <div class="login-form-group">
                <label class="login-form-label" for="email">メールアドレス</label>
                <input class="login-form-input" type="email" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                    <p class="login-form-error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="login-form-group">
                <label class="login-form-label" for="password">パスワード</label>
                <input class="login-form-input" type="password" name="password" id="password">
                @error('password')
                    <p class="login-form-error-message">{{ $message }}</p>
                @enderror
            </div>
            <input class="login-form-btn" type="submit" value="ログインする">
            <a href="{{ route('register') }}" class="register-link">会員登録はこちら</a>
        </form>
    </div>
</div>
@endsection