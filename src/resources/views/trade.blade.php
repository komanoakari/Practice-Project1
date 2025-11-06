@extends('layouts.minimal')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trading.css')}}">
@endsection

@section('content')
<div class="container">
    <aside class="sidebar">
        <section>
        <h3>その他の取引</h3>
        <div class="other-tradings">
            <ul>
            @foreach($otherTradings as $trading)
                <li>
                <a href="{{ route('trade.show', ['order' => $trading->id]) }}" class="other-trading">
                    {{ $trading->product->name }}
                </a>
                </li>
            @endforeach
            </ul>
        </div>
        </section>
    </aside>

    <div class="chat">
        <div class="user-info">
        <div class="user-icon">
            @if($partner && $partner->image)
            <img src="{{ Storage::url($partner->image) }}" alt="アイコン">
            @else
            <div class="user-avatar"></div>
            @endif
        </div>
        <div class="user-name">「{{ $partner->user_name }}」さんとの取引画面</div>
        <div class="button">
            <form action="{{ route('trade.update', ['order'=> $order->id])}}" class="complete-button" method="post">
                @csrf
                <button id="openModal" type="submit" class="complete-button">取引を完了する</button>
            </form>
        </div>
        </div>
        <hr>

        <div class="product-info">
        <img src="{{ Storage::url($order->product->image) }}" alt="商品画像">
        <div class="item">
            <div class="name">{{ $order->product->name }}</div>
            <div class="price">
            <span class="price-mark">¥</span>
            {{ number_format($order->product->price)}}
            </div>
        </div>
        </div>
        <hr>

        <div class="chat-message">
        @forelse ($messages as $message)
            @if($message->user_id === $user->id)
            <div class="message-right">
                <div class="message-user">
                <div class="message-user-icon">
                    @if($user->profile->image)
                    <img src="{{ Storage::url($user->profile->image) }}" alt="アイコン">
                    @else
                    <div class="message-avatar"></div>
                    @endif
                </div>
                <span class="message-user-name">{{ $user->profile->user_name }}</span>
                </div>
                <div class="message-body">
                <div class="message-text">{{ $message->body }}</div>
                <div class="message-actions">
                    <form action="{{ route('message.edit', ['order' => $order->id, 'message' => $message->id]) }}" class="edit-form" method="get">
                        @csrf
                        <button class="edit-button">編集</button>
                    </form>
                    <form action="{{ route('message.remove', ['order' => $order->id, 'message' => $message->id]) }}" class="remove-form" method="post">
                        @csrf
                        @method('DELETE')
                            <button class="remove-button" type="submit">削除</button>
                    </form>
                </div>
                </div>
                @if($message->image)
                <div class="message-image">
                    <img src="{{ Storage::url($message->image) }}" alt="添付画像">
                </div>
                @endif
            </div>
            @else
            <div class="message-left">
                <div class="message-user">
                <div class="message-user-icon">
                    @if($partner && $partner->image)
                    <img src="{{ Storage::url($partner->image) }}" alt="アイコン">
                    @else
                    <div class="message-avatar"></div>
                    @endif
                </div>
                <span class="message-user-name">{{ $partner->user_name }}</span>
                </div>
                <div class="message-body">{{ $message->body }}</div>
                @if($message->image)
                <div class="message-image">
                    <img src="{{ Storage::url($message->image) }}" alt="添付画像">
                </div>
                @endif
            </div>
            @endif
        @empty
            <p>まだメッセージがありません</p>
        @endforelse
        </div>

        <form action="{{ isset($editMessage) ? route('message.update', ['order' => $order->id, 'message' => $editMessage->id]) : route('message.send', ['order' => $order->id]) }}" class="message-form" method="post" enctype="multipart/form-data">
            @csrf
                <div class="form-inner">
                    <div class="input-text">
                        <input type="text" name="body" placeholder="取引メッセージを記入してください" value="{{ old('body', $editMessage->body ?? '') }}">
                        <div class="image-preview" id="preview"></div>
                    </div>
                    @error('body')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('image')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="input-image">
                        <label for="image" class="image-box">
                            <span class="image-text">画像を追加</span>
                        </label>
                        <input type="file" name="image" id="image" class="image-input" accept="image/*" style="display: none;">
                    </div>
                    <button type="submit" class="send-button">
                        <img src="{{ asset('images/inputbuttun.svg')}}" alt="送信マーク">
                    </button>
        </form>
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <h2>取引が完了しました。</h2>
        <hr>
        <p>今回の取引相手はどうでしたか？</p>
        <hr>
        <form action="{{ route('trade.review', ['order' => $order->id]) }}" method="POST" class="modal-form">
        @csrf
            <div class="stars">
                <span>
                    <input type="radio" name="review" id="star1"><label for="star1">★</label>
                    <input type="radio" name="review" id="star2"><label for="star2">★</label>
                    <input type="radio" name="review" id="star3"><label for="star3">★</label>
                    <input type="radio" name="review" id="star4"><label for="star4">★</label>
                    <input type="radio" name="review" id="star5"><label for="star5">★</label>
                </span>
            </div>
            <button type="submit" class="modal-button">送信する</button>
        </form>
    </div>
</div>

<script>
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');

    if (imageInput && preview) {
        imageInput.addEventListener('change', function() {
        preview.innerHTML = '';

        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100px';
            img.style.maxHeight = '100px';
            preview.appendChild(img);
            };

            reader.readAsDataURL(this.files[0]);
        }
        });
    }

    const openBtn = document.getElementById('openModal');
    const modal = document.getElementById('modal');

    openBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });
</script>

@if(session('showReviewModal'))
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modal');
        modal.style.display = 'flex';
    })
</script>
@endif
@endsection