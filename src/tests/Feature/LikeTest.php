<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_and_count_increases()
    {
        $liker = User::forceCreate([
            'name' => 'いいね太郎',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($liker);

        $product = Product::forceCreate([
            'name' => 'テスト商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('<span class="product-likes-count">0</span>', false);

        $response = $this->post(route('mylist.store', $product), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
        $this->assertTrue($response->json('liked'));
        $this->assertSame(1, $response->json('likes_count'));

        $this->assertDatabaseHas('mylist', ['user_id' => $liker->id, 'product_id' => $product->id]);

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('<span class="product-likes-count">1</span>', false)
            ->assertSee('product-likes-btn--active');
    }

    public function test_user_can_like_and_color_changes()
    {
        $liker = User::forceCreate([
            'name' => 'いいね花子',
            'email' => 'liker@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($liker);

        $product = Product::forceCreate([
            'name' => 'テスト商品',
            'brand' => 'ブランド',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明文',
            'condition' => '良好',
        ]);

        $response = $this->post(route('mylist.store', $product), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
        $this->assertTrue($response->json('liked'));

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('product-likes-btn--active');
    }

    public function test_user_can_unlike_and_count_decreases()
    {
        $liker = User::forceCreate([
            'name' => 'いいね太郎',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($liker);

        $product = Product::forceCreate([
            'name' => 'テスト商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('<span class="product-likes-count">0</span>', false)
            ->assertSee('data-liked="false"', false)
            ->assertSee('class="product-likes-btn "', false);

        $this->post(route('mylist.store', $product), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])->assertOk();

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('<span class="product-likes-count">1</span>', false)
            ->assertSee('class="product-likes-btn product-likes-btn--active"', false);

        $this->delete(route('mylist.destroy', $product), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])->assertOk();

        $this->assertDatabaseMissing('mylist', ['user_id' => $liker->id, 'product_id' => $product->id]);

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('<span class="product-likes-count">0</span>', false)
            ->assertSee('data-liked="false"', false)
            ->assertSee('class="product-likes-btn "', false);
    }
}
