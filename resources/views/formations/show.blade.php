<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Fil d'Ariane moderne -->
        <nav class="flex items-center text-sm font-medium text-slate-500 mb-8 gap-2">
            <a href="{{ route('formations.index') }}" class="hover:text-indigo-600 transition-colors">Catalogue</a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">{{ $formation->nom }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- COLONNE GAUCHE : PROGRAMME -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-100 pb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800">Programme du cours</h2>
                            <p class="text-sm text-slate-500 mt-1">Progressez étape par étape à votre rythme.</p>
                        </div>
                        
                        @if(auth()->user()->isAdmin() || (isset($isCreator) && $isCreator))
                            <a href="{{ route('chapitres.create', ['formation_id' => $formation->id]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-full hover:bg-indigo-700 transition-colors shadow-sm">
                                + Ajouter un chapitre
                            </a>
                        @endif
                    </div>

                    <div class="space-y-6">
                        @forelse($formation->chapitres as $index => $chapitre)
                            @php
                                $isLocked = false;
                                if ($index > 0 && !auth()->user()->isAdmin() && (!isset($isCreator) || !$isCreator)) {
                                    $chapitrePrecedent = $formation->chapitres[$index - 1];
                                    $quizPrecedent = $chapitrePrecedent->sousChapitres->flatMap->quiz->first() ?? null;
                                    if ($quizPrecedent) {
                                        $aReussi = auth()->user()->completedQuizzes()->where('quiz_id', $quizPrecedent->id)->where('is_passed', true)->exists();
                                        $isLocked = !$aReussi;
                                    }
                                }
                            @endphp

                            <div class="bg-slate-50/50 rounded-2xl p-5 border border-slate-100 relative">
                                
                                <!-- En-tête du chapitre -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full {{ $isLocked ? 'bg-slate-200 text-slate-500' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center font-bold text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        <h3 class="font-bold text-base {{ $isLocked ? 'text-slate-400' : 'text-slate-800' }}">
                                            {{ $chapitre->titre }}
                                            @if($isLocked) <span class="ml-2 text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Verrouillé 🔒</span> @endif
                                        </h3>
                                    </div>
                                    
                                    @if(auth()->user()->isAdmin() || (isset($isCreator) && $isCreator))
                                        <div class="flex items-center gap-3 text-xs font-semibold">
                                            <a href="{{ route('chapitres.show', $chapitre->id) }}" class="text-slate-500 hover:text-indigo-600 transition-colors">Gérer</a>
                                            <form action="{{ route('chapitres.destroy', $chapitre->id) }}" method="POST" onsubmit="return confirm('Supprimer ce chapitre ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">Supprimer</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                                <!-- Liste des leçons -->
                                <div class="space-y-2 pl-4">
                                    @forelse($chapitre->sousChapitres as $lecon)
                                        @if($isLocked)
                                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-100/60 opacity-60">
                                                <span class="text-sm font-medium text-slate-400">{{ $lecon->titre }}</span>
                                            </div>
                                        @else
                                            <a href="{{ route('sous-chapitres.show', $lecon->id) }}" class="flex items-center justify-between p-3 rounded-xl bg-white hover:bg-indigo-50/50 border border-slate-100 hover:border-indigo-100 transition-all group shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs group-hover:bg-indigo-600 group-hover:text-white transition-colors">▶</div>
                                                    <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-900">{{ $lecon->titre }}</span>
                                                </div>
                                                <span class="text-xs text-slate-400 group-hover:text-indigo-600 font-medium">Accéder</span>
                                            </a>
                                        @endif
                                    @empty
                                        <p class="text-xs text-slate-400 italic pl-2">Aucune leçon pour l'instant.</p>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="p-8 border-2 border-dashed border-slate-200 rounded-2xl text-center">
                                <p class="text-slate-500 text-sm font-medium">Aucun chapitre disponible.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- COLONNE DROITE : INFORMATIONS DU COURS -->
            <div>
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 sticky top-8 space-y-6">
                    <div>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3 inline-block">
                            Formation
                        </span>
                        <h3 class="text-xl font-bold text-slate-800 leading-snug">{{ $formation->nom }}</h3>
                    </div>
                    
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $formation->description ?? 'Apprenez les concepts clés de cette formation guidé par l\'intelligence artificielle.' }}
                    </p>
                    
                    <div class="space-y-3 pt-4 border-t border-slate-100 text-sm">
                        <div class="flex justify-between items-center text-slate-600">
                            <span class="font-medium">Chapitres</span>
                            <span class="font-bold text-slate-800">{{ $formation->chapitres->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span class="font-medium">Créateur</span>
                            <span class="font-bold text-indigo-600">{{ $formation->creator->name ?? 'Admin' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span class="font-medium">Récompense</span>
                            <span class="font-bold text-emerald-600">+50 PTS / Quiz</span>
                        </div>
                    </div>

                    @php
                        $premiereLecon = $formation->chapitres->first()?->sousChapitres->first();
                    @endphp
                    
                    @if($premiereLecon)
                        <a href="{{ route('sous-chapitres.show', $premiereLecon->id) }}" class="w-full flex items-center justify-center py-3.5 bg-indigo-600 text-white font-semibold text-sm rounded-full hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                            Démarrer le cours
                        </a>
                    @else
                        <button disabled class="w-full py-3.5 bg-slate-100 text-slate-400 font-semibold text-sm rounded-full cursor-not-allowed">
                            Bientôt disponible
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>