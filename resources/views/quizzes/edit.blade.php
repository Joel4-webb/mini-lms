<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="mb-8">
                <nav class="flex items-center text-sm text-slate-500 mb-3 font-medium">
                    <a href="{{ route('quizzes.index') }}" class="hover:text-indigo-600 transition">Gestion des Quiz</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-slate-800">Édition</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Paramétrage du Quiz</h1>
            </div>

            <!-- Formulaire Compact (Fond teinté) -->
            <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" 
                  class="bg-slate-100/50 p-8 sm:p-10 rounded-[2.5rem] shadow-sm border border-slate-200">
                @csrf
                @method('PUT')

                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Titre de l'évaluation</label>
                    <input type="text" name="titre" value="{{ $quiz->titre }}" 
                           class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl text-slate-800 font-semibold focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow shadow-sm">
                </div>

                <div class="mb-10">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Assigner au Chapitre</label>
                    <select name="chapitre_id" 
                            class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl text-slate-800 font-semibold focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow shadow-sm cursor-pointer">
                        <option value="">-- Choisir le chapitre correspondant --</option>
                        @foreach($chapitres as $chapitre)
                            <option value="{{ $chapitre->id }}" {{ $quiz->chapitre_id == $chapitre->id ? 'selected' : '' }}>
                                {{ $chapitre->titre }} (Formation: {{ $chapitre->formation->titre ?? '?' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500 font-medium mt-3 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        C'est ce choix qui fera apparaître le bouton de validation à la fin des leçons de ce chapitre.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-slate-200">
                    <a href="{{ route('quizzes.index') }}" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-800 transition-colors text-center">
                        Annuler
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-bold shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition-colors">
                        Enregistrer et Relier
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</x-app-layout>