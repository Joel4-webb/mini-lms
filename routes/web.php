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
    
    if ($user->role === 'admin') {
        $totalNotes = Note::count();
        $notesReussies = Note::where('note', '>=', 10)->count();

        $stats = [
            'total_eleves' => User::where('role', 'apprenant')->count(),
            'total_formations' => Formation::count(),
            'total_inscriptions' => \Illuminate\Support\Facades\DB::table('formation_user')->count(),
            'taux_reussite' => $totalNotes > 0 ? round(($notesReussies / $totalNotes) * 100) : 0,
        ];
        // Activité récente (Les 5 derniers quiz passés)
        $activite_recente = Note::with(['user', 'quiz'])->latest()->take(5)->get();
        
        return view('dashboard', compact('stats', 'activite_recente'));
        
    } else {
        $formations = $user->formations()->with('chapitres.sousChapitres')->get(); 
        $mesFormationsCount = $formations->count();

        $debutSemaine = now()->startOfWeek();
        $quizCetteSemaine = Note::where('user_id', $user->id)
                                ->where('created_at', '>=', $debutSemaine)
                                ->count();
        
        $objectifHebdo = 5;
        $progression = min(100, ($quizCetteSemaine / $objectifHebdo) * 100);
        $restant = max(0, $objectifHebdo - $quizCetteSemaine);

        $stats = [
            'moyenne' => Note::where('user_id', $user->id)->avg('note') ?? 0,
            'quiz_termines' => Note::where('user_id', $user->id)->count(),
            'mes_formations_count' => $mesFormationsCount,
            'dernieres_notes' => Note::where('user_id', $user->id)->with('quiz')->latest()->take(3)->get(),
            'quiz_cette_semaine' => $quizCetteSemaine,
            'progression_hebdo' => $progression,
            'restant_hebdo' => $restant,
            'total_formations' => $mesFormationsCount, 
        ];
        
        return view('dashboard', compact('stats', 'formations'));
    }
})->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // 1. DASHBOARD & LISTES
    Route::get('/formations', [FormationController::class, 'index'])->name('formations.index');
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');

    // 2. PROFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/quizzes/{quiz}/results', [QuizController::class, 'results'])->name('quizzes.results');

    // 3. ROUTES CRÉATEURS & APPRENANTS (Accessibles à tous les connectés)
    Route::resource('formations', FormationController::class)->except(['index', 'show']);
    Route::resource('chapitres', ChapitreController::class)->except(['show']);
    Route::resource('sous-chapitres', SousChapitreController::class)->except(['show']);
    
    // Outils IA pour tous (contrôlés par le solde de points)
    Route::post('/generate-ai', [AIController::class, 'generate'])->name('ai.generate');
    Route::post('/generate-quiz-ia', [AIController::class, 'generateQuizQuestions'])->name('ai.generate-quiz');
    Route::post('/ai-suggest-structure', [App\Http\Controllers\AIController::class, 'suggestStructure'])->name('ai.suggest');

    // 4. ROUTES STRICTEMENT ADMIN
    Route::middleware('admin')->group(function () {
        Route::resource('quizzes', QuizController::class)->except(['show', 'index']);
        Route::resource('notes', NoteController::class)->except(['index']);
        Route::post('/formations/{formation}/assign', [FormationController::class, 'assign'])->name('formations.assign');
        Route::patch('/formations/{formation}/toggle-featured', [FormationController::class, 'toggleFeatured'])->name('formations.toggle-featured');

        // Gestion des Questions
        Route::get('quizzes/{quiz}/questions/create', [QuizController::class, 'createQuestion'])->name('quizzes.questions.create');
        Route::post('quizzes/{quiz}/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
        
        // Liste des quiz et suppression
        Route::get('/admin/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
        Route::delete('/questions/{question}', [QuizController::class, 'destroyQuestion'])->name('questions.destroy');
    });

    // 5. WILDCARDS / PARAMÈTRES (À mettre TOUJOURS en dernier)
    Route::post('/formations/{formation}/enroll', [FormationController::class, 'enroll'])->name('formations.enroll');
    Route::get('/formations/{formation}', [FormationController::class, 'show'])->name('formations.show');
    Route::get('/chapitres/{chapitre}', [ChapitreController::class, 'show'])->name('chapitres.show');
    Route::get('/sous-chapitres/{sous_chapitre}', [SousChapitreController::class, 'show'])->name('sous-chapitres.show');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
});

require __DIR__.'/auth.php';