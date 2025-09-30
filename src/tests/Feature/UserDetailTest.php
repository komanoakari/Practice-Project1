<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

class UserDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_detail_display()
    {
        $user = User::forceCreate([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $myListed = Product::forceCreate([
            'user_id' => $user->id,
            'name' => '出品した商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        $seller = User::forceCreate([
            'name' => '出品者さん',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
        ]);

        $toBuy = Product::forceCreate([
            'user_id' => $seller->id,
            'name' => '購入した商品B',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'b.jpg',
            'description' => '説明B',
            'condition' => '良好',
        ]);

        session(['checkout.shipping' => [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府大阪市123',
            'shipping_building' => null,
        ]]);

        $this->from(route('purchase.create', ['product' => $toBuy->id]))
            ->post(route('purchase.store', ['product' => $toBuy->id]), [
                'payment_method' => 'コンビニ支払い'])
            ->assertRedirect('/');
    }

    public function test_user_info_first_display_after_saved_once()
    {
        $user = User::forceCreate([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $this->post(route('profile.update', ['back' => 'mypage']), [
            'user_name'   => 'テスト太郎',
            'postal_code' => '123-4567',
            'address'     => '大阪府大阪市123',
            'building'    => null,
        ])->assertRedirect(route('profile.show'));

        $user->profile()->update(['image' => 'profile_images/a.jpg']);

        $this->get("/mypage/profile")
            ->assertOk()
            ->assertSee('alt="現在のプロフィール画像"', false)
            ->assertSee('value="テスト太郎"', false)
            ->assertSee('value="123-4567"', false)
            ->assertSee('value="大阪府大阪市123"', false);
    }
}
