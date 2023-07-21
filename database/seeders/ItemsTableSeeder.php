<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        // Fetch item types
        $foodTypeId = DB::table('item_types')->where('type_name', 'food')->first()->id;
        $materialTypeId = DB::table('item_types')->where('type_name', 'material')->first()->id;

        // Create food items
        DB::table('items')->insert([
            ['model_name' => 'Pizza', 'selling_price' => 10.50, 'item_type_id' => $foodTypeId],
            ['model_name' => 'Couscous', 'selling_price' => 13.00, 'item_type_id' => $foodTypeId],
            ['model_name' => 'Baguette', 'selling_price' => 1.00, 'item_type_id' => $foodTypeId],
            ['model_name' => 'Croissant', 'selling_price' => 2.50, 'item_type_id' => $foodTypeId],
            ['model_name' => 'Quiche', 'selling_price' => 7.50, 'item_type_id' => $foodTypeId],
        ]);

        // Create material items
        DB::table('items')->insert([
            ['model_name' => 'Fourchette', 'selling_price' => 1.50, 'item_type_id' => $materialTypeId],
            ['model_name' => 'Casserole', 'selling_price' => 20.00, 'item_type_id' => $materialTypeId],
            ['model_name' => 'Poêle', 'selling_price' => 25.00, 'item_type_id' => $materialTypeId],
            ['model_name' => 'Assiette', 'selling_price' => 5.00, 'item_type_id' => $materialTypeId],
            ['model_name' => 'Verre', 'selling_price' => 3.00, 'item_type_id' => $materialTypeId],
        ]);
    }
}
