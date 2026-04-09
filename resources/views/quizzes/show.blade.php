<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                {{-- FIL D'ARIANE (Breadcrumbs) pour la navigation --}}
                <nav class="flex items-center text-sm text-gray-400 mb-2">
                    <a href="{{ route('formations.index') }}" class="hover:text-indigo-600 transition">Formations</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <a href="{{ route('formations.show', $quiz->sousChapitre->chapitre->formation_id) }}" class="hover:text-indigo-600 transition">
                        {{ $quiz->sousChapitre->chapitre->formation->nom }}
                    </a>
                </nav>
                <h2 class="font-black text-2xl text-gray-800 leading-tight">
                    📝 Quiz : {{ $quiz->titre }}
                </h2>
            </div>

            {{-- BOUTON RETOUR RAPIDE À LA LEÇON --}}
            <a href="{{ route('sous-chapitres.show', $quiz->sous_chapitre_id) }}" 
               class="flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 hover:text-indigo-600 transition shadow-sm group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 0118 0z"></path>
                </svg>
                Retour à la leçon
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST" class="space-y-8">
                @csrf

                @foreach($quiz->questions as $index => $question)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                        
                        {{-- En-tête de la question --}}
                        <div class="p-6 bg-gray-50/50 border-b border-gray-100 flex items-start space-x-4">
                            <span class="flex-shrink-0 w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-indigo-100">
                                {{ $index + 1 }}
                            </span>
                            <h3 class="text-xl font-bold text-gray-800 pt-1 leading-tight">
                                {{ $question->texte_question }}
                            </h3>
                        </div>

                        {{-- Grille de réponses --}}
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($question->reponses as $reponse)
                                <label class="relative flex items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer transition-all hover:border-indigo-300 hover:bg-indigo-50/30 group">
                                    <input type="checkbox" 
                                           name="reponses[{{ $question->id }}][]" 
                                           value="{{ $reponse->id }}" 
                                           class="w-6 h-6 text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500 transition cursor-pointer">
                                    
                                    <span class="ml-4 text-gray-700 font-semibold group-hover:text-indigo-900 transition">
                                        {{ $reponse->texte_reponse }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Validation Finale --}}
                <div class="flex flex-col items-center pt-10 pb-20">
                    <button type="submit" 
                            class="group relative w-full md:w-auto px-16 py-5 bg-indigo-600 text-white text-xl font-black rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95">
                        <span class="flex items-center justify-center">
                            🚀 Envoyer mes réponses
                            <svg class="w-6 h-6 ml-3 transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </button>
                    <p class="mt-4 text-gray-400 text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        Toute sortie de la page réinitialisera vos réponses.
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>