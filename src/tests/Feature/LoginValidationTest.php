<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_required()
    {
        $this->from('/login')
            ->post('/login', [
                'email' => '',
                'password' => 'password',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_password_required()
    {
        $this->from('/login')
            ->post('/login', [
                'email' => 'test@example.com',
                'password' => '',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_login_unregistered()
    {
        $this->from('/login')
            ->post('/login', [
                'email' => 'test1@example.com',
                'password' => 'password',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    public function test_login_success()
    {
        $user = User::forceCreate([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect('/');
    }
}
