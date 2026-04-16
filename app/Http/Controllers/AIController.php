<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Pour débugger si besoin

class AIController extends Controller
{
    /**
     * Génère le contenu d'un cours (leçon)
     */
    public function generate(Request $request)
    {
        try {
            $userPrompt = $request->input('prompt');
            $apiKey = config('services.groq.key') ?? env('GROQ_API_KEY');

            if (!$apiKey) {
                return response()->json(['success' => false, 'message' => 'Clé API GROQ manquante sur Railway']);
            }

            $response = Http::withToken($apiKey)->post("https://api.groq.com/openai/v1/chat/completions", [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'user', 'content' => $userPrompt]
                ],
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true, 
                    'reply' => $response->json('choices.0.message.content')
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Erreur API: ' . $response->status()]);

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
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $rawContent = $response->json('choices.0.message.content');
                $data = json_decode($rawContent, true);

                if (isset($data['questions'])) {
                    return response()->json(['success' => true, 'questions' => $data['questions']]);
                }
            }
            
            return response()->json(['success' => false, 'message' => 'L\'IA a renvoyé un format invalide']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}