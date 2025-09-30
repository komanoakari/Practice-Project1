<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_method_changes()
    {
        $buyer = User::forceCreate([
            'name' => '購入太郎',
            'email' => 'commenter@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($buyer);

        $p = Product::forceCreate([
            'name' => 'テスト商品A',
            'brand' => 'ブランド名',
            'price' => 3000,
            'image' => 'a.jpg',
            'description' => '説明',
            'condition' => '良好',
        ]);

        $this->get("/purchase/{$p->id}")
            ->assertOk()
            ->assertSee('<td id="summary-payment">未選択</td>', false);

        $this->withSession(['_old_input' => ['payment_method' => 'コンビニ支払い']])
            ->get(route('purchase.create', $p))
            ->assertOk()
            ->assertSee('<td id="summary-payment">コンビニ支払い</td>', false);

        $this->withSession(['_old_input' => ['payment_method' => 'カード支払い']])
            ->get(route('purchase.create', $p))
            ->assertOk()
            ->assertSee('<td id="summary-payment">カード支払い</td>', false);
    }
}
