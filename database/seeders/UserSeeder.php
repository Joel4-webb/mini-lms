<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        
        User::create([
            'name' => 'Sarah',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        
        User::create([
            'name' => 'Marc',
            'email' => 'marc@eleve.com',
            'password' => Hash::make('password'),
            'role' => 'apprenant',
        ]);
    }
}