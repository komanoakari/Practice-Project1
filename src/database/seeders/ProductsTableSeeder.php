<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => '腕時計',
                'brand' => 'Rolax',
                'price' => '15,000',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '良好',
            ],
            [
                'name' => 'HDD',
                'brand' => '西芝',
                'price' => '5,000',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => null,
                'price' => '300',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'name' => '革靴',
                'brand' => null,
                'price' => '4,000',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'description' => 'クラシックなデザインの革靴',
                'condition' => '状態が悪い',
            ],
            [
                'name' => 'ノートPC',
                'brand' => null,
                'price' => '45,000',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'description' => '高性能なノートパソコン',
                'condition' => '良好',
            ],
            [
                'name' => 'マイク',
                'brand' => null,
                'price' => '8,000',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'description' => '高音質のレコーディング用マイク',
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => null,
                'price' => '3,500',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'name' => 'タンブラー',
                'brand' => null,
                'price' => '500',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'description' => '使いやすいタンブラー',
                'condition' => '状態が悪い',
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'Starbacks',
                'price' => '4,000',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'description' => '手動のコーヒーミル',
                'condition' => '良好',
            ],
            [
                'name' => 'メイクセット',
                'brand' => null,
                'price' => '2,500',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'description' => '便利なメイクアップセット',
                'condition' => '目立った傷や汚れなし',
            ],
        ]);
    }
}
