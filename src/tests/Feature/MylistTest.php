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

        $a = Product::forceCreate([
            'name' => 'いいねした商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'a.img',
            'condition' => '良好',
        ]);

        $b = Product::forceCreate([
            'name' => 'いいねしていない商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'b.img',
            'condition' => '良好',
        ]);

        $user->mylistProducts()->syncWithoutDetaching([$a->id]);

        $response = $this->get('/')->assertOk();
        $mylisted = $response->viewData('mylistedProducts');

        $names = collect($mylisted->items())->pluck('name')->all();

        $this->assertContains('いいねした商品', $names);
        $this->assertNotContains('いいねしてない商品', $names);
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

        $response = $this->get('/')->assertOk();
        $mylist = $response->viewData('mylistedProducts');
        $item = collect($mylist->items())->firstWhere('id', $a->id);

        $this->assertNotNull($item);
        $this->assertTrue($item->is_sold);

        $response->assertSee('いいねした商品')->assertSee('Sold');
    }

    public function test_guest_mylist_products()
    {
        $someone = User::forceCreate([
            'name' => '誰か',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $p = Product::forceCreate([
            'name' => 'いいねした商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'a.img',
            'condition' => '良好',
        ]);

        $someone->mylistProducts()->syncWithoutDetaching([$p->id]);

        $this->assertGuest();

        $response = $this->get('/')->assertOk();

        $this->assertNull($response->viewData('mylistedProducts'));

        $response->assertSee('マイリストを見るにはログインしてください');
    }
}
