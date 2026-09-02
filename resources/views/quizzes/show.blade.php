<x-app-layout>
    <div class="bg-white border-b border-slate-200 px-4 py-6 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <nav class="flex items-center text-sm text-slate-500 mb-2 font-medium">
                    <a href="{{ route('formations.index') }}" class="hover:text-indigo-600 transition">Formations</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <a href="{{ route('formations.show', $quiz->sousChapitre->chapitre->formation_id) }}" class="hover:text-indigo-600 transition">
                        {{ $quiz->sousChapitre->chapitre->formation->nom }}
                    </a>
                </nav>
                <h2 class="font-black text-3xl text-slate-900 leading-tight tracking-tight">
                    Évaluation : {{ $quiz->titre }}
                </h2>
            </div>

            <a href="{{ route('sous-chapitres.show', $quiz->sous_chapitre_id) }}" 
               class="flex items-center px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à la leçon
            </a>
        </div>
    </div>

    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST" class="space-y-8">
                @csrf

                @foreach($quiz->questions as $index => $question)
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        
                        <!-- En-tête de la question -->
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex items-start space-x-4">
                            <span class="flex-shrink-0 w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-lg">
                                {{ $index + 1 }}
                            </span>
                            <h3 class="text-xl font-bold text-slate-800 pt-2 leading-snug">
                                {{ $question->texte_question }}
                            </h3>
                        </div>

                        <!-- Grille de réponses -->
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($question->reponses as $reponse)
                                <label class="relative flex items-center p-5 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all hover:border-indigo-200 hover:bg-indigo-50/50 group">
                                    <input type="checkbox" 
                                           name="reponses[{{ $question->id }}][]" 
                                           value="{{ $reponse->id }}" 
                                           class="w-6 h-6 text-indigo-600 border-slate-300 rounded-lg focus:ring-indigo-500 transition cursor-pointer">
                                    
                                    <span class="ml-4 text-slate-700 font-semibold group-hover:text-indigo-900 transition">
                                        {{ $reponse->texte_reponse }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Validation Finale -->
                <div class="flex flex-col items-center pt-10 pb-20 border-t border-slate-200 mt-12">
                    <button type="submit" 
                            class="w-full md:w-auto px-16 py-5 bg-indigo-600 text-white text-lg font-bold rounded-2xl shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition-colors flex items-center justify-center gap-3">
                        Valider mes réponses
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                    <p class="mt-6 text-slate-500 text-sm font-medium flex items-center bg-white px-4 py-2 rounded-lg border border-slate-100 shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        Toute sortie de la page réinitialisera vos réponses.
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>