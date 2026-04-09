<?php
namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $apprenant = User::where('email', 'marc@eleve.com')->first();
        $quiz = Quiz::first();

        if ($apprenant && $quiz) {
            Note::create([
                'user_id' => $apprenant->id,
                'quiz_id' => $quiz->id,
                'note' => 16.0, // Note sur 20 [cite: 64]
            ]);
        }
    }
}