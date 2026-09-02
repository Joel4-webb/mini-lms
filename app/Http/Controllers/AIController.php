<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; 

class AIController extends Controller
{
    /**
     * Génère le contenu d'un cours (leçon)
     */
    public function generate(Request $request)
    {
        try {
            $user = auth()->user();
            $coutCours = 10;

            if ($user && !$user->isAdmin() && $user->points_balance < $coutCours) {
                return response()->json(['success' => false, 'message' => "Fonds insuffisants. La génération coûte $coutCours points."]);
            }

            $userPrompt = $request->input('prompt');
            $apiKey = config('services.groq.key') ?? env('GROQ_API_KEY');

            if (!$apiKey) {
                return response()->json(['success' => false, 'message' => 'Clé API GROQ manquante sur Railway']);
            }

            $response = Http::withToken($apiKey)->post("https://api.groq.com/openai/v1/chat/completions", [
                'model' => 'openai/gpt-oss-120b',
                'messages' => [
                    ['role' => 'user', 'content' => $userPrompt]
                ],
            ]);

            if ($response->successful()) {
                if ($user && !$user->isAdmin()) {
                    $user->decrement('points_balance', $coutCours);
                }

                return response()->json([
                    'success' => true, 
                    'reply' => $response->json('choices.0.message.content')
                ]);
            }

            $erreurDetaillee = $response->json('error.message') ?? $response->body();
            
            return response()->json([
                'success' => false, 
                'message' => 'Détail de l\'erreur : ' . $erreurDetaillee
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Génère les questions du Quiz
     */
    public function generateQuizQuestions(Request $request)
    {
        try {
            $user = auth()->user();
            $coutQuiz = 5;

            if ($user && !$user->isAdmin() && $user->points_balance < $coutQuiz) {
                return response()->json(['success' => false, 'message' => "Fonds insuffisants. La génération d'un quiz coûte $coutQuiz points."]);
            }

            $contenu = $request->input('contenu');
            $apiKey = config('services.groq.key') ?? env('GROQ_API_KEY');

            if (!$apiKey) {
                return response()->json(['success' => false, 'message' => 'Clé API manquante']);
            }

            if (empty($contenu)) {
                return response()->json(['success' => false, 'message' => 'Le contenu du cours est vide']);
            }

            $prompt = "Tu es un expert en pédagogie. Analyse le texte suivant et crée un quiz de 5 à 10 questions.
                    RÈGLES :
                    1. Réponds UNIQUEMENT en JSON pur. Pas de texte avant ou après.
                    2. Chaque question doit avoir 4 options.
                    3. Au moins 2 questions sur 5 DOIVENT avoir PLUSIEURS réponses correctes.
                    4. 'correct' doit être un booléen (true/false).
                    5. Le quiz doit etre en français.
                    
                    Format attendu :
                    {
                        \"questions\": [
                            {
                                \"question\": \"La question ?\",
                                \"options\": [
                                    {\"texte\": \"Option A\", \"correct\": true},
                                    {\"texte\": \"Option B\", \"correct\": false},
                                    {\"texte\": \"Option C\", \"correct\": false},
                                    {\"texte\": \"Option D\", \"correct\": false}
                                ]
                            }
                        ]
                    }

                    Texte à analyser : " . $contenu;

            $response = Http::withToken($apiKey)->timeout(30)->post("https://api.groq.com/openai/v1/chat/completions", [
                'model' => 'openai/gpt-oss-120b',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $rawContent = $response->json('choices.0.message.content');
                $data = json_decode($rawContent, true);

                if (isset($data['questions'])) {
                    if ($user && !$user->isAdmin()) {
                        $user->decrement('points_balance', $coutQuiz);
                    }
                    return response()->json(['success' => true, 'questions' => $data['questions']]);
                }
            }
            
            return response()->json(['success' => false, 'message' => 'L\'IA a renvoyé un format invalide']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Génère une arborescence de cours suggérée (Conseiller IA)
     */
    public function suggestStructure(Request $request)
    {
        try {
            $user = auth()->user();
            $coutConseil = 5;

            if ($user && !$user->isAdmin() && $user->points_balance < $coutConseil) {
                return response()->json(['success' => false, 'message' => "Fonds insuffisants. Un conseil IA coûte $coutConseil points."]);
            }

            $idee = $request->input('idee');
            $apiKey = config('services.groq.key') ?? env('GROQ_API_KEY');

            if (!$apiKey) {
                return response()->json(['success' => false, 'message' => 'Clé API manquante']);
            }

            if (empty($idee)) {
                return response()->json(['success' => false, 'message' => 'L\'idée de cours est vide']);
            }

            $prompt = "Tu es un expert en ingénierie pédagogique. Un utilisateur veut créer une formation sur : '{$idee}'.
                    Génère une structure logique.
                    RÈGLES :
                    1. Réponds UNIQUEMENT en JSON pur.
                    2. 2 à 3 chapitres maximum, avec 2 sous-chapitres (leçons) chacun.
                    
                    Format attendu :
                    {
                        \"titre_suggere\": \"Titre accrocheur\",
                        \"chapitres\": [
                            {
                                \"titre\": \"Nom du chapitre\",
                                \"sous_chapitres\": [\"Leçon 1\", \"Leçon 2\"]
                            }
                        ]
                    }";

            $response = Http::withToken($apiKey)->timeout(30)->post("https://api.groq.com/openai/v1/chat/completions", [
                'model' => 'openai/gpt-oss-120b',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $rawContent = $response->json('choices.0.message.content');
                $data = json_decode($rawContent, true);

                if ($user && !$user->isAdmin()) {
                    $user->decrement('points_balance', $coutConseil);
                }

                return response()->json(['success' => true, 'structure' => $data]);
            }
            
            $erreurDetaillee = $response->json('error.message') ?? $response->body();
            
            return response()->json([
                'success' => false, 
                'message' => 'Erreur API : ' . $erreurDetaillee
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}