<?php

namespace Database\Seeders;

use App\Models\ProviderType;
use Illuminate\Database\Seeder;

class ProviderTypeSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $providerTypes = [
            'Chef cuisinier',
            'Livreur',
            'Chef de formation',
        ];

        foreach ($providerTypes as $providerType) {
            ProviderType::create([
                'type_name' => $providerType,
            ]);
        }
    }
}
