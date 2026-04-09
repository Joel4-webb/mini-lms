<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-gray-900 leading-tight">
                    Bonjour, <span class="text-indigo-600">{{ Auth::user()->name }}</span> 👋
                </h2>
                <p class="text-gray-500 font-medium">Heureux de vous revoir sur votre plateforme d'apprentissage.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-4 py-2 bg-white rounded-2xl shadow-sm border border-gray-100 text-xs font-black uppercase tracking-widest text-indigo-600">
                    Rôle : {{ Auth::user()->role }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. STATS RAPIDES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                @if(Auth::user()->role === 'admin')
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Élèves</p>
                        <p class="text-3xl font-black text-gray-900">{{ $stats['total_eleves'] ?? 0 }}</p>
                    </div>
                    <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-xl shadow-indigo-100 text-white">
                        <p class="text-[10px] font-black opacity-80 uppercase tracking-widest mb-1">Formations Actives</p>
                        <p class="text-3xl font-black">{{ $stats['total_formations'] ?? 0 }}</p>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Ma Moyenne</p>
                        <p class="text-3xl font-black text-indigo-600">{{ number_format($stats['moyenne'] ?? 0, 1) }}/20</p>
                    </div>
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Quiz Terminés</p>
                        <p class="text-3xl font-black text-gray-900">{{ $stats['quiz_termines'] ?? 0 }}</p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- COLONNE GAUCHE --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Actions Rapides --}}
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                        <h3 class="font-black text-xl text-gray-800 mb-6">Actions rapides</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('formations.create') }}" class="flex items-center p-4 bg-indigo-50 rounded-2xl border border-indigo-100 hover:bg-indigo-100 transition group">
                                    <div class="p-3 bg-white rounded-xl shadow-sm mr-4 group-hover:scale-110 transition-transform">🚀</div>
                                    <span class="font-bold text-indigo-900">Créer une formation</span>
                                </a>
                                <a href="{{ route('quizzes.index') }}" class="flex items-center p-4 bg-amber-50 rounded-2xl border border-amber-100 hover:bg-amber-100 transition group">
                                    <div class="p-3 bg-white rounded-xl shadow-sm mr-4 group-hover:scale-110 transition-transform">📝</div>
                                    <span class="font-bold text-amber-900">Gérer les Quiz IA</span>
                                </a>
                            @else
                                <a href="{{ route('formations.index') }}" class="flex items-center p-4 bg-indigo-50 rounded-2xl border border-indigo-100 hover:bg-indigo-100 transition group">
                                    <div class="p-3 bg-white rounded-xl shadow-sm mr-4 group-hover:scale-110 transition-transform">📚</div>
                                    <span class="font-bold text-indigo-900">Continuer mes cours</span>
                                </a>
                                <a href="{{ route('notes.index') }}" class="flex items-center p-4 bg-green-50 rounded-2xl border border-green-100 hover:bg-green-100 transition group">
                                    <div class="p-3 bg-white rounded-xl shadow-sm mr-4 group-hover:scale-110 transition-transform">🏆</div>
                                    <span class="font-bold text-green-900">Voir mes certificats</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Derniers résultats --}}
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                            <h3 class="font-black text-xl text-gray-800">Derniers résultats</h3>
                            <a href="{{ route('notes.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Voir tout</a>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @forelse($stats['dernieres_notes'] ?? [] as $note)
                                <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                                    <div class="flex items-center">
                                        {{-- Badge Note corrigé (Décimales limitées + Pas de coupure) --}}
                                        <div class="w-16 h-10 rounded-xl flex items-center justify-center font-black text-xs mr-4 whitespace-nowrap px-2
                                            {{ $note->note >= 10 ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }}">
                                            {{ number_format($note->note, 1) }} / 20
                                        </div>
                                        
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm leading-tight">{{ $note->quiz->titre ?? 'Quiz' }}</p>
                                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">{{ $note->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            @empty
                                <p class="p-10 text-center text-gray-400 italic">Aucune activité récente.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- COLONNE DROITE --}}
                <div class="space-y-8">
                    {{-- SÉCURITÉ : On affiche l'objectif SEULEMENT pour l'apprenant --}}
                    @if(Auth::user()->role === 'apprenant')
                        <div class="bg-indigo-900 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
                            <div class="relative z-10">
                                <h4 class="font-black text-lg mb-4">Objectif de la semaine</h4>
                                
                                @if($stats['restant_hebdo'] > 0)
                                    <p class="text-indigo-200 text-sm mb-6 leading-relaxed">
                                        Terminez encore <span class="text-white font-bold">{{ $stats['restant_hebdo'] }} quiz</span> pour atteindre votre objectif !
                                    </p>
                                @else
                                    <p class="text-green-400 text-sm mb-6 leading-relaxed font-bold">
                                        🎉 Objectif atteint ! Vous êtes une machine !
                                    </p>
                                @endif

                                <div class="w-full bg-indigo-800 rounded-full h-2 mb-4">
                                    <div class="bg-indigo-400 h-2 rounded-full transition-all duration-1000" style="width: {{ $stats['progression_hebdo'] }}%"></div>
                                </div>
                                
                                <p class="text-[10px] font-bold text-indigo-300">
                                    {{ round($stats['progression_hebdo']) }}% de progression hebdomadaire
                                </p>
                            </div>
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                        </div>
                    @else
                        {{-- Contenu alternatif pour l'ADMIN --}}
                        <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100">
                            <h4 class="font-black text-lg mb-2">Gestion Plateforme</h4>
                            <p class="text-indigo-100 text-sm leading-relaxed">
                                Surveillez l'activité de vos {{ $stats['total_eleves'] }} apprenants depuis cet espace.
                            </p>
                        </div>
                    @endif

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                        <h4 class="font-black text-gray-800 mb-4">Conseil IA ✨</h4>
                        <p class="text-gray-500 text-sm italic leading-relaxed">
                            "Concentrez-vous sur le chapitre **{{ $stats['recommandation'] ?? 'Introduction' }}**, vos derniers résultats suggèrent que vous pouvez encore vous améliorer sur ce point."
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>