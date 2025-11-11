<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::where('email', 'test1@example.com')->first();

        if($user1) {
            Profile::create([
                'user_id' => $user1->id,
                'user_name' => 'テスト太郎',
                'postal_code' => '100-0000',
                'address' => '東京都千代田区架空町1-2-3',
                'building' => '存在しないビルディング404号',
                'image' => 'images/icon_user.png',
            ]);
        }

        $user2 = User::where('email', 'test2@example.com')->first();

        if($user2) {
            Profile::create([
                'user_id' => $user2->id,
                'user_name' => 'テスト花子',
                'postal_code' => '540-0000',
                'address' => '大阪府大阪市中央区ダミー1丁目2番地3号',
                'building' => '',
                'image' => 'images/icon_user.png',
            ]);
        }

        $user3 = User::where('email', 'test3@example.com')->first();

        if($user3) {
            Profile::create([
                'user_id' => $user3->id,
                'user_name' => 'テストユーザー',
                'postal_code' => '810-0000',
                'address' => '福岡県福岡市中央区テスト町4-5-6',
                'building' => '',
                'image' => 'images/icon_user2.png',
            ]);
        }
    }
}
