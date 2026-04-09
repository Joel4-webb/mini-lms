<?php
namespace Database\Seeders;

use App\Models\Formation;
use App\Models\User;
use Illuminate\Database\Seeder;

class FormationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de la Formation (Nom, Niveau, Description) [cite: 24]
        $formation = Formation::create([
            'nom' => 'Introduction à la Programmation Python',
            'niveau' => 'Débutant', // Critère évalué [cite: 24, 67]
            'description' => 'Découvrez les bases du langage le plus populaire au monde, des variables aux fonctions.',
        ]);

        // 2. Association de l'Apprenant à la Formation [cite: 26]
        $apprenant = User::where('email', 'marc@eleve.com')->first();
        $formation->apprenants()->attach($apprenant->id);

        // 3. Création d'un Chapitre [cite: 29]
        $chapitre = $formation->chapitres()->create([
            'titre' => 'Les bases du langage',
            'description' => 'Apprendre à stocker des données et à manipuler les types de base.',
        ]);

        // 4. Création d'un Sous-chapitre (Contenu Pédagogique) [cite: 31, 33]
        $sousChapitre = $chapitre->sousChapitres()->create([
            'titre' => 'Variables et Types de données',
            'resume' => 'Comprendre comment Python gère les entiers, les chaînes et les listes.', // Requis [cite: 35]
            'contenu' => '<h1>Les Variables</h1><p>En Python, on déclare une variable simplement : <code>x = 5</code>.</p>', // Contenu HTML [cite: 31]
            'lien_ressource' => 'https://docs.python.org/fr/3/tutorial/introduction.html' // Lien demandé [cite: 35]
        ]);

        // 5. Création du Quiz associé [cite: 39]
        $quiz = $sousChapitre->quiz()->create([
            'titre' => 'Vérification des acquis : Les Variables',
            'chapitre_id' => $chapitre->id
        ]);

        // 6. Ajout de questions au quiz [cite: 40, 41]
        $question = $quiz->questions()->create([
            'texte_question' => 'Comment déclare-t-on une liste en Python ?', //
        ]);

        // Ajout des réponses liées à cette question
        $question->reponses()->createMany([
            ['texte_reponse' => 'list = {}', 'est_correcte' => false], //
            ['texte_reponse' => 'list = []', 'est_correcte' => true],  //
            ['texte_reponse' => 'list = ()', 'est_correcte' => false], //
        ]);
    }
}