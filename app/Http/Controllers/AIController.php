<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    /**
     * Génère le contenu d'un cours (leçon) à partir d'un titre
     */
    public function generate(Request $request)
    {
        try {
            $userPrompt = $request->input('prompt');
            $apiKey = env('GROQ_API_KEY');

            if (!$apiKey) {
                return response()->json(['success' => false, 'message' => 'Clé API manquante dans le .env']);
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

            // Si l'API renvoie une erreur (ex: limite atteinte)
            return response()->json(['success' => false, 'message' => 'Erreur API: ' . $response->body()]);

        } catch (\Exception $e) {
            // Capture l'erreur PHP et la renvoie en JSON plutôt qu'en HTML
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Génère les questions du Quiz à partir du contenu de la leçon
     */
    public function generateQuizQuestions(Request $request)
    {
        $contenu = $request->input('contenu');
        $apiKey = env('GROQ_API_KEY');

        $prompt = "En tant qu'expert en pédagogie, crée 5 à 10 questions de quiz basées sur le texte fourni.
                RÈGLES STRICTES :
                1. Chaque question doit avoir EXACTEMENT 4 options de réponse.
                2. Pour chaque question, il peut y avoir UNE ou PLUSIEURS options correctes (true).
                3. Réponds UNIQUEMENT avec un objet JSON pur.
                
                Structure attendue :
                {
                    \"questions\": [
                    {
                        \"question\": \"Texte de la question\",
                        \"options\": [
                        {\"texte\": \"Option 1\", \"correct\": true},
                        {\"texte\": \"Option 2\", \"correct\": false},
                        {\"texte\": \"Option 3\", \"correct\": true},
                        {\"texte\": \"Option 4\", \"correct\": false}
                        ]
                    }
                    ]
                }
                
                Texte source : " . $contenu;

        $response = Http::withToken($apiKey)->post("https://api.groq.com/openai/v1/chat/completions", [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'response_format' => ['type' => 'json_object']
        ]);

        if ($response->successful()) {
            $data = json_decode($response->json('choices.0.message.content'), true);
            return response()->json(['success' => true, 'questions' => $data['questions'] ?? []]);
        }
        
        return response()->json(['success' => false]);
    }
}