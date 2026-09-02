<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ filterUser: '', filterQuiz: '' }">
        
        <!-- EN-TÊTE COMMUN -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <nav class="flex items-center text-sm text-slate-500 mb-2 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Tableau de bord</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-slate-800">Résultats</span>
                </nav>
                <h2 class="font-black text-3xl text-slate-900 leading-tight tracking-tight">
                    Suivi des <span class="text-indigo-600">Évaluations</span>
                </h2>
            </div>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('notes.create') }}" 
                   class="flex items-center bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Saisie manuelle
                </a>
            @endif
        </div>

        @if(auth()->user()->role === 'admin')
            <!-- ========================================== -->
            <!-- VUE ADMIN : LISTE ANALYTIQUE               -->
            <!-- ========================================== -->
            <div class="flex flex-col lg:flex-row gap-8">
                
               <!-- Barre latérale : Filtres -->
                <div class="w-full lg:w-1/4 shrink-0">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 sticky top-6">
                        <h3 class="font-bold text-sm text-slate-800 uppercase tracking-widest mb-6">Filtres</h3>
                        
                        @php
                            // Extraction automatique des élèves et quiz uniques existants dans les notes
                            $uniqueUsers = $notes->pluck('user')->unique('id')->filter();
                            $uniqueQuizzes = $notes->pluck('quiz')->unique('id')->filter();
                        @endphp

                        <div class="space-y-6">
                            <!-- Filtre Élève -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase">Filtrer par Élève</label>
                                <select x-model="filterUser" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 transition-shadow cursor-pointer font-medium text-slate-700">
                                    <option value="">Tous les élèves</option>
                                    @foreach($uniqueUsers as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre Quiz -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase">Filtrer par Quiz</label>
                                <select x-model="filterQuiz" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 transition-shadow cursor-pointer font-medium text-slate-700">
                                    <option value="">Tous les quiz</option>
                                    @foreach($uniqueQuizzes as $q)
                                        <option value="{{ $q->id }}">{{ Str::limit($q->titre, 30) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenu : Liste des copies -->
                <div class="w-full lg:w-3/4 space-y-4">
                    @forelse($notes as $note)
                        <div x-cloak x-show="(filterUser === '' || '{{ $note->user_id }}' === filterUser) && (filterQuiz === '' || '{{ $note->quiz_id }}' === filterQuiz)" 
                             class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                            
                            <!-- Informations Élève & Quiz -->
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-lg shrink-0">
                                    {{ strtoupper(substr($note->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-lg">{{ $note->user->name ?? 'Élève inconnu' }}</h4>
                                    <p class="text-sm text-slate-500 font-medium">
                                        Quiz : <span class="text-slate-700">{{ $note->quiz->titre ?? 'Quiz supprimé' }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Note & Actions -->
                            <div class="flex items-center gap-6 sm:justify-end">
                                <div class="text-right">
                                    <div class="text-2xl font-black {{ $note->note >= 16 ? 'text-emerald-500' : ($note->note >= 10 ? 'text-amber-500' : 'text-rose-500') }}">
                                        {{ round($note->note, 1) }}<span class="text-base text-slate-300 font-bold">/20</span>
                                    </div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                        {{ $note->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                
                                <a href="{{ route('quizzes.results', ['quiz' => $note->quiz_id ?? 0, 'user_id' => $note->user_id]) }}" class="px-5 py-2.5 bg-slate-50 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl text-sm font-bold transition-colors border border-slate-100 shrink-0 shadow-sm">
                                    Détails
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-dashed border-slate-200 rounded-3xl p-12 text-center">
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun résultat enregistré pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        @else
            <!-- ========================================== -->
            <!-- VUE APPRENANT : CARNET DE BORD             -->
            <!-- ========================================== -->
            
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                
                <!-- Carte Moyenne -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex items-center space-x-6">
                    <div class="relative w-20 h-20 shrink-0">
                        <svg class="w-full h-full text-slate-50" viewBox="0 0 36 36">
                            <path class="stroke-current text-slate-100" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="stroke-current {{ $stats['moyenne_generale'] >= 10 ? 'text-emerald-500' : 'text-rose-500' }}" 
                                  stroke-width="3" stroke-dasharray="{{ ($stats['moyenne_generale'] / 20) * 100 }}, 100" stroke-linecap="round" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-xl font-black text-slate-800">
                            {{ number_format($stats['moyenne_generale'], 1) }}
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Moyenne</p>
                        <p class="text-slate-500 text-sm font-medium">Sur {{ $stats['total_quiz'] }} quiz passés</p>
                    </div>
                </div>

                <!-- Carte Quiz Réussis -->
                <div class="bg-indigo-600 p-8 rounded-3xl shadow-sm shadow-indigo-200 text-white flex items-center space-x-6">
                    <div class="p-4 bg-white/10 rounded-2xl shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black tracking-tight">{{ $stats['quiz_reussis'] }}</p>
                        <p class="text-indigo-200 text-xs font-bold uppercase tracking-wider mt-1">Quiz Validés</p>
                    </div>
                </div>

                <!-- Carte Niveau -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex items-center space-x-6">
                    <div class="p-4 bg-slate-50 rounded-2xl text-slate-400 shrink-0">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-black text-slate-800 tracking-tight">
                            {{ $stats['moyenne_generale'] >= 15 ? 'Expert' : ($stats['moyenne_generale'] >= 10 ? 'Apprenant' : 'Débutant') }}
                        </p>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mt-1">Rang actuel</p>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest">Historique détaillé</h3>
                </div>
                
                <div class="divide-y divide-slate-50">
                    @forelse($notes as $note)
                        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between hover:bg-slate-50/50 transition-colors group">
                            
                            <div class="flex items-center space-x-5 mb-4 md:mb-0">
                                <div class="w-12 h-12 rounded-2xl {{ $note->note >= 10 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center font-black text-sm shrink-0">
                                    {{ round($note->note, 0) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                        {{ $note->quiz->titre ?? 'Quiz sans nom' }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                                        {{ $note->quiz->sousChapitre->chapitre->formation->nom ?? 'Formation inconnue' }} 
                                        • {{ $note->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <span class="px-4 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase {{ $note->note >= 10 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $note->note >= 10 ? 'Validé' : 'Échoué' }}
                                </span>
                                <a href="{{ route('quizzes.results', $note->quiz_id ?? 0) }}" class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-indigo-600 hover:text-white transition-colors border border-slate-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <p class="text-slate-500 font-bold text-sm uppercase tracking-widest">Vous n'avez pas encore passé de quiz.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</x-app-layout>