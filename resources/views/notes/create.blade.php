<x-app-layout>
    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- EN-TÊTE -->
            <div class="mb-8">
                <nav class="flex items-center text-sm text-slate-500 mb-2 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Tableau de bord</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <a href="{{ route('notes.index') }}" class="hover:text-indigo-600 transition">Évaluations</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-slate-800">Saisie</span>
                </nav>
                <h2 class="font-black text-3xl text-slate-900 leading-tight tracking-tight">
                    Saisie d'une <span class="text-indigo-600">Note Manuelle</span>
                </h2>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                
                <!-- PANNEAU D'INFORMATION GAMIFICATION -->
                <div class="bg-indigo-50/50 border-b border-indigo-100 p-6 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-indigo-900 text-sm">Synchronisation Gamification</h3>
                        <p class="text-xs text-indigo-700/80 mt-1 font-medium leading-relaxed">
                            L'attribution d'une note via ce formulaire déclenche les mêmes règles qu'un quiz classique. Si vous attribuez une note <strong>supérieure ou égale à 16/20</strong>, l'apprenant recevra automatiquement ses 50 points de récompense.
                        </p>
                    </div>
                </div>

                <form action="{{ route('notes.store') }}" method="POST" class="p-8 sm:p-10 space-y-8">
                    @csrf

                    <!-- SELECTION DE LA CIBLE -->
                    <div class="space-y-6">
                        <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest border-b border-slate-100 pb-2">1. Cible de l'évaluation</h3>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Sélectionner l'apprenant *</label>
                            <select name="user_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 font-medium text-slate-800 cursor-pointer transition-shadow">
                                <option value="" disabled selected>-- Choisir dans la liste --</option>
                                @foreach($apprenants as $eleve)
                                    <option value="{{ $eleve->id }}">{{ $eleve->name }} ({{ $eleve->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Quiz ou Leçon concernée *</label>
                            <select name="quiz_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 font-medium text-slate-800 cursor-pointer transition-shadow">
                                <option value="" disabled selected>-- Associer à une évaluation --</option>
                                @foreach($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}">
                                        {{ $quiz->titre }} (Chapitre : {{ $quiz->sousChapitre->titre ?? 'Général' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- ATTRIBUTION DE LA NOTE -->
                    <div class="space-y-6 pt-4">
                        <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest border-b border-slate-100 pb-2">2. Résultat</h3>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Note sur 20 *</label>
                            <div class="relative max-w-xs">
                                <input type="number" step="0.1" name="note" min="0" max="20" required 
                                       class="w-full pl-6 pr-16 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-xl font-black text-indigo-600 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-shadow"
                                       placeholder="Ex: 16.5">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                    <span class="text-slate-400 font-bold text-lg">/ 20</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="pt-8 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('notes.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Annuler
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3.5 rounded-xl font-bold shadow-sm shadow-indigo-200 transition-all flex items-center gap-2">
                            Enregistrer la note
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>