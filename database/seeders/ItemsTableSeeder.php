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
            ['model_name' => 'Pizza', 'selling_price' => 10.50, 'item_type_id' => $foodTypeId, 'picture_url' => '/images/shop/food/pizza.jpg', 'description' => 'Délicieuse pizza avec tomate, fromage et basilic'],
            ['model_name' => 'Couscous', 'selling_price' => 13.00, 'item_type_id' => $foodTypeId, 'picture_url' => '/images/shop/food/couscous.jpg', 'description' => 'Savoureux couscous avec légumes et viande'],
            ['model_name' => 'Baguette', 'selling_price' => 1.00, 'item_type_id' => $foodTypeId, 'picture_url' => '/images/shop/food/baguette.jpg', 'description' => 'Baguette française classique, croustillante et moelleuse'],
            ['model_name' => 'Croissant', 'selling_price' => 2.50, 'item_type_id' => $foodTypeId, 'picture_url' => '/images/shop/food/croissant.jpg', 'description' => 'Croissant beurré et feuilleté, parfait pour le petit déjeuner'],
            ['model_name' => 'Quiche', 'selling_price' => 7.50, 'item_type_id' => $foodTypeId, 'picture_url' => '/images/shop/food/quiche.jpg', 'description' => 'Délicieuse quiche au fromage et aux légumes'],
        ]);

        // Create material items
        DB::table('items')->insert([
            ['model_name' => 'Fourchette', 'selling_price' => 1.50, 'item_type_id' => $materialTypeId, 'picture_url' => '/images/shop/materials/fourchette.jpg', 'description' => 'Fourchette robuste et élégante'],
            ['model_name' => 'Casserole', 'selling_price' => 20.00, 'item_type_id' => $materialTypeId, 'picture_url' => '/images/shop/materials/casserole.jpg', 'description' => 'Casserole de haute qualité pour cuisiner'],
            ['model_name' => 'Poêle', 'selling_price' => 25.00, 'item_type_id' => $materialTypeId, 'picture_url' => '/images/shop/materials/poele.png', 'description' => 'Poêle antiadhésive, idéale pour frire'],
            ['model_name' => 'Assiette', 'selling_price' => 5.00, 'item_type_id' => $materialTypeId, 'picture_url' => '/images/shop/materials/assiette.jpg', 'description' => 'Belle assiette pour servir la nourriture'],
            ['model_name' => 'Verre', 'selling_price' => 3.00, 'item_type_id' => $materialTypeId, 'picture_url' => '/images/shop/materials/verre.jpg', 'description' => 'Verre d’une clarté cristalline pour les boissons'],
        ]);
    }
}
