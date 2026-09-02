<x-app-layout>

    @php
        $isCreatorOrAdmin = auth()->user()->isAdmin() || $chapitre->formation->creator_id === auth()->id();
    @endphp
    <div class="max-w-7xl mx-auto">
        
        {{-- En-tête de la page (Sorti du x-slot pour s'afficher correctement) --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                {{-- Fil d'Ariane --}}
                <nav class="flex items-center text-sm font-bold text-gray-400 mb-4 gap-2">
                    <a href="{{ route('formations.index') }}" class="hover:text-lms-dark transition-colors">Catalogue</a>
                    <span>/</span>
                    <a href="{{ route('formations.show', $chapitre->formation_id) }}" class="hover:text-lms-dark transition-colors">{{ $chapitre->formation->nom }}</a>
                    <span>/</span>
                    <span class="text-lms-dark">Chapitre</span>
                </nav>
                
                <h1 class="text-4xl font-black text-lms-dark flex items-center gap-3">
                    <span class="font-pixel uppercase tracking-wide">{{ $chapitre->titre }}</span>
                </h1>
                <p class="text-sm font-bold text-gray-500 mt-2">Gestion des leçons et des évaluations du chapitre.</p>
            </div>

            @if($isCreatorOrAdmin)
                <a href="{{ route('sous-chapitres.create', ['chapitre_id' => $chapitre->id]) }}" 
                   class="bg-lms-red text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-red-800 transition-colors shadow-md shrink-0">
                    + Nouvelle Leçon
                </a>
            @endif
        </div>

        {{-- Liste des leçons (Remplacement du tableau par des cartes flexbox) --}}
        <div class="space-y-4 mb-10">
            @forelse($chapitre->sousChapitres as $lecon)
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:shadow-md transition-shadow group">
                    
                    {{-- Titre de la leçon --}}
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-lms-red/10 transition-colors">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-lms-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <a href="{{ route('sous-chapitres.show', $lecon->id) }}" class="text-xl font-bold text-lms-dark hover:text-lms-red transition-colors">
                            {{ $lecon->titre }}
                        </a>
                    </div>

                    {{-- Évaluation et Actions --}}
                    <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">
                        
                        {{-- Statut du Quiz --}}
                        <div class="text-center">
                            @if($lecon->quiz)
                                <div class="flex flex-col items-center">
                                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-1">
                                        Quiz disponible
                                    </span>
                                    @if($isCreatorOrAdmin)
                                        <a href="{{ route('quizzes.questions.create', $lecon->quiz->id) }}" class="text-xs font-bold text-gray-400 hover:text-lms-dark transition-colors">
                                            Configurer les questions
                                        </a>
                                    @endif
                                </div>
                            @else
                                @if($isCreatorOrAdmin)
                                    <a href="{{ route('quizzes.create', ['sous_chapitre_id' => $lecon->id]) }}" class="text-xs font-bold text-lms-red hover:text-red-800 transition-colors border border-lms-red/30 px-3 py-1 rounded-full">
                                        + Créer un quiz
                                    </a>
                                @else
                                    <span class="text-xs font-bold text-gray-400 italic">Pas de quiz</span>
                                @endif
                            @endif
                        </div>

                        {{-- Actions Admin --}}
                        @if($isCreatorOrAdmin)
                            <div class="flex items-center gap-2 border-l border-gray-100 pl-6">
                                <a href="{{ route('sous-chapitres.edit', $lecon->id) }}" class="p-2 text-gray-400 hover:text-lms-dark transition-colors" title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('sous-chapitres.destroy', $lecon->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette leçon ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-lms-red transition-colors" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                {{-- État vide revisité avec un appel à l'action direct --}}
                <div class="border-2 border-dashed border-gray-300 rounded-[2.5rem] p-12 text-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-lms-dark mb-2">Aucune leçon publiée</h3>
                    <p class="text-sm text-gray-500 mb-6">Ce chapitre est encore vide. Commencez par ajouter du contenu pédagogique.</p>
                    @if($isCreatorOrAdmin)
                        <a href="{{ route('sous-chapitres.create', ['chapitre_id' => $chapitre->id]) }}" 
                           class="inline-block bg-lms-dark text-white px-6 py-3 rounded-full text-sm font-bold hover:bg-black transition-colors shadow-md">
                            Ajouter ma première leçon
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <div class="text-center">
            <a href="{{ route('formations.show', $chapitre->formation_id) }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-lms-dark transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour au programme complet
            </a>
        </div>

    </div>
</x-app-layout>