<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_displays()
    {
        $product = Product::forceCreate([
            'name' => 'テスト商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        $cat = Category::forceCreate(['name' => '家電']);
        $product->categories()->sync([$cat->id]);

        $liker = User::forceCreate([
            'name' => '山田太郎',
            'email' => 'example@example.com',
            'password' => Hash::make('password'),
        ]);
        $product->wishlistBy()->sync([$liker->id]);

        $commenter = User::forceCreate([
            'name' => 'コメント太郎',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password'),
        ]);

        Comment::forceCreate([
            'user_id' => $commenter->id,
            'product_id' => $product->id,
            'body' => 'ほしい',
        ]);

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('商品画像')
            ->assertSee('テスト商品A')
            ->assertSee('ブランド名')
            ->assertSee('3,000')
            ->assertSee('説明')
            ->assertSee('良好')
            ->assertSee('家電')
            ->assertSee('コメント(1)')
            ->assertSee('ほしい')
            ->assertSee('コメント太郎')
            ->assertSee('<span class="product-likes-count">1</span>', false);
    }

    public function test_product_detail_category_displays()
    {
        $product = Product::forceCreate([
            'name' => 'テスト商品B',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        $cat1 = Category::forceCreate(['name' => '家電']);
        $cat2 = Category::forceCreate(['name' => 'インテリア']);
        $product->categories()->sync([$cat1->id, $cat2->id]);

        $this->get("/item/{$product->id}")
            ->assertOk()
            ->assertSee('家電')
            ->assertSee('インテリア');
    }
}
