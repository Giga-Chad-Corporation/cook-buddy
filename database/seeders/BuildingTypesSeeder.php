<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('building_types')->insert([
            ['name' => 'local'],
            ['name' => 'entrepôt'],
            ['name' => 'cuisine centrale'],
        ]);
    }

}
