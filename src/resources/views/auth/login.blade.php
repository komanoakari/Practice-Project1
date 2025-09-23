@extends('layouts.minimal')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css')}}">
@endsection

@section('content')
<div class="login-form">
    <h2 class="login-form-heading">ログイン</h2>

    <div class="login-form-inner">
        <form class="login-form-form" action="{{ route('login.store') }}" method="post">
        @csrf
            <div class="login-form-group">
                <label class="login-form-label" for="email">メールアドレス</label>
                <input class="login-form-input" type="email" name="email" id="email" value="{{ old('email') }}">
                <p class="login-form-error-message">
                    @error('email')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form-group">
                <label class="login-form-label" for="password">パスワード</label>
                <input class="login-form-input" type="password" name="password" id="password">
                <p class="login-form-error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="login-form-btn" type="submit" value="ログインする">
            <a href="{{ route('register') }}" class="register-link">会員登録はこちら</a>
        </form>
    </div>
</div>
@endsection