<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-slate-50/30 min-h-screen">
        
        <!-- EN-TÊTE -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 border-b border-slate-200 pb-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Résultats du Quiz</h1>
                <p class="text-sm font-bold text-slate-500 mt-2 uppercase tracking-widest">{{ $quiz->titre }}</p>
            </div>
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('notes.index') }}" 
                   class="bg-white border border-slate-200 text-slate-700 px-6 py-3 font-bold text-sm rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
                    Retour au suivi
                </a>
            @else
                <a href="{{ route('formations.show', $quiz->sousChapitre->chapitre->formation_id) }}" 
                   class="bg-white border border-slate-200 text-slate-700 px-6 py-3 font-bold text-sm rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
                    Retour au cours
                </a>
            @endif
        </div>

        @php
            $scoreFinal = session('score') ?? ($note ? ($note->note * $quiz->questions->count() / 20) : 0);
            $totalQuestions = session('total') ?? $quiz->questions->count();
            $noteSur20 = session('noteSur20') ?? ($note ? $note->note : 0);
            $isPassed = session('isPassed') ?? ($noteSur20 >= 16);
            $pointsGagnes = session('pointsGagnes') ?? 0;
        @endphp

        <!-- BANNIÈRE DE GAMIFICATION ADAPTATIVE -->
        @if($pointsGagnes > 0)
            <div class="bg-indigo-600 text-white p-8 rounded-3xl mb-10 text-center shadow-lg shadow-indigo-200 transform hover:-translate-y-1 transition-transform">
                <h2 class="font-black text-4xl mb-2 tracking-tight">+ {{ $pointsGagnes }} POINTS !</h2>
                <p class="font-bold uppercase tracking-widest text-indigo-100 text-sm">
                    {{ auth()->user()->role === 'admin' ? "L'élève a validé ce niveau et remporté la récompense." : "Niveau validé avec succès." }}
                </p>
            </div>
        @elseif($isPassed)
            <div class="bg-slate-900 text-white p-8 rounded-3xl mb-10 text-center shadow-lg shadow-slate-200">
                <h2 class="font-black text-3xl mb-2 tracking-tight">CHAPITRE VALIDÉ</h2>
                <p class="font-bold uppercase tracking-widest text-slate-300 text-sm">
                    {{ auth()->user()->role === 'admin' ? "L'apprenant a déjà validé cette étape." : "Tu as déjà récupéré les points de ce niveau." }}
                </p>
            </div>
        @else
            <div class="bg-white border-2 border-slate-900 text-slate-900 p-8 rounded-3xl mb-10 text-center shadow-sm">
                <h2 class="font-black text-3xl mb-2 tracking-tight">ÉCHEC DU NIVEAU</h2>
                <p class="font-bold uppercase tracking-widest text-slate-500 text-sm">
                    {{ auth()->user()->role === 'admin' ? "L'apprenant n'a pas atteint le seuil de 16/20 requis." : "Il te faut au moins 16/20 pour débloquer les points." }}
                </p>
            </div>
        @endif

        <!-- SCORE GLOBAL -->
        <div class="bg-white border border-slate-100 rounded-[2.5rem] p-10 text-center mb-16 shadow-sm">
            <div class="inline-flex items-end justify-center gap-2 mb-4">
                <span class="text-7xl font-black text-indigo-600 tracking-tighter">{{ round($noteSur20, 1) }}</span>
                <span class="text-3xl font-bold text-slate-400 mb-2">/20</span>
            </div>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-sm mt-2">
                Score : <span class="text-slate-900">{{ $scoreFinal }}</span> sur {{ $totalQuestions }} réponses correctes
            </p>
        </div>

        <!-- DÉTAIL DES RÉPONSES -->
        <h3 class="font-black text-xl text-slate-800 mb-6 tracking-tight border-b border-slate-200 pb-4">
            Détail de la correction
        </h3>

        <div class="space-y-6 mb-16">
            @php $details = $details ?? []; @endphp
            @forelse($details as $res)
                <div class="bg-white border {{ ($res['correct'] ?? false) === true ? 'border-emerald-100' : (($res['correct'] ?? false) === false ? 'border-rose-100' : 'border-slate-200') }} rounded-3xl p-6 flex flex-col md:flex-row gap-6 shadow-sm hover:shadow-md transition-shadow">
                    
                    <!-- Statut (Badges Soft UI) -->
                    <div class="flex-shrink-0 mt-1">
                        @if(($res['correct'] ?? false) === true)
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 font-bold text-xs px-3 py-1.5 rounded-lg uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Correct
                            </span>
                        @elseif(($res['correct'] ?? false) === false)
                            <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-600 font-bold text-xs px-3 py-1.5 rounded-lg uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Incorrect
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-500 font-bold text-xs px-3 py-1.5 rounded-lg uppercase tracking-wider">
                                Archivé
                            </span>
                        @endif
                    </div>

                    <!-- Question et Réponses -->
                    <div class="flex-1">
                        <p class="font-bold text-lg text-slate-900 mb-4">{{ $res['question'] }}</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Réponse soumise -->
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                                <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1">
                                    {{ auth()->user()->role === 'admin' ? "Réponse de l'élève" : "Votre réponse" }}
                                </span>
                                <span class="font-semibold text-slate-700">
                                    {{ is_array($res['votre_reponse']) ? implode(', ', $res['votre_reponse']) : ($res['votre_reponse'] ?: 'Aucune') }}
                                </span>
                            </div>

                            <!-- Bonne réponse (affichée si erreur ou historique) -->
                            @if(($res['correct'] ?? false) !== true)
                                <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-4">
                                    <span class="block text-[10px] uppercase font-bold text-indigo-500 tracking-widest mb-1">La bonne réponse</span>
                                    <span class="font-semibold text-indigo-900">
                                        {{ is_array($res['la_bonne_reponse']) ? implode(', ', $res['la_bonne_reponse']) : $res['la_bonne_reponse'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-slate-50 border border-dashed border-slate-200 rounded-3xl p-8 text-center">
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun détail disponible.</p>
                </div>
            @endforelse
        </div>

        <!-- ACTIONS DE FIN ADAPTATIVES -->
        <div class="flex flex-col md:flex-row gap-4 justify-center">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('notes.index') }}" 
                   class="bg-slate-900 hover:bg-slate-800 text-white text-center font-bold py-4 px-8 rounded-2xl shadow-sm transition-colors flex-1 md:flex-none">
                    Retour aux évaluations
                </a>
            @else
                <a href="{{ route('formations.show', $quiz->sousChapitre->chapitre->formation_id) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-4 px-8 rounded-2xl shadow-sm shadow-indigo-200 transition-colors flex-1 md:flex-none">
                    Continuer le cours
                </a>
                <a href="{{ route('formations.index') }}" 
                   class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-center font-bold py-4 px-8 rounded-2xl shadow-sm transition-colors flex-1 md:flex-none">
                    Retour au catalogue
                </a>
            @endif
        </div>

    </div>
</x-app-layout>