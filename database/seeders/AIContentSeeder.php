<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formation;
use App\Models\Chapitre;
use App\Models\SousChapitre;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Reponse;

class AIContentSeeder extends Seeder
{
    public function run()
    {
        // 1. Création de la Formation
        $formation = Formation::create([
            'nom' => 'Anglais : Les Verbes Irréguliers',
            'description' => 'Un parcours complet généré par IA pour maîtriser les bases du passé.',
            'niveau' => 'Débutant'
        ]);

        // 2. Création du Chapitre (Le module principal)
        $chapitre = Chapitre::create([
            'formation_id' => $formation->id,
            'titre' => 'Module 1 : Comprendre et utiliser le prétérit',
            'description' => 'Ce module couvre les bases des verbes irréguliers.'
        ]);

        // 3. Création des Sous-Chapitres (Le contenu réel de l'IA)
        SousChapitre::create([
            'chapitre_id' => $chapitre->id,
            'titre' => 'Leçon 1 : Introduction',
            'contenu' => 'L\'IA nous apprend que les verbes irréguliers sont des exceptions à la règle du -ed. Exemple : "To Go" devient "Went".'
        ]);

        SousChapitre::create([
            'chapitre_id' => $chapitre->id,
            'titre' => 'Leçon 2 : Les verbes fréquents',
            'contenu' => 'Voici une liste générée par l\'IA : Be (Was/Were), Have (Had), Do (Did), See (Saw), Eat (Ate).'
        ]);

        // 4. Création du Quiz lié au chapitre
        $quiz = Quiz::create([
            'chapitre_id' => $chapitre->id,
            'titre' => 'Quiz de validation : Verbes Irréguliers',
        ]);

        // 5. Ajout d'une question QCM
        $q = Question::create([
            'quiz_id' => $quiz->id,
            'texte_question' => 'Quelles sont les formes correctes pour le verbe "TO GO" ?'
        ]);

        Reponse::create(['question_id' => $q->id, 'texte_reponse' => 'Go / Went', 'est_correcte' => true]);
        Reponse::create(['question_id' => $q->id, 'texte_reponse' => 'Go / Goed', 'est_correcte' => false]);
    }
}