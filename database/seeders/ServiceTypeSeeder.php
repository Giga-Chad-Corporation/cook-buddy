<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $serviceTypes = [
            'Cours à domicile',
            'Cours en ligne',
            'Ateliers',
            'Formations professionnelles',
            'Évenements',
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::create(['type_name' => $serviceType]);
        }
    }
}

