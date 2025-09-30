<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        Product::forceCreate([
            'name' => '検索したい商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'a.img',
            'condition' => '良好',
        ]);
        Product::forceCreate([
            'name' => '別ワード',
            'price' => 4000,
            'description' => '説明',
            'image' => 'a.img',
            'condition' => '良好',
        ]);

        $this->get('/search?q=検索')
            ->assertOk()
            ->assertSee('検索したい商品')
            ->assertDontSee('別ワード');
    }

    public function test_search_keyword_is_kept_in_mylist()
    {
        $user = User::forceCreate([
            'name' => 'user',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $p = Product::forceCreate([
            'name' => '検索用の商品',
            'price' => 4000,
            'description' => '説明',
            'image' => 'a.img',
            'condition' => '良好',
        ]);
        $user->mylistProducts()->syncWithoutDetaching([$p->id]);

        $this->actingAs($user);

        $response = $this->get('/search?q=検索');
        $response->assertOk()->assertSee('検索用の商品');

        $response2 = $this->get('/search?q=検索&tab=mylist');
        $response2->assertOk()->assertSee('検索用の商品');
    }
}
