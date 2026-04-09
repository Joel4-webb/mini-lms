<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Création de l'Administrateur / Formateur [cite: 20]
        User::create([
            'name' => 'Sarah Formatrice',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Création de l'Apprenant [cite: 20]
        User::create([
            'name' => 'Marc Apprenant',
            'email' => 'marc@eleve.com',
            'password' => Hash::make('password'),
            'role' => 'apprenant',
        ]);
    }
}