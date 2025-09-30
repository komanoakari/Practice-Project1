<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

use Tests\TestCase;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_listing_product_and_save_data()
    {
        $seller = User::forceCreate([
            'name' => '販売太郎',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($seller);

        $cat1 = Category::forceCreate(['name' => '家電']);
        $cat2 = Category::forceCreate(['name' => 'インテリア']);

        Storage::fake('public');
        $tmp = tempnam(sys_get_temp_dir(), 'jpg');

        file_put_contents($tmp,base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxISEhURExIVFRUVFRUVFRUVFRUVFRUVFRUWFxUVHCggGBolGxUVITEhJSkrLi4uFx8zODMsNygtLisBCgoKDg0OGhAQGy0lICUtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAAEAAQMBIgACEQEDEQH/xAAbAAABBQEBAAAAAAAAAAAAAAADAAIEBQYBB//EADoQAAEDAgQDBgQEBwAAAAAAAAEAAgMEEQUSITMBURQiYXGBkaGxwRQjQlLw8WKS0uEVM5Ki/8QAGQEAAwEBAQAAAAAAAAAAAAAAAAECAwQF/8QAHhEAAIDAQEBAQEAAAAAAAAAAAECERIxAyFBYXH/2gAMAwEAAhEDEQA/APcQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD//2Q=='));

        $file = new UploadedFile($tmp, 'product.jpg', 'image/jpeg', null, true);

        $response = $this->post(route('sell.store'), [
                'name' => 'テスト商品',
                'description' => '説明文',
                'image' => $file,
                'condition' => '良好',
                'price' => 3000,
                'brand' => 'ブランド名',
                'category' => [$cat1->id, $cat2->id],
        ]);
        $response->assertRedirect(route('products.index'));

        $product = Product::latest('id')->first();
        $this->assertNotNull($product);
        $this->assertEquals($seller->id, $product->user_id);
        $this->assertEquals('テスト商品', $product->name);
        $this->assertEquals('ブランド名', $product->brand);
        $this->assertEquals('説明文', $product->description);
        $this->assertEquals('良好', $product->condition);
        $this->assertEquals(3000, $product->price);
        $this->assertStringStartsWith('images/', $product->image);

        $this->assertDatabaseHas('category_product',['product_id' => $product->id, 'category_id' => $cat1->id]);
        $this->assertDatabaseHas('category_product',['product_id' => $product->id, 'category_id' => $cat2->id]);
    }
}
