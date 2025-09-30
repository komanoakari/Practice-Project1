<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_comment_and_count_increases()
    {
        $commenter = User::forceCreate([
            'name' => 'コメント太郎',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($commenter);

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
            ->assertSee('コメント(0)')
            ->assertSee('<span class="product-comments-count">0</span>', false);

        $response = $this->post(route('comment.store', $product), [
            'body' => 'ほしい',
        ])->assertRedirect();

        $this->assertDatabaseHas('comments', ['user_id' => $commenter->id, 'product_id' => $product->id, 'body' => 'ほしい']);

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('コメント(1)')
            ->assertSee('<span class="product-comments-count">1</span>', false)
            ->assertSee('ほしい')
            ->assertSee('コメント太郎');
    }

    public function test_guest_cannot_comment()
    {
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
            ->assertSee('コメント(0)')
            ->assertSee('<span class="product-comments-count">0</span>', false);

        $this->assertGuest();

        $response = $this->post(route('comment.store', $product), ['body' => '素敵']);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        $this->assertDatabaseMissing('comments', ['product_id' => $product->id, 'body' => '素敵']);

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('コメント(0)')
            ->assertSee('<span class="product-comments-count">0</span>', false)
            ->assertDontSee('素敵');
    }

    public function test_body_max_255()
    {
        $user = User::forceCreate([
            'name' => 'コメント太郎',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $product = Product::forceCreate([
            'name' => 'テスト商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        $tooLong = str_repeat('あ',256);

        $this->from("/item/{$product->id}")
            ->post(route('comment.store', $product), [
                'body' => $tooLong,
            ])
            ->assertRedirect("/item/{$product->id}")
            ->assertSessionHasErrors(['body']);
    }

    public function test_body_required()
    {
        $user = User::forceCreate([
            'name' => 'コメント太郎',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $product = Product::forceCreate([
            'name' => 'テスト商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        $this->from("/item/{$product->id}")
            ->post(route('comment.store', $product), [
                'body' => '',
            ])
            ->assertRedirect("/item/{$product->id}")
            ->assertSessionHasErrors('body');
    }
}
