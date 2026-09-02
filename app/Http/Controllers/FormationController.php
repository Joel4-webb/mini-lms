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

        // 1. Les formations créées par l'utilisateur (L'admin voit tout, le client voit les siennes)
        if ($user->isAdmin()) {
            $mesCreations = Formation::all(); 
        } else {
            $mesCreations = $user->createdFormations; 
        }

        // 2. Les formations où l'utilisateur est inscrit en tant qu'élève
        $mesFormations = $user->formations; 

        // 3. Le Catalogue Ouvert : Toutes les formations publiques où l'utilisateur n'est pas encore inscrit
        $catalogue = Formation::where('is_public', true)
            ->whereDoesntHave('apprenants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('creator_id', '!=', $user->id)
            ->orderByDesc('is_featured') // Les mises en avant en premier
            ->latest()
            ->get();

        return view('formations.index', compact('mesFormations', 'mesCreations', 'catalogue'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // SYSTÈME DE GAMIFICATION : Vérification du quota et des points
        if (!$user->isAdmin() && $user->formations_created >= 2) {
            // Si le client n'a pas les 10 points requis, on le bloque
            if ($user->points_balance < 10) {
                return redirect()->route('formations.index')->with('error', 'Vous avez épuisé vos créations gratuites. Il vous faut 10 points IA pour forger un nouveau cours.');
            }
            
            // S'il a les points, on affiche un petit message préventif (nécessite d'ajouter l'alerte 'info' dans votre composant flash)
            session()->now('info', 'La création de cette formation déduira 10 points de votre solde IA.');
        }

        return view('formations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'niveau' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();

        if (!$user->isAdmin() && $user->formations_created >= 2) {
            if ($user->points_balance < 10) {
                return redirect()->route('formations.index')->with('error', 'Solde de points insuffisant.');
            }
            // On débite les 10 points
            $user->decrement('points_balance', 10);
        }

        // LOGIQUE : Les cours de l'admin sont publics d'office, ceux des clients sont privés par défaut.
        $isPublic = $user->isAdmin() ? true : false;

        Formation::create([
            'nom' => $request->nom,
            'niveau' => $request->niveau,
            'description' => $request->description,
            'creator_id' => $user->id,
            'is_public' => $isPublic,
        ]);

        // On incrémente le compteur de créations du client
        if (!$user->isAdmin()) {
            $user->increment('formations_created');
        }

        return redirect()->route('formations.index')->with('success', 'Formation générée avec succès !');
    }

    /**
     * NOUVELLE METHODE : Permet de s'inscrire à une formation du catalogue
     */
    public function enroll(Formation $formation) 
    {
        $user = auth()->user();

        if ($formation->is_public && !$user->formations->contains($formation->id)) {
            $user->formations()->attach($formation->id);
            return redirect()->route('formations.show', $formation)->with('success', 'Inscription réussie ! Bon apprentissage.');
        }

        return back()->with('error', 'Inscription impossible.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation)
    {
        $user = auth()->user();
        
        $isEnrolled = $user->formations->contains($formation->id);
        $isCreator = $formation->creator_id === $user->id;
        
        // Sécurité : Seuls l'admin, le créateur ou un élève inscrit peuvent voir le contenu détaillé
        if (!$user->isAdmin() && !$isCreator && !$isEnrolled) {
            // Si la formation est publique, on pourrait le rediriger vers une page vitrine (à créer)
            abort(403, "Accès refusé : Veuillez vous inscrire via le catalogue pour voir ce cours."); 
        }

        $formation->load('chapitres.sousChapitres');
        $firstLecon = $formation->chapitres->first()?->sousChapitres->first();

        return view('formations.show', compact('formation', 'firstLecon', 'isCreator'));    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        $user = auth()->user();
        
        // Seul le créateur ou l'admin peut modifier
        if (!$user->isAdmin() && $formation->creator_id !== $user->id) {
            abort(403, "Vous ne pouvez modifier que vos propres créations.");
        }

        $tousLesApprenants = User::where('role', 'apprenant')->get();
        return view('formations.edit', compact('formation', 'tousLesApprenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $formation->creator_id !== $user->id) {
            abort(403, "Action non autorisée.");
        }

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
        $user = auth()->user();
        if (!$user->isAdmin() && $formation->creator_id !== $user->id) {
            abort(403, "Action non autorisée.");
        }

        $formation->delete();
        return redirect()->route('formations.index')->with('success', 'Formation supprimée !');
    }

    public function assign(Request $request, Formation $formation) {
        if (!auth()->user()->isAdmin()) abort(403);
        $formation->apprenants()->sync($request->apprenants);
        return back()->with('success', 'Inscriptions mises à jour !');
    }

    public function toggleFeatured(Formation $formation)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $formation->update([
            'is_featured' => !$formation->is_featured
        ]);

        $message = $formation->is_featured ? 'Formation mise en avant !' : 'Mise en avant retirée.';
        return back()->with('success', $message);
    }
}