@extends('layouts.minimal')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-notice.css') }}">
@endsection

@section('content')
<div class="verification-content">
    @if (session('status'))
        <p class="verify-status">{{ session('status') }}</p>
    @endif

    <p>登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>

    <a href="http://localhost:8025" class="btn" target="_blank" rel="noopener">認証はこちらから</a>

    <form action="{{ route('verification.send') }}" class="verify-resend-form" method="post">
        @csrf
        <button type="submit" class="verify-btn">認証メールを再送する</button>
    </form>
</div>
@endsection