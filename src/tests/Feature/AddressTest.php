<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipping_address_updates()
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

        $response = $this->from(route('address.edit', ['product' => $product->id]))
            ->put(route('address.update', ['product' => $product->id]), [
                'shipping_postal_code' => '123-4567',
                'shipping_address' => '大阪府大阪市123',
                'shipping_building' => '',
            ]);

        $response->assertRedirect("/purchase/{$product->id}");

        $response->assertSessionHas('checkout.shipping', function ($shipping) {
            $this->assertSame('123-4567', $shipping['shipping_postal_code']);
            $this->assertSame('大阪府大阪市123', $shipping['shipping_address']);
            $this->assertNull($shipping['shipping_building']);
            return true;
        });

        $this->get("/purchase/{$product->id}")
            ->assertOk()
            ->assertSee('123-4567')
            ->assertSee('大阪府大阪市123');
    }

    public function test_shipping_address_updates_and_purchase_save_data()
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

        $response = $this->from(route('address.edit', ['product' => $product->id]))
            ->put(route('address.update', ['product' => $product->id]), [
                'shipping_postal_code' => '123-4567',
                'shipping_address' => '大阪府大阪市123',
                'shipping_building' => '',
        ]);

        $response->assertRedirect("/purchase/{$product->id}");

        $response = $this->from(route('purchase.create', ['product' => $product->id]))
            ->post(route('purchase.store', ['product' => $product->id]), ['payment_method' => 'コンビニ支払い']);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'amount' => 3000,
            'payment_method' => 'コンビニ支払い',
            'status' => 'paid',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府大阪市123',
            'shipping_building' => null,
        ]);

        $this->get('/')->assertOk();
    }
}

