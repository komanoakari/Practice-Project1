<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'テスト太郎',
                    'email' => 'test1@example.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'テスト花子',
                    'email' => 'test2@example.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'テストユーザー',
                    'email' => 'test3@example.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ],
        );
    }
}