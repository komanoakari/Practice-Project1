<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pids = DB::table('products')->pluck('id');
        $cids = DB::table('categories')->pluck('id');

        foreach ($pids as $pid) {
            DB::table('product_category')->insert([
                'product_id' => $pid,
                'category_id' => $cids->random(),
                    'created_at' =>now(), 'updated_at' => now(),
            ]);
        }
    }
}
