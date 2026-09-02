<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Chapitre;
use App\Models\Question;
use App\Models\Reponse;
use App\Models\Note;
use App\Models\SousChapitre;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function create()
    {
        $chapitres = Chapitre::all();
        return view('quizzes.create', compact('chapitres'));
    }

    public function index()
    {
        $quizzes = Quiz::with('sousChapitre', 'questions')->paginate(9); 
        return view('quizzes.index', compact('quizzes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'sous_chapitre_id' => 'required|exists:sous_chapitres,id',
        ]);

        $sousChapitre = \App\Models\SousChapitre::findOrFail($validated['sous_chapitre_id']);

        $quiz = Quiz::create([
            'titre' => $validated['titre'],
            'sous_chapitre_id' => $validated['sous_chapitre_id'],
            'chapitre_id' => $sousChapitre->chapitre_id,
        ]);

        return redirect()->route('quizzes.questions.create', $quiz->id)
                         ->with('success', 'Quiz créé ! Ajoutez maintenant entre 5 et 10 questions.');
    }

    public function show(Quiz $quiz)
    {
        $user = auth()->user();
        
        $quiz->load('sousChapitre.chapitre.formation', 'questions.reponses');
        $formation_id = $quiz->sousChapitre->chapitre->formation_id;

        if ($user->role === 'apprenant' && !$user->formations->contains($formation_id)) {
            abort(403, "Accès refusé : Vous n'êtes pas inscrit à la formation de ce quiz.");
        }

        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $chapitres = Chapitre::all();
        return view('quizzes.edit', compact('quiz', 'chapitres'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'chapitre_id' => 'required|exists:chapitres,id'
        ]);

        $quiz->update($validated);
        return redirect()->route('quizzes.index')->with('success', 'Quiz mis à jour !');
    }

    /**
     * Enregistre les questions et vérifie le quota (5 à 10 questions)
     */
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.texte' => 'required|string',
            'questions.*.reponses' => 'required|array',
        ]);

        // Calcul du nombre total après ajout
        $countExistant = $quiz->questions()->count();
        $countNouveau = count($request->questions);
        $totalFinal = $countExistant + $countNouveau;

        // Validation du quota imposé par le sujet
        if ($totalFinal < 5 || $totalFinal > 10) {
            return back()->with('error', "Un quiz doit comporter entre 5 et 10 questions. (Actuellement : $totalFinal)");
        }

        foreach ($request->questions as $qData) {
            $question = $quiz->questions()->create([
                'texte_question' => $qData['texte']
            ]);

            foreach ($qData['reponses'] as $rData) {
                $question->reponses()->create([
                    'texte_reponse' => $rData['texte'],
                    'est_correcte' => isset($rData['correct']) && $rData['correct'] == "1"
                ]);
            }
        }

        return redirect()->route('formations.index')->with('success', "Quiz enregistré avec $totalFinal questions !");
    }

    public function createQuestion(Quiz $quiz)
    {
        $quiz->load('questions.reponses'); 
        return view('quizzes.questions_create', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $reponsesEleve = $request->input('reponses') ?? [];
        $score = 0;
        $total = $quiz->questions->count();
        $detailResultats = [];

        // 1. Calcul du score (Logique existante)
        foreach ($quiz->questions as $question) {
            $choisis = $reponsesEleve[$question->id] ?? [];
            $choisisIds = is_array($choisis) ? array_map('intval', $choisis) : [intval($choisis)];

            $bonsIds = $question->reponses()
                ->where('est_correcte', true)
                ->pluck('id')
                ->map(fn($id) => intval($id))
                ->toArray();

            sort($choisisIds);
            sort($bonsIds);
            
            $estCorrect = ($choisisIds === $bonsIds);
            if ($estCorrect) { $score++; }

            $detailResultats[] = [
                'question' => $question->texte_question,
                'correct' => $estCorrect,
                'votre_reponse' => \App\Models\Reponse::whereIn('id', $choisisIds)->pluck('texte_reponse')->toArray(),
                'la_bonne_reponse' => \App\Models\Reponse::whereIn('id', $bonsIds)->pluck('texte_reponse')->toArray()
            ];
        }

        // 2. Calcul automatique de la note sur 20
        $noteSur20 = ($total > 0) ? ($score / $total) * 20 : 0;
        
        // 3. MOTEUR DE GAMIFICATION
        $user = auth()->user();
        $seuilReussite = 16; // 80% (16/20) pour valider le chapitre
        $isPassed = $noteSur20 >= $seuilReussite;
        $pointsGagnes = 0;

        // Anti-triche : On vérifie si l'utilisateur a déjà réussi ce quiz auparavant
        $alreadyPassed = $user->completedQuizzes()
                              ->where('quiz_id', $quiz->id)
                              ->where('is_passed', true)
                              ->exists();

        // S'il réussit pour la première fois, on lui donne 50 points
        if ($isPassed && !$alreadyPassed) {
            $pointsGagnes = 50;
            $user->increment('points_balance', $pointsGagnes);
        }

        // 4. Enregistrements en Base de données
        // Historique Gamification
        $user->completedQuizzes()->attach($quiz->id, [
            'score' => $noteSur20,
            'is_passed' => $isPassed
        ]);

        // Historique des notes global (existant)
        Note::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'note'    => $noteSur20,
            'details' => $detailResultats 
        ]);

        return redirect()->route('quizzes.results', $quiz->id)->with([
            'score' => $score,
            'total' => $total,
            'noteSur20' => $noteSur20,
            'details' => $detailResultats,
            'pointsGagnes' => $pointsGagnes,
            'isPassed' => $isPassed
        ]);
    }

    public function results(Request $request, Quiz $quiz)
    {
        $user = auth()->user();
        $targetUserId = $user->id;

        if ($user->role === 'admin' && $request->has('user_id')) {
            $targetUserId = $request->query('user_id');
        }
        
        $note = Note::where('quiz_id', $quiz->id)
            ->where('user_id', $targetUserId)
            ->latest()
            ->first();

        $details = $note ? $note->details : [];

        return view('quizzes.results', compact('quiz', 'note', 'details'));
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->back()->with('success', 'Le quiz a été supprimé.');
    }

    public function destroyQuestion(Question $question)
    {
        $question->delete(); 
        return back()->with('success', 'Question supprimée.');
    }
}