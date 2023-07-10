<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $serviceTypes = [
            ['type_name' => 'Atelier'],
            ['type_name' => 'Cours à domicile'],
            ['type_name' => 'Formation Professionnel'],
            ['type_name' => 'Cours en ligne'],
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::create($serviceType);
        }
    }
}
