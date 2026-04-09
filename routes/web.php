<?php

use App\Http\Controllers\FormationController;
use App\Http\Controllers\ChapitreController;
use App\Http\Controllers\SousChapitreController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AIController;

use App\Models\User;
use App\Models\Formation;
use App\Models\Note;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // On prépare le tableau de statistiques selon le rôle
    if ($user->role === 'admin') {
        $stats = [
            'total_eleves' => User::where('role', 'apprenant')->count(),
            'total_formations' => Formation::count(),
            // On récupère les 5 dernières notes globales pour l'admin
            'dernieres_notes' => Note::with(['user', 'quiz'])->latest()->take(5)->get(),

            'quiz_cette_semaine' => 0,
            'progression_hebdo' => 0,
            'restant_hebdo' => 0,
        ];
    } else {
        // On récupère uniquement le nombre de formations associées à cet apprenant 
        $mesFormationsCount = $user->formations()->count();

        // Calcul de la progression pour la semaine en cours
        $debutSemaine = now()->startOfWeek();
        $quizCetteSemaine = Note::where('user_id', $user->id)
                                ->where('created_at', '>=', $debutSemaine)
                                ->count();
        
        $objectifHebdo = 5; // Objectif de quiz par semaine
        $progression = min(100, ($quizCetteSemaine / $objectifHebdo) * 100);
        $restant = max(0, $objectifHebdo - $quizCetteSemaine);

        $stats = [
            'moyenne' => Note::where('user_id', $user->id)->avg('note') ?? 0, //
            'quiz_termines' => Note::where('user_id', $user->id)->count(),
            'mes_formations_count' => $mesFormationsCount, // Preuve de l'association apprenant/formation 
            'dernieres_notes' => Note::where('user_id', $user->id)->with('quiz')->latest()->take(3)->get(), //
            'quiz_cette_semaine' => $quizCetteSemaine,
            'progression_hebdo' => $progression,
            'restant_hebdo' => $restant,
        ];
    }

    // On passe les statistiques à la vue pour affichage
    return view('dashboard', compact('stats'));
})->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // 1. DASHBOARD & LISTES (Toujours en haut)

    Route::get('/formations', [FormationController::class, 'index'])->name('formations.index');
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');

    // 2. PROFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/quizzes/{quiz}/results', [QuizController::class, 'results'])->name('quizzes.results');

    // 3. ROUTES RÉSERVÉES À L'ADMIN (Placées AVANT les wildcards)
    Route::middleware('admin')->group(function () {
        Route::resource('formations', FormationController::class)->except(['index', 'show']);
        Route::resource('chapitres', ChapitreController::class)->except(['show']);
        Route::resource('sous-chapitres', SousChapitreController::class)->except(['show']);
        
        // CORRECTION ICI : Ajout de 'index' dans except pour éviter le doublon avec la route manuelle plus bas
        Route::resource('quizzes', QuizController::class)->except(['show', 'index']);
        
        Route::resource('notes', NoteController::class)->except(['index']);
        Route::post('/formations/{formation}/assign', [FormationController::class, 'assign'])->name('formations.assign');

        // Gestion des Questions
        Route::get('quizzes/{quiz}/questions/create', [QuizController::class, 'createQuestion'])->name('quizzes.questions.create');
        Route::post('quizzes/{quiz}/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
        
        // Outils IA
        Route::post('/generate-ai', [AIController::class, 'generate'])->name('ai.generate');
        Route::post('/generate-quiz-ia', [AIController::class, 'generateQuizQuestions'])->name('ai.generate-quiz');
        
        // Liste des quiz et suppression (C'est cette route qui définit officiellement quizzes.index)
        Route::get('/admin/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
        Route::delete('/questions/{question}', [QuizController::class, 'destroyQuestion'])->name('questions.destroy');
    });

    // 4. WILDCARDS / PARAMÈTRES (À mettre TOUJOURS en dernier)
    Route::get('/formations/{formation}', [FormationController::class, 'show'])->name('formations.show');
    Route::get('/chapitres/{chapitre}', [ChapitreController::class, 'show'])->name('chapitres.show');
    Route::get('/sous-chapitres/{sous_chapitre}', [SousChapitreController::class, 'show'])->name('sous-chapitres.show');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
});

require __DIR__.'/auth.php';