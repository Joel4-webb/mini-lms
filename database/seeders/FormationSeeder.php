<?php
namespace Database\Seeders;

use App\Models\Formation;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Reponse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class FormationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@lms.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $apprenant = User::firstOrCreate(
            ['email' => 'marc@eleve.com'],
            ['name' => 'Marc', 'password' => Hash::make('password'), 'role' => 'apprenant']
        );

        $sujets = [
            'Les bases de Python pour débutants',
            'Architecture UI/UX et design d\'interfaces',
            'Introduction à l\'analyse de données avec Python'
        ];

        $apiKey = config('services.groq.key') ?? env('GROQ_API_KEY');

        foreach ($sujets as $sujet) {
            $this->command->info(" Génération en cours pour : {$sujet}...");

            $prompt = "Tu es un expert pédagogique. Réponds UNIQUEMENT en JSON valide.
            RÈGLES IMPORTANTES :
            1. Le 'contenu' DOIT être TRÈS LONG, détaillé et formaté en HTML (utilise <h2>, <p>, <ul>, <li>, <strong>, et <pre><code>).
            2. Chaque leçon doit inclure 3 questions de quiz.
            3. Chaque question doit avoir exactement 4 options. 'correct' doit être un booléen (true/false).
            
            Format attendu :
            {
                \"description\": \"...\",
                \"niveau\": \"Débutant\",
                \"chapitres\": [
                    {
                        \"titre\": \"...\",
                        \"lecons\": [
                            {
                                \"titre\": \"...\",
                                \"resume\": \"...\",
                                \"contenu\": \"...\",
                                \"questions\": [
                                    {
                                        \"question\": \"...\",
                                        \"options\": [
                                            {\"texte\": \"Option A\", \"correct\": true},
                                            {\"texte\": \"Option B\", \"correct\": false},
                                            {\"texte\": \"Option C\", \"correct\": false},
                                            {\"texte\": \"Option D\", \"correct\": false}
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }";

            $response = Http::withToken($apiKey)->timeout(120)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'openai/gpt-oss-120b',
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                    ['role' => 'user', 'content' => "Génère un cours structuré sur : {$sujet}"]
                ],
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $data = json_decode($response->json('choices.0.message.content'), true);

                if (!isset($data['chapitres'])) {
                    $this->command->error(" Format JSON invalide renvoyé pour : {$sujet}");
                    continue;
                }

                $formation = Formation::create([
                    'nom' => $sujet,
                    'description' => $data['description'] ?? 'Formation générée par IA.',
                    'niveau' => $data['niveau'] ?? 'Débutant',
                    'creator_id' => $admin->id,
                    'is_public' => true,
                    'is_featured' => true,
                ]);

                $formation->apprenants()->attach($apprenant->id);

                foreach ($data['chapitres'] as $chapData) {
                    $chapitre = $formation->chapitres()->create([
                        'titre' => $chapData['titre'] ?? 'Chapitre sans titre',
                        'description' => 'Module généré par IA'
                    ]);

                    if (isset($chapData['lecons'])) {
                        foreach ($chapData['lecons'] as $leconData) {
                            $sousChapitre = $chapitre->sousChapitres()->create([
                                'titre' => $leconData['titre'] ?? 'Leçon sans titre',
                                'resume' => $leconData['resume'] ?? 'Résumé non fourni.',
                                'contenu' => $leconData['contenu'] ?? '<p>Contenu non généré.</p>'
                            ]);

                            $quiz = Quiz::create([
                                'chapitre_id' => $chapitre->id,
                                'sous_chapitre_id' => $sousChapitre->id,
                                'titre' => 'Validation : ' . ($leconData['titre'] ?? 'Leçon')
                            ]);

                            // Analyse des questions générées par l'IA
                            if (isset($leconData['questions'])) {
                                foreach ($leconData['questions'] as $qData) {
                                    $question = $quiz->questions()->create([
                                        'texte_question' => $qData['question'] ?? 'Question manquante'
                                    ]);

                                    if (isset($qData['options'])) {
                                        foreach ($qData['options'] as $opt) {
                                            $question->reponses()->create([
                                                'texte_reponse' => $opt['texte'] ?? 'Option',
                                                'est_correcte' => $opt['correct'] ?? false
                                            ]);
                                        }
                                    }
                                }
                            } else {
                                // Fallback si l'IA oublie le quiz
                                $q = $quiz->questions()->create(['texte_question' => 'Avez-vous compris cette leçon ?']);
                                $q->reponses()->createMany([
                                    ['texte_reponse' => 'Oui', 'est_correcte' => true],
                                    ['texte_reponse' => 'Non', 'est_correcte' => false],
                                ]);
                            }
                        }
                    }
                }
                $this->command->info(" Cours {$sujet} inséré avec succès !");
            } else {
                $this->command->error(" Échec API pour {$sujet} : " . $response->body());
            }
        }
    }
}