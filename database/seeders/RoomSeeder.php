<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Building;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $buildings = [
            [
                'name' => 'Bâtiment 1',
                'address' => '1 Rue de Paris, 75001 Paris, France',
            ],
            [
                'name' => 'Bâtiment 2',
                'address' => '2 Rue de Paris, 75002 Paris, France',
            ],
            [
                'name' => 'Bâtiment 3',
                'address' => '3 Rue de Paris, 75003 Paris, France',
            ],
            [
                'name' => 'Bâtiment 4',
                'address' => '4 Rue de Paris, 75004 Paris, France',
            ],
            [
                'name' => 'Bâtiment 5',
                'address' => '5 Rue de Paris, 75005 Paris, France',
            ],
            [
                'name' => 'Bâtiment 6',
                'address' => '6 Rue de Paris, 75006 Paris, France',
            ],
        ];

        $roomTypes = [
            'Cuisine',
            'Salle de dégustation',
            'Espace événementiel',
            'Salle de classe culinaire',
            'Salle de stockage',
        ];

        foreach ($buildings as $building) {
            $b = Building::create($building);

            foreach ($roomTypes as $roomType) {
                $type = RoomType::firstOrCreate(['type_name' => $roomType]);

                Room::create([
                    'room_type_id' => $type->id,
                    'building_id' => $b->id,
                    'name' => $roomType,
                ]);
            }
        }
    }
}

