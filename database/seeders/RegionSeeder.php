<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            'Auvergne-Rhône-Alpes',
            'Bourgogne-Franche-Comté',
            'Bretagne',
            'Centre-Val de Loire',
            'Corse',
            'Grand Est',
            'Hauts-de-France',
            'Île-de-France',
            'Normandie',
            'Nouvelle-Aquitaine',
            'Occitanie',
            'Pays de la Loire',
            'Provence-Alpes-Côte d\'Azur',
        ];

        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'name' => $region,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
