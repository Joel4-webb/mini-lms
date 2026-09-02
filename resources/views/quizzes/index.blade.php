<x-app-layout>
    <div class="bg-white border-b border-slate-200 px-4 py-6 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <nav class="flex items-center text-sm text-slate-500 mb-2 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Tableau de bord</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-slate-800">Gestion des Quiz</span>
                </nav>
                <h2 class="font-black text-3xl text-slate-900 leading-tight tracking-tight">
                    Liste des Quiz IA
                </h2>
            </div>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('quizzes.create') }}" class="flex items-center bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Nouveau Quiz
                </a>
            @endif
        </div>
    </div>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($quizzes as $quiz)
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                        <div class="p-6">
                            
                            <!-- Badges -->
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider rounded-lg line-clamp-1">
                                    {{ $quiz->sousChapitre->titre ?? 'Leçon non définie' }}
                                </span>
                                <span class="flex items-center text-slate-500 bg-slate-50 px-2 py-1 rounded-lg text-xs font-bold shrink-0">
                                    <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $quiz->questions->count() }}
                                </span>
                            </div>

                            <!-- Titre -->
                            <h3 class="font-bold text-xl text-slate-800 mb-2 leading-tight">
                                {{ $quiz->titre }}
                            </h3>

                            <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-2 font-medium">
                                Géré par l'intelligence artificielle pour valider les acquis de la leçon.
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="p-4 border-t border-slate-50 bg-slate-50/50 flex items-center justify-between rounded-b-3xl">
                            <a href="{{ route('quizzes.questions.create', $quiz->id) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                                Gérer les questions
                            </a>

                            <div class="flex items-center gap-1">
                                <a href="{{ route('quizzes.edit', $quiz->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-widest mb-4">Aucun quiz généré</p>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('quizzes.create') }}" class="text-indigo-600 font-bold hover:text-indigo-700">Créer le premier quiz &rarr;</a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination (si active) -->
            <div class="mt-8">
                {{ $quizzes->links() }}
            </div>

        </div>
    </div>
</x-app-layout>