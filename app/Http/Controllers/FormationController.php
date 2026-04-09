<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // L'admin gère toutes les formations 
            $formations = Formation::all();
        } else {
            // L'apprenant ne voit que les formations auxquelles il est associé 
            $formations = $user->formations; 
        }

        return view('formations.index', compact('formations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('formations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            ['nom' => 'required|string|max:255',
            'niveau' => 'nullable|string',
            'description' => 'nullable|string',
            ]
        );

        \App\Models\Formation::create($request->all());

        return redirect()->route('formations.index')->with('succes', 'Formation crée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation)
    {
    $user = auth()->user();

        
        if ($user->role === 'apprenant' && !$user->formations->contains($formation->id)) {
            abort(403, "Accès refusé : Vous n'êtes pas inscrit à cette formation."); 
        }

        
        $formation->load('chapitres.sousChapitres');

        $firstLecon = $formation->chapitres->first()?->sousChapitres->first();

        // 4. RETOUR : Envoyer la formation et la première leçon à la vue
        return view('formations.show', compact('formation', 'firstLecon'));    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        $tousLesApprenants = User::where('role', 'apprenant')->get();
        return view('formations.edit', compact('formation', 'tousLesApprenants'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation)
    {
        $request->validate([
        'nom' => 'required|string|max:255',
        'niveau' => 'nullable|string',
        'description' => 'nullable|string',
        ]);

        $formation->update($request->all());

        return redirect()->route('formations.index')->with('success', 'Formation modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation)
    {
        $formation->delete();
        return redirect()->route('formations.index')->with('success', 'Formation supprimée !');
    }

    public function assign(Request $request, Formation $formation) {
        $formation->apprenants()->sync($request->apprenants);
        return back()->with('success', 'Inscriptions mises à jour !');
    }
}
