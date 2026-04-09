<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <nav class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Tableau de bord</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-gray-600">Gestion des Quiz</span>
                </nav>
                <h2 class="font-black text-3xl text-gray-900 leading-tight">
                    📝 Liste des <span class="text-indigo-600">Quiz IA</span>
                </h2>
            </div>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('quizzes.create') }}" class="flex items-center bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Nouveau Quiz
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($quizzes as $quiz)
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
                        <div class="p-8">
                            {{-- Badge Catégorie/Leçon --}}
                            <div class="flex justify-between items-start mb-6">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-lg">
                                    {{ $quiz->sousChapitre->titre ?? 'Leçon non définie' }}
                                </span>
                                <span class="flex items-center text-gray-400 text-xs font-bold">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $quiz->questions->count() }} Qst
                                </span>
                            </div>

                            {{-- Titre --}}
                            <h3 class="font-black text-xl text-gray-900 mb-4 group-hover:text-indigo-600 transition-colors">
                                {{ $quiz->titre }}
                            </h3>

                            <p class="text-gray-500 text-sm leading-relaxed mb-8 line-clamp-2">
                                Géré par l'intelligence artificielle pour valider les acquis de la leçon.
                            </p>

                            {{-- Actions --}}
                            <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                                <a href="{{ route('quizzes.questions.create', $quiz->id) }}" class="text-sm font-black text-indigo-600 hover:text-indigo-800 transition flex items-center">
                                    Gérer les questions
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>

                                <div class="flex items-center space-x-2">
                                    {{-- Bouton Éditer --}}
                                    <a href="{{ route('quizzes.edit', $quiz->id) }}" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    {{-- Bouton Supprimer --}}
                                    <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('❗ Attention : Supprimer ce quiz supprimera également toutes les questions et les notes associées. Confirmer ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <p class="text-gray-400 font-black italic text-lg">Aucun quiz n'a encore été généré.</p>
                        <a href="{{ route('quizzes.create') }}" class="mt-4 inline-block text-indigo-600 font-bold hover:underline">Créer le premier quiz maintenant</a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>