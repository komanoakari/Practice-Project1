@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css')}}">
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
<div class="edit-form">
    <h2 class="edit-form-heading">プロフィール設定</h2>
    <div class="edit-form-inner">
        <form action="/edit" class="edit-form-form" method="post">
            @csrf
            <div class="edit-form-group">
                <label for="building" class="edit-form-label"></label>
                <input type="file" name="profile_image" id="profile_image" class="edit-form-image" value="{{ old('building') }}">
                <p class="edit-form-error-message">
                    @error('profile_image')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="edit-form-group">
                <label for="name" class="edit-form-label">ユーザー名</label>
                <input type="text" name="name" id="name" class="edit-form-input" value="{{ old('name') }}">
                <p class="edit-form-error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="edit-form-group">
                <label for="postal_code" class="edit-form-label">郵便番号</label>
                <input type="text" name="postal_code" id="postal_code" class="edit-form-input" value="{{ old('postal_code') }}">
                <p class="edit-form-error-message">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="edit-form-group">
                <label for="address" class="edit-form-label">住所</label>
                <input type="text" name="address" id="address" class="edit-form-input" value="{{ old('address') }}">
                <p class="edit-form-error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="edit-form-group">
                <label for="building" class="edit-form-label">建物名</label>
                <input type="text" name="building" id="building" class="edit-form-input" value="{{ old('building') }}">
                <p class="edit-form-error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input type="submit" class="edit-form-btn" value="更新する">
        </form>
    </div>
</div>
@endsection