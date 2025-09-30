@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_edit.css')}}">
@endsection

@section('content')
<div class="edit-form">
    <h2 class="edit-form-heading">プロフィール設定</h2>
    <div class="edit-form-inner">
        <form action="{{ route('profile.update', ['back' => old('back', request('back', session('back', 'mypage')))]) }}" class="edit-form-form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="edit-form-group no-image">
                <div class="image-row">
                    <div id="preview" class="avatar-preview">
                        @if(empty($profile?->image))
                            <div class="image-placeholder"></div>
                        @else
                            <img src="{{ Storage::url($profile->image) }}" alt="現在のプロフィール画像" class="reader-image">
                        @endif
                    </div>
                    <label for="image" class="edit-form-label file-label">画像を選択する</label>
                </div>

                <input type="file" name="image" id="image" class="edit-form-image" accept="image/*" style="display: none;">
                <p class="edit-form-error-message">
                    @error('image')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="edit-form-group">
                <label for="user_name" class="edit-form-label">ユーザー名</label>
                <input type="text" name="user_name" id="user_name" class="edit-form-input" value="{{ old('user_name', $profile->user_name ?? ($user->name ?? '')) }}">
                <p class="edit-form-error-message">
                    @error('user_name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="edit-form-group">
                <label for="postal_code" class="edit-form-label">郵便番号</label>
                <input type="text" name="postal_code" id="postal_code" class="edit-form-input" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
                <p class="edit-form-error-message">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="edit-form-group">
                <label for="address" class="edit-form-label">住所</label>
                <input type="text" name="address" id="address" class="edit-form-input" value="{{ old('address', $profile->address ?? '') }}">
                <p class="edit-form-error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="edit-form-group">
                <label for="building" class="edit-form-label">建物名</label>
                <input type="text" name="building" id="building" class="edit-form-input" value="{{ old('building', $profile->building ?? '') }}">
                <p class="edit-form-error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input type="hidden" name="back" value="{{ request('back', 'mypage') }}">
            <input type="submit" class="edit-form-btn" value="更新する">
        </form>
        <script>
        document.getElementById('image').addEventListener('change', (e) => {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';
            const file = e.target.files?.[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
            preview.innerHTML = '<p>画像ファイルを選択してください。</p>';
            e.target.value = '';
            return;
            }
            const reader = new FileReader();
            reader.onload = (ev) => {
            const img = document.createElement('img');
            img.src = ev.target.result;
            img.className = 'reader-image';
            preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
        </script>
    </div>
</div>
@endsection