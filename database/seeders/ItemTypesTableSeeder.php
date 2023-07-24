<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('item_types')->insert([
            ['type_name' => 'food'],
            ['type_name' => 'material'],
        ]);
    }
}
