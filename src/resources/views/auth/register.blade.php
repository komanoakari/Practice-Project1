@extends('layouts.minimal')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div>
    <h2 class="register-form-heading">会員登録</h2>
    <div class="register-form-inner">
        <form action="/register" class="register-form-form" method="post">
            @csrf
            <div class="register-form-group">
                <label for="name" class="register-form-label">ユーザー名</label>
                <input type="text" name="name" id="name" class="register-form-input" value="{{ old('name') }}">
                <p class="register-form-error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form-group">
                <label for="email" class="register-form-label">メールアドレス</label>
                <input type="mail" name="email" id="email" class="register-form-input" value="{{ old('email') }}">
                <p class="register-form-error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form-group">
                <label for="password" class="register-form-label">パスワード</label>
                <input type="password" name="password" id="password" class="register-form-input">
                <p class="register-form-error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form-group">
                <label for="password_confirmation" class="register-form-label">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="register-form-input">
                <p class="register-form-error-message">
                    @error('password_confirmation')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input type="submit" class="register-form-btn" value="登録する">
            <a href="/login" class="login-link">ログインはこちら</a>
        </form>
    </div>
</div>
@endsection