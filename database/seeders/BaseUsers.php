<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BaseUsers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'last_name' => 'admin',
            'first_name' => 'admin',
            'email' => 'admin@gccorp.fr',
            'password' => bcrypt('Gccorp123!')
        ]);
    }
}
