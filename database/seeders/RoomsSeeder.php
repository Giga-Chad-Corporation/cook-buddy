<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Room types with their respective capacities
        $roomTypes = [
            'Petite Salle' => 5,
            'Moyenne Salle' => 10,
            'Grande Salle' => 20,
        ];

        // Fetch all local building IDs
        $buildingTypeId = DB::table('building_types')->where('name', 'local')->first()->id;
        $localBuildingIds = DB::table('buildings')->where('building_type_id', $buildingTypeId)->pluck('id');

        // For each local building
        foreach ($localBuildingIds as $buildingId) {
            // For each room type
            foreach ($roomTypes as $typeName => $capacity) {
                $roomTypeRecord = DB::table('room_types')->where('type_name', $typeName)->first();

                if ($roomTypeRecord) {
                    $roomTypeId = $roomTypeRecord->id;

                    // Create 3 rooms of this type
                    for ($i = 1; $i <= 3; $i++) {
                        DB::table('rooms')->insert([
                            'name' => "$typeName Room $i",
                            'room_type_id' => $roomTypeId,
                            'max_capacity' => $capacity,
                            'building_id' => $buildingId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                } else {
                    echo "No room type found with the name '$typeName'\n";
                }
            }
        }
    }
}

