<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_products()
    {
        $buyer = User::forceCreate([
            'name' => '購入太郎',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($buyer);

        $product = Product::forceCreate([
            'name' => 'テスト商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        session(['checkout.shipping' => [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府大阪市123',
            'shipping_building' => '',
        ]]);

        $response = $this->from(route('purchase.create', $product))
            ->post(route('purchase.store', $product), ['payment_method' => 'コンビニ支払い']);
        $response->assertRedirect('/')->assertSessionHas('status', 'コンビニ払いで購入を完了しました');

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'amount' => 3000,
            'payment_method' => 'コンビニ支払い',
            'status' => 'paid',
        ]);

        $this->get('/')->assertOk();
    }

    public function test_user_can_purchase_and_marks_sold_products()
    {
        $buyer = User::forceCreate([
            'name' => '購入太郎',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($buyer);

        $product = Product::forceCreate([
            'name' => 'テスト商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        session(['checkout.shipping' => [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府大阪市123',
            'shipping_building' => '',
        ]]);

        $this->from(route('purchase.create', $product))
            ->post(route('purchase.store', $product), ['payment_method' => 'コンビニ支払い'])
            ->assertRedirect('/');

        $this->get('/')->assertOk()->assertSee('テスト商品A')->assertSee('Sold');
    }

    public function test_user_can_purchase_and_mypage_display()
    {
        $buyer = User::forceCreate([
            'name' => '購入太郎',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($buyer);

        $product = Product::forceCreate([
            'name' => '購入した商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        session(['checkout.shipping' => [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府大阪市123',
            'shipping_building' => '',
        ]]);

        $this->from(route('purchase.create', $product))
            ->post(route('purchase.store', $product), ['payment_method' => 'コンビニ支払い'])
            ->assertRedirect('/');

        $this->get('/mypage?page=buy')
            ->assertOk()
            ->assertSee('購入した商品A', false)
            ->assertSee('Sold', false);
    }
}