<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $localTypeId = DB::table('building_types')->where('name', 'local')->first()->id;
        $entrepotTypeId = DB::table('building_types')->where('name', 'entrepôt')->first()->id;
        $cuisineTypeId = DB::table('building_types')->where('name', 'cuisine centrale')->first()->id;

        $localAddresses = [
            '18 Rue des Blancs Manteaux, 75004 Paris, France', // 4ème arrondissement
            '39 Boulevard de Bonne Nouvelle, 75002 Paris, France', // 2ème arrondissement
            '14 Rue de Bretagne, 75003 Paris, France', // 3ème arrondissement
            '2 Rue Lacépède, 75005 Paris, France', // 5ème arrondissement
            '77 Avenue Ledru-Rollin, 75012 Paris, France' // 12ème arrondissement
        ];


        $entrepotAddresses = [
            '26 Rue des Rigoles, 75020 Paris, France', // Entrepôt example 1
            '15 Rue Francis De Pressensé, 75014 Paris, France'  // Entrepôt example 2
        ];

        $cuisineAddress = '16 Rue des Cinq Diamants, 75013 Paris, France'; // Cuisine Centrale example

        foreach ($localAddresses as $address) {
            DB::table('buildings')->insert([
                'name' => 'Local',
                'address' => $address,
                'building_type_id' => $localTypeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        foreach ($entrepotAddresses as $address) {
            DB::table('buildings')->insert([
                'name' => 'Entrepôt',
                'address' => $address,
                'building_type_id' => $entrepotTypeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        DB::table('buildings')->insert([
            'name' => 'Cuisine Centrale',
            'address' => $cuisineAddress,
            'building_type_id' => $cuisineTypeId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

}
