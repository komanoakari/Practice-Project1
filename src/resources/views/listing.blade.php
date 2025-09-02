@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/listing.css')}}">
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
<div class="listing-content">
    <h3 class="listing-heading">商品の出品</h3>
    <div class="listing-form">
        <form action="{{ route('sell.store') }}" class="listing-form-inner" method="post" enctype="multipart/form-data">
            @csrf
            <div class="listing-image-heading">商品画像</div>
            <div class="listing-form-group no-image">
                <div class="image-row">
                    <label for="image" class="image-box">
                        <span class="image-text">画像を選択する</span>
                        <div class="image-preview" id="preview"></div>
                    </label>
                    <input type="file" name="image" id="image" class="image-input" accept="image/*" style="display: none;">
                </div>

                <p class="listing-form-error">
                    @error('image')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="listing-detail-title">商品の詳細</div>
            <hr>
            <div class="listing-form-group">
                <span class="listing-form-label">カテゴリー</span>
                <div class="chip-group">
                    @foreach ($categories as $category)
                        <input type="checkbox" name="category[]" id="{{ $category->id }}" value="{{ $category->id }}">
                        <label for="{{ $category->id }}" class="chip">{{ $category->name }}</label>
                    @endforeach
                </div>
                <p class="listing-form-error">
                    @error('category')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="listing-form-group">
                <label for="condition" class="listing-condition-label">商品の状態</label>
                <div class="listing-form-select-inner">
                    <select name="condition" class="listing-form-select" id="condition">
                        <option disabled selected hidden>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                </div>
                <p class="listing-form-error">
                    @error('condition')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="listing-detail-title">商品名と説明</div>
            <hr>
            <div class="listing-form-group">
                <label for="name" class="listing-form-label">商品名</label>
                <input type="text" name="name" id="name" class="listing-form-input" value="{{ old('name') }}">
                <p class="listing-form-error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="listing-form-group">
                <label for="brand" class="listing-form-label">ブランド名</label>
                <input type="text" name="brand" id="brand" class="listing-form-input" value="{{ old('brand') }}">
            </div>

            <div class="listing-form-group">
                <label for="description" class="listing-form-label">商品の説明</label>
                <textarea name="description" id="description" class="listing-form-input" >{{ old('description') }}</textarea>
                <p class="listing-form-error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="listing-form-group">
                <label for="price" class="listing-form-label">販売価格</label>
                <div class="price-wrapper">
                    <span class="yen">¥</span>
                    <input type="number" name="price" id="price" class="listing-form-input" value="{{ old('price') }}" min="0" step="1">
                </div>
                <p class="listing-form-error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <input type="submit" class="listing-form-btn" value="出品する">
        </form>
    </div>

    <script>
    document.getElementById('image').addEventListener('change', (e) => {
    const preview = document.getElementById('preview');
    const box = preview.closest('.image-box'); // 親ラベル
    preview.innerHTML = '';

    const file = e.target.files?.[0];
    if (!file) {
        box.classList.remove('has-image');
        return;
    }
    if (!file.type.startsWith('image/')) {
        preview.innerHTML = '<p>画像ファイルを選択してください。</p>';
        e.target.value = '';
        box.classList.remove('has-image');
        return;
    }

    const reader = new FileReader();
    reader.onload = (ev) => {
        const img = document.createElement('img');
        img.src = ev.target.result;
        preview.appendChild(img);
        box.classList.add('has-image');  // ← テキストを隠す
    };
    reader.readAsDataURL(file);
    });
    </script>
    </div>
</div>
@endsection
