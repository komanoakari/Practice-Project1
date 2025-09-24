<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_success()
    {
        $user = User::forceCreate([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();

        $response->assertRedirect('/');
    }
}
