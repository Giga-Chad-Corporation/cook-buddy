<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roomTypes = [
            ['type_name' => 'Petite Salle', 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Moyenne Salle', 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Grande Salle', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($roomTypes as $type) {
            DB::table('room_types')->insert($type);
        }
    }
}
