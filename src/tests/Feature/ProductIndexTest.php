<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_index_shows()
    {
        \App\Models\Product::forceCreate([
            'name' => 'テスト商品A',
            'price' => 1000,
            'image' => 'a.jpg',
            'description' => '説明A',
            'condition' => '良好',
        ]);

        \App\Models\Product::forceCreate([
            'name' => 'テスト商品B',
            'price' => 2000,
            'image' => 'b.jpg',
            'description' => '説明B',
            'condition' => '状態が悪い',
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('テスト商品A')
            ->assertSee('テスト商品B');
    }

    public function test_index_marks_sold_products()
    {
        $buyer = User::forceCreate([
            'name' => '購入者',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $p = \App\Models\Product::forceCreate([
            'name' => '売れた商品',
            'price' => 3000,
            'image' => 'c.jpg',
            'description' => '説明C',
            'condition' => '良好',
        ]);

        \Illuminate\Support\Facades\DB::table('orders')->insert([
            'user_id' => $buyer->id,
            'product_id' => $p->id,
            'amount' => 3000,
            'payment_method' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府大阪市123',
            'status' => 'paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/')->assertOk()->assertSee('売れた商品')->assertSee('Sold');
    }

    public function test_index_hides_my_products()
    {
        $seller = User::forceCreate([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->actingAs($seller);

        \App\Models\Product::forceCreate([
            'user_id' => $seller->id,
            'name' => '出品した商品',
            'price' => 4000,
            'description' => '説明D',
            'image' => 'd.img',
            'condition' => '良好',
        ]);

        $this->get('/')->assertOk()->assertDontSee('出品した商品');
    }
}
