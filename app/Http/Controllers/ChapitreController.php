<?php

namespace App\Http\Controllers;

use App\Models\Chapitre;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Formation;

class ChapitreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $formation_id = $request->query('formation_id');
        return view('chapitres.create', compact('formation_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'formation_id' => 'required|exists:formations,id'
        ]);

        $chapitre = Chapitre::create($validated);

        return redirect()->route('formations.show', $chapitre->formation_id)
                        ->with('success', 'Nouveau chapitre ajouté !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapitre $chapitre)
    {
        $chapitre->load('sousChapitres.quiz');
        return view('chapitres.show', compact('chapitre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapitre $chapitre)
    {
        $formations = Formation::all();
        return view('chapitres.edit', compact('chapitre', 'formations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapitre $chapitre)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formation_id' => 'required|exists:formations,id',
        ]);

        $chapitre->update($request->all());

        return redirect()->route('formations.index')->with('success', 'Chapitre mis à jour !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapitre $chapitre)
    {
        $chapitre->delete();
        return redirect()->route('formations.index')->with('success', 'Chapitre supprimé !');
    }
}
