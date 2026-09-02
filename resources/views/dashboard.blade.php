<x-app-layout>
    <div class="min-h-screen bg-slate-50/50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(Auth::user()->role === 'admin')
                <!-- ========================================== -->
                <!-- VUE ADMINISTRATEUR                         -->
                <!-- ========================================== -->
                <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tableau de bord</h1>
                        <p class="text-sm font-medium text-slate-500 mt-1">Supervision de la plateforme et performances globales.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold px-4 py-2 rounded-xl border border-indigo-100 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        Mode Administrateur
                    </span>
                </div>

                <!-- Métriques clés -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <p class="text-3xl font-black text-slate-900">{{ $stats['total_eleves'] }}</p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Élèves inscrits</p>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
                        </div>
                        <p class="text-3xl font-black text-slate-900">{{ $stats['total_formations'] }}</p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Formations</p>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-3xl font-black text-slate-900">{{ $stats['total_inscriptions'] }}</p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Participations</p>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <p class="text-3xl font-black text-slate-900">{{ $stats['taux_reussite'] }}</p>
                            <p class="text-sm font-bold text-slate-400">%</p>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Taux de réussite</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Activité Récente -->
                    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest">Évaluations récentes</h3>
                            <a href="{{ route('notes.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Voir tout</a>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @forelse($activite_recente ?? [] as $activite)
                                <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black text-sm shrink-0">
                                            {{ strtoupper(substr($activite->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-800">{{ $activite->user->name ?? 'Élève inconnu' }}</p>
                                            <p class="text-xs text-slate-500 font-medium truncate max-w-[200px] sm:max-w-xs">{{ $activite->quiz->titre ?? 'Quiz supprimé' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-bold tracking-widest uppercase {{ $activite->note >= 16 ? 'bg-emerald-50 text-emerald-600' : ($activite->note >= 10 ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600') }}">
                                            {{ round($activite->note, 1) }}/20
                                        </span>
                                        <p class="text-[10px] font-medium text-slate-400 mt-1">{{ $activite->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-10 text-center">
                                    <p class="text-slate-500 text-sm font-medium">Aucune activité récente.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Actions Rapides -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest mb-4 px-2">Raccourcis</h3>
                        
                        <a href="{{ route('formations.create') }}" class="group block bg-indigo-600 rounded-3xl p-6 shadow-sm hover:bg-indigo-700 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <h4 class="font-bold text-white">Générer un cours</h4>
                            <p class="text-xs text-indigo-100 mt-1 font-medium">Créer une formation via IA</p>
                        </a>

                        <a href="{{ route('formations.index') }}" class="group block bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600 mb-4 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div>
                            <h4 class="font-bold text-slate-800">Mettre en avant</h4>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Gérer la visibilité du catalogue</p>
                        </a>

                        <a href="{{ route('notes.create') }}" class="group block bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600 mb-4 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <h4 class="font-bold text-slate-800">Saisie manuelle</h4>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Ajouter une note d'évaluation</p>
                        </a>
                    </div>
                </div>

            @else
                <!-- ========================================== -->
                <!-- VUE APPRENANT & CRÉATEUR                   -->
                <!-- ========================================== -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Colonne Principale -->
                    <div class="lg:col-span-2 space-y-8">
                        
                        <!-- Bannière de bienvenue -->
                        <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-8 shadow-lg shadow-indigo-200 text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="relative z-10">
                                <h2 class="text-3xl font-black mb-2 tracking-tight">Bonjour, {{ auth()->user()->name }}</h2>
                                <p class="text-indigo-100 text-sm mb-6 max-w-md">Continuez votre apprentissage ou utilisez vos points pour forger de nouveaux contenus.</p>
                                <a href="{{ route('formations.index') }}" class="inline-flex px-6 py-3 bg-white text-indigo-600 font-bold text-sm rounded-xl hover:bg-indigo-50 transition-colors shadow-sm">
                                    Explorer le catalogue
                                </a>
                            </div>
                            <div class="bg-white/20 backdrop-blur-md rounded-2xl p-5 border border-white/20 text-center relative z-10 shadow-sm min-w-[140px]">
                                <p class="text-xs font-bold text-indigo-100 uppercase tracking-widest mb-1">Solde IA</p>
                                <p class="text-3xl font-black text-white">{{ auth()->user()->points_balance }} <span class="text-base text-indigo-200 font-medium">pts</span></p>
                            </div>
                            <!-- Décoration -->
                            <svg class="absolute right-0 bottom-0 w-64 h-64 text-white opacity-5 transform translate-x-1/4 translate-y-1/4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"/></svg>
                        </div>

                        <!-- Mes Formations -->
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-black text-slate-900 tracking-tight">Mes Apprentissages</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                @forelse($formations as $formation)
                                    <a href="{{ route('formations.show', $formation->id) }}" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group flex flex-col justify-between min-h-[160px]">
                                        <div>
                                            <span class="inline-block bg-indigo-50 text-indigo-600 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider mb-3">
                                                {{ $formation->chapitres->count() }} Chapitres
                                            </span>
                                            <h4 class="font-bold text-lg text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight">{{ $formation->nom }}</h4>
                                        </div>
                                        <div class="mt-4 flex items-center justify-between pt-4 border-t border-slate-50 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                            <span>Continuer</span>
                                            <svg class="w-4 h-4 text-indigo-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-span-full border border-dashed border-slate-200 rounded-3xl p-10 text-center bg-slate-50/50">
                                        <p class="text-sm text-slate-500 font-bold uppercase tracking-widest">Aucun cours en cours.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Colonne Secondaire (Gamification & Outils) -->
                    <div class="space-y-6">
                        
                        <!-- Objectif Hebdomadaire -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                            <h3 class="text-sm font-bold text-slate-800 mb-1">Objectif Hebdomadaire</h3>
                            <p class="text-xs text-slate-500 font-medium mb-5">Validez 5 quiz cette semaine</p>
                            
                            <div class="relative w-full bg-slate-100 rounded-full h-3 mb-3 overflow-hidden">
                                <div class="bg-emerald-500 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $stats['progression_hebdo'] }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-emerald-600">{{ $stats['quiz_cette_semaine'] }} validés</span>
                                <span class="text-slate-400">{{ $stats['restant_hebdo'] }} restants</span>
                            </div>
                        </div>

                        <!-- Outils Créateurs IA (Restauré) -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100" x-data="{ idee: '', loading: false, resultat: null }">
                            <h3 class="text-sm font-bold text-slate-800 mb-4">Outils Créateurs IA</h3>
                            
                            @php
                                $creationsRestantes = max(0, 2 - auth()->user()->formations_created);
                            @endphp

                            <!-- Bouton Forger un cours -->
                            <a href="{{ route('formations.create') }}" class="w-full flex items-center justify-between p-4 mb-4 rounded-2xl bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span class="text-sm font-semibold">Forger un cours</span>
                                </div>
                                
                                @if($creationsRestantes > 0)
                                    <span class="text-[10px] font-bold bg-white/20 px-2 py-1 rounded-full">{{ $creationsRestantes }} Gratuit(s)</span>
                                @else
                                    <span class="text-[10px] font-bold bg-rose-500 px-2 py-1 rounded-full">-10 PTS</span>
                                @endif
                            </a>

                            <!-- Assistant idée -->
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-widest">Idée de plan</span>
                                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-100 px-2 py-1 rounded-full">-5 PTS</span>
                                </div>
                                <input type="text" x-model="idee" placeholder="Ex: Les bases de Python..." class="w-full text-sm bg-white border border-slate-200 rounded-xl px-4 py-3 mb-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                
                                <button @click="loading = true; fetch('{{ route('ai.suggest') }}', {
                                            method: 'POST', 
                                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                            body: JSON.stringify({idee: idee})
                                        }).then(r => r.json()).then(d => { loading = false; resultat = d; })"
                                        :disabled="loading || idee.length === 0"
                                        class="w-full text-center bg-white text-slate-700 text-sm font-bold py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 hover:text-indigo-600 transition-colors disabled:opacity-50">
                                    <span x-show="!loading">Soumettre à l'IA</span>
                                    <span x-show="loading" class="animate-pulse">Génération...</span>
                                </button>
                                
                                <!-- Résultat JSON -->
                                <div x-show="resultat" class="mt-4 p-4 bg-white border border-slate-200 rounded-xl text-sm overflow-auto max-h-48" style="display: none;">
                                    <template x-if="resultat?.success">
                                        <div>
                                            <p class="font-black text-indigo-600 mb-2" x-text="resultat.structure.titre_suggere"></p>
                                            <template x-for="chap in resultat.structure.chapitres">
                                                <div class="mb-2 pl-3 border-l-2 border-slate-200">
                                                    <p class="font-semibold text-slate-700" x-text="chap.titre"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!resultat?.success">
                                        <p class="text-rose-500 font-bold text-xs" x-text="resultat?.message"></p>
                                    </template>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>