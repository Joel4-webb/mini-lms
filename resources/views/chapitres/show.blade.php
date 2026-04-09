<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                {{-- Breadcrumbs --}}
                <nav class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                    <a href="{{ route('formations.index') }}" class="hover:text-indigo-600 transition">Formations</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <a href="{{ route('formations.show', $chapitre->formation_id) }}" class="hover:text-indigo-600 transition">{{ $chapitre->formation->nom }}</a>
                </nav>
                <h2 class="font-black text-3xl text-gray-800 leading-tight">
                    📚 {{ $chapitre->titre }}
                </h2>
                <p class="text-gray-500 mt-1 font-medium">Liste des leçons et quiz de ce chapitre</p>
            </div>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('sous-chapitres.create', ['chapitre_id' => $chapitre->id]) }}" 
                   class="inline-flex items-center bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle Leçon
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider">Leçon</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider text-center">Évaluation</th>
                                @if(auth()->user()->role === 'admin')
                                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider text-right">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($chapitre->sousChapitres as $lecon)
                                <tr class="group hover:bg-indigo-50/30 transition-colors">
                                    {{-- Titre de la leçon --}}
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-4 group-hover:bg-white transition shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <a href="{{ route('sous-chapitres.show', $lecon->id) }}" class="text-gray-800 font-bold hover:text-indigo-600 transition decoration-2 underline-offset-4">
                                                {{ $lecon->titre }}
                                            </a>
                                        </div>
                                    </td>

                                    {{-- État du Quiz --}}
                                    <td class="px-6 py-5 text-center">
                                        @if($lecon->quiz)
                                            <div class="inline-flex flex-col items-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-green-100 text-green-700 mb-2">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                                    QUIZ DISPONIBLE
                                                </span>
                                                @if(auth()->user()->role === 'admin')
                                                    <a href="{{ route('quizzes.questions.create', $lecon->quiz->id) }}" class="text-[10px] font-black text-indigo-500 hover:text-indigo-700 uppercase tracking-tighter">
                                                        ⚙️ Configurer
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('quizzes.create', ['sous_chapitre_id' => $lecon->id]) }}" class="inline-flex items-center px-3 py-1.5 border-2 border-dashed border-gray-200 text-gray-400 rounded-xl text-xs font-bold hover:border-indigo-300 hover:text-indigo-500 transition">
                                                    ➕ Créer Quiz
                                                </a>
                                            @else
                                                <span class="text-gray-300 text-xs font-medium italic">Pas de quiz</span>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- Actions Admin --}}
                                    @if(auth()->user()->role === 'admin')
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex justify-end items-center space-x-2">
                                                <a href="{{ route('sous-chapitres.edit', $lecon->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Modifier">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                                <form action="{{ route('sous-chapitres.destroy', $lecon->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer définitivement cette leçon ?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                            </div>
                                            <p class="text-gray-400 font-medium">Aucune leçon publiée dans ce chapitre.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('formations.show', $chapitre->formation_id) }}" class="text-gray-500 hover:text-indigo-600 font-bold transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Retour au programme complet
                </a>
            </div>

        </div>
    </div>
</x-app-layout>