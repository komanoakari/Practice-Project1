<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $uids = DB::table('users')->pluck('id');

        DB::table('products')->insert([
            [
                'user_id' => $uids->random(),
                'name' => '腕時計',
                'brand' => 'Rolax',
                'price' => 15000,
                'image' => 'images/Armani+Mens+Clock.jpg',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '良好',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => 'HDD',
                'brand' => '西芝',
                'price' => 5000,
                'image' => 'images/HDD+Hard+Disk.jpg',
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れなし',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => '玉ねぎ3束',
                'brand' => null,
                'price' => 300,
                'image' => 'images/iLoveIMG+d.jpg',
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => 'やや傷や汚れあり',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => '革靴',
                'brand' => null,
                'price' => 4000,
                'image' => 'images/Leather+Shoes+Product+Photo.jpg',
                'description' => 'クラシックなデザインの革靴',
                'condition' => '状態が悪い',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => 'ノートPC',
                'brand' => null,
                'price' => 45000,
                'image' => 'images/Living+Room+Laptop.jpg',
                'description' => '高性能なノートパソコン',
                'condition' => '良好',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => 'マイク',
                'brand' => null,
                'price' => 8000,
                'image' => 'images/Music+Mic+4632231.jpg',
                'description' => '高音質のレコーディング用マイク',
                'condition' => '目立った傷や汚れなし',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => 'ショルダーバッグ',
                'brand' => null,
                'price' => 3500,
                'image' => 'images/Purse+fashion+pocket.jpg',
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => 'タンブラー',
                'brand' => null,
                'price' => 500,
                'image' => 'images/Tumbler+souvenir.jpg',
                'description' => '使いやすいタンブラー',
                'condition' => '状態が悪い',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => 'コーヒーミル',
                'brand' => 'Starbacks',
                'price' => 4000,
                'image' => 'images/Waitress+with+Coffee+Grinder.jpg',
                'description' => '手動のコーヒーミル',
                'condition' => '良好',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $uids->random(),
                'name' => 'メイクセット',
                'brand' => null,
                'price' => 2500,
                'image' => 'images/外出メイクアップセット.jpg',
                'description' => '便利なメイクアップセット',
                'condition' => '目立った傷や汚れなし',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
