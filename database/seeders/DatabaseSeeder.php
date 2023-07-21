<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\DocumentTypeSeeder;
use Database\Seeders\FreePlanSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\ProviderTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            FreePlanSeeder::class,
        ]);

        $this->call([
            StarterPlanSeeder::class,
        ]);

        $this->call([
            MasterPlanSeeder::class,
        ]);


        $this->call([
            ProviderTypeSeeder::class,
        ]);

        $this->call([
            DocumentTypeSeeder::class,
        ]);

        $this->call([
            ServiceTypeSeeder::class,
        ]);

        $this->call([
            BuildingTypesSeeder::class,
            BuildingsSeeder::class,
        ]);

        $this->call([
            RoomTypesSeeder::class,
        ]);

        $this->call([
            RoomsSeeder::class,
        ]);
    }
}
