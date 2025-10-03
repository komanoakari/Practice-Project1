<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class ProfileUpdateTest extends TestCase
{
    public function test_user_info_first_display_after_saved_once()
    {
        $user = User::forceCreate([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->from(route('profile.edit', ['back' => 'mypage']))
            ->put(route('profile.update', ['back' => 'mypage']), [
            'user_name'   => 'テスト太郎',
            'postal_code' => '123-4567',
            'address'     => '大阪府大阪市123',
            'building'    => null,
            'image'       => UploadedFile::fake()->create('a.jpg', 10, 'image/jpeg'),
        ]);
        $response->assertRedirect(route('profile.show'));

        $this->get("/mypage/profile")
            ->assertOk()
            ->assertSee('alt="現在のプロフィール画像"', false)
            ->assertSee('value="テスト太郎"', false)
            ->assertSee('value="123-4567"', false)
            ->assertSee('value="大阪府大阪市123"', false);
    }
}
