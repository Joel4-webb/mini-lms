<?php

namespace App\Http\Controllers;

use App\Models\SousChapitre;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chapitre;


class SousChapitreController extends Controller
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
        $chapitre_id = $request->query('chapitre_id');
        return view('sous_chapitres.create', compact('chapitre_id'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'contenu' => 'required',
        'chapitre_id' => 'required|exists:chapitres,id'
    ]);

    $lecon = SousChapitre::create($validated);

    return redirect()->route('quizzes.create', ['sous_chapitre_id' => $lecon->id])
                     ->with('success', 'Leçon créée ! Créez maintenant le quiz associé.');
    }

    /**
     * Display the specified resource.
     */ 
    public function show(SousChapitre $sousChapitre)
    {
        $formation = $sousChapitre->chapitre->formation->load('chapitres.sousChapitres');
        return view('sous_chapitres.show', compact('sousChapitre', 'formation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SousChapitre $sousChapitre)
    {
        return view('sous_chapitres.edit', compact('sousChapitre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SousChapitre $sousChapitre)
    {
            $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required'
        ]);

        $sousChapitre->update($request->all());
        return redirect()->route('chapitres.show', $sousChapitre->chapitre_id)->with('success', 'Leçon mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SousChapitre $sousChapitre)
    {
        $chapitreId = $sousChapitre->chapitre_id;
        $sousChapitre->delete();
        return redirect()->route('chapitres.show', $chapitreId)->with('success', 'Leçon supprimée.');
    }
}
