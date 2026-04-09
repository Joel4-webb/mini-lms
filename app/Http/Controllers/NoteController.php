<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
   public function index()
    {
        $user = auth()->user();
        $notes = \App\Models\Note::where('user_id', $user->id)
                    ->with('quiz.sousChapitre.chapitre.formation')
                    ->latest()
                    ->get();

        $stats = [
            'moyenne_generale' => $notes->avg('note') ?? 0,
            'quiz_reussis' => $notes->where('note', '>=', 10)->count(),
            'total_quiz' => $notes->count(),
        ];

        return view('notes.index', compact('notes', 'stats'));
    }

    public function create()
    {
        $quizzes = \App\Models\Quiz::with('sousChapitre')->get(); 
        $apprenants = User::where('role', 'apprenant')->get();
        return view('notes.create', compact('apprenants', 'quizzes'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'user_id' => 'required|exists:users,id',
        'quiz_id' => 'required|exists:quizzes,id', 
        'note'    => 'required|numeric|min:0|max:20', 
    ]);

    Note::create($request->all());

    return redirect()->route('notes.index')->with('success', 'Note ajoutée avec succès.');
    }
}