<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    public function run()
    {
        $pids = DB::table('products')->pluck('id');
        $cids = DB::table('categories')->pluck('id');

        foreach ($pids as $pid) {
            DB::table('category_product')->insert([
                'product_id' => $pid,
                'category_id' => $cids->random(),
            ]);
        }
    }
}
