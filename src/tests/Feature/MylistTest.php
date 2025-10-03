<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_mylist_products()
    {
        $user = User::forceCreate([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->actingAs($user);

        $liked = Product::forceCreate([
            'name' => 'いいねした商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'a.img',
            'condition' => '良好',
        ]);

        $notliked = Product::forceCreate([
            'name' => 'いいねしていない商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'b.img',
            'condition' => '良好',
        ]);

        $user->mylistProducts()->syncWithoutDetaching([$liked->id]);

        $this->get('/?tab=mylist')
            ->assertOk()
            ->assertSee('いいねした商品', false)
            ->assertDontSee('いいねしていない商品', false);
    }

        public function test_mylist_marks_sold_products()
    {
        $user = User::forceCreate([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->actingAs($user);

        $a = Product::forceCreate([
            'name' => 'いいねした商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'a.img',
            'condition' => '良好',
        ]);
        $user->mylistProducts()->syncWithoutDetaching([$a->id]);

        \Illuminate\Support\Facades\DB::table('orders')->insert([
            'user_id' => $user->id,
            'product_id' => $a->id,
            'amount' => 4000,
            'payment_method' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府大阪市123',
            'status' => 'paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/?tab=mylist')
            ->assertOk()
            ->assertSee('いいねした商品',false)
            ->assertSee('Sold', false);
    }

    public function test_guest_mylist_products()
    {
        $someone = User::forceCreate([
            'name' => '誰か',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $liked = Product::forceCreate([
            'name' => 'いいねした商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'a.img',
            'condition' => '良好',
        ]);

        $someone->mylistProducts()->syncWithoutDetaching([$liked->id]);

        $this->assertGuest();

        $this->get('/?tab=mylist')
            ->assertOk()
            ->assertSee('マイリストを見るにはログインしてください', false);
    }
}
