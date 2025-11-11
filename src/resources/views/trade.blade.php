@extends('layouts.minimal')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trading.css')}}">
@endsection

@section('content')
<div class="container">
    <aside class="sidebar">
        <section>
        <h3 class="sidebar-heading">その他の取引</h3>
        <div class="other-tradings">
            <ul>
            @foreach($tradings as $trading)
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

    <div class="trading-content">
        <div class="user-info">
            <div class="user-icon">
                @if($partner && $partner->image)
                <img src="{{ Storage::url($partner->image) }}" alt="アイコン">
                @else
                <div class="user-avatar"></div>
                @endif
            </div>
            <div class="user-name">「{{ $partner->user_name }}」さんとの取引画面</div>
            @if($order->user_id === $user->id)
                <div class="button">
                    <form action="{{ route('trade.update', ['order'=> $order->id])}}" class="complete-button" method="post">
                        @csrf
                            <button id="openModal" name="complete-button" type="submit" class="complete-button">取引を完了する</button>
                    </form>
                </div>
            @endif
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

        <div class="chat-content">
        @forelse ($messages as $message)
            @if($message->user_id === $user->id)
            <div class="chat-right">
                <div class="chat-user">
                    <span class="chat-user-name">{{ $user->profile->user_name }}</span>
                    <div class="chat-icon">
                        @if($user->profile->image)
                        <img src="{{ Storage::url($user->profile->image) }}" alt="アイコン">
                        @else
                        <div class="chat-avatar"></div>
                        @endif
                    </div>
                </div>
                <div class="chat-inner">
                    <div class="own-message">{!! nl2br(e($message->body)) !!}</div>
                    @if($message->image)
                    <div class="image-wrapper">
                        <img src="{{ Storage::url($message->image) }}" alt="添付画像">
                    </div>
                    @endif
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
            </div>
            @else
            <div class="chat-left">
                <div class="chat-user">
                    <div class="chat-icon">
                        @if($partner && $partner->image)
                        <img src="{{ Storage::url($partner->image) }}" alt="アイコン">
                        @else
                        <div class="chat-avatar"></div>
                        @endif
                    </div>
                    <span class="chat-user-name">{{ $partner->user_name }}</span>
                </div>
                <div class="chat-inner">
                    <div class="body">{{ $message->body }}</div>
                    @if($message->image)
                    <div class="image-wrapper">
                        <img src="{{ Storage::url($message->image) }}" alt="添付画像">
                    </div>
                    @endif
                </div>
            </div>
            @endif
        @empty
            <p>まだメッセージがありません</p>
        @endforelse
        </div>

        <div class="input-form">
            <form action="{{ isset($editMessage) ? route('message.update', ['order' => $order->id, 'message' => $editMessage->id]) : route('message.send', ['order' => $order->id]) }}" class="form-inner" method="post" enctype="multipart/form-data">
                @csrf
                <div class="text-area">
                    <div class="text">
                        <textarea id="message-body" name="body" placeholder="取引メッセージを記入してください">{{ old('body', $editMessage->body ?? '')}}</textarea>
                    </div>
                    <div class="input-image">
                        <label for="image" class="image-box">
                            <span class="image-text">画像を追加</span>
                        </label>
                        <input type="file" name="image" id="image" class="image-preview" accept="image/*" style="display: none;">
                    </div>
                    <button type="submit" class="send-button">
                        <img src="{{ asset('images/inputbuttun.svg')}}" alt="送信マーク">
                    </button>
                </div>
                <div class="image-preview" id="preview"></div>
            </form>

            <div class="error">
                @error('body')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                @error('image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <h3>取引が完了しました。</h3>
        <hr>
        <p>今回の取引相手はどうでしたか？</p>
        <form action="{{ route('trade.review', ['order' => $order->id]) }}" method="POST" class="modal-form">
            @csrf
            <div class="stars">
                <label data-value="1">★</label>
                <label data-value="2">★</label>
                <label data-value="3">★</label>
                <label data-value="4">★</label>
                <label data-value="5">★</label>
                <input type="hidden" name="review" id="review-value" value="">
            </div>
            <hr>
            <div class="button-area">
                <button type="submit" class="modal-button">送信する</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                        img.style.maxWidth = '200px';
                        img.style.maxHeight = '200px';
                        img.style.objectFit = 'cover';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        const textarea = document.getElementById('message-body');

        if (textarea) {
            const orderId = {{ $order->id }};
            const userId = {{ $user->id }};
            const storageKey = 'trade_message_' + orderId + '_' + userId;
            const form = document.querySelector('.form-inner');
            const isEditMode = location.pathname.includes('/message/') && !location.pathname.endsWith('/message');

            if (!isEditMode) {
                const hasValue = textarea.value.trim() !== '';

                if(!hasValue) {
                    const saved = localStorage.getItem(storageKey);
                    if (saved) {
                        textarea.value = saved;
                    }
                }
                textarea.addEventListener('input', () => {
                    localStorage.setItem(storageKey, textarea.value);
                });

                if (form) {
                    form.addEventListener('submit', () => {
                        localStorage.removeItem(storageKey);
                    });
                }
            } else {
                localStorage.removeItem(storageKey);
            }
        }

        const stars = document.querySelectorAll('.stars label');
        const reviewInput = document.getElementById('review-value');

        if (stars.length > 0) {
            for (let i = 0; i < stars.length; i++) {
                stars[i].addEventListener('click', function() {
                    console.log('星がクリックされました:', i + 1);

                    if (reviewInput) {
                        reviewInput.value = i + 1;
                    }

                    for (let j = 0; j < stars.length; j++) {
                        if (j <= i) {
                            stars[j].classList.add('active');
                        } else {
                            stars[j].classList.remove('active');
                        }
                    }
                });
            }
        }
    });
</script>

@if(session('showReviewModal') || (isset($showReviewModal) && $showReviewModal))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal');
        if (modal) {
            modal.style.display = 'flex';
        }
    });
</script>
@endif
@endsection