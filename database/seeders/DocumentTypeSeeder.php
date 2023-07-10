<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = [
            'pdf',
            'png',
            'jpg',
            'doc',
            'docx',
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::create([
                'type_name' => $documentType,
            ]);
        }
    }
}
