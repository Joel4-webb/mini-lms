<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                {{-- Breadcrumbs --}}
                <nav class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Tableau de bord</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-gray-600">Résultats</span>
                </nav>
                <h2 class="font-black text-3xl text-gray-900 leading-tight">
                    🏆 Résultats & <span class="text-indigo-600">Notes</span>
                </h2>
            </div>

            {{-- BOUTON AJOUT MANUEL (Visible uniquement par l'Admin) --}}
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('notes.create') }}" 
                   class="flex items-center bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95">
                    <div class="bg-white/20 p-1 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    Ajouter une note manuelle
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. SECTION RÉCAPITULATIVE (STATS) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                {{-- Carte Moyenne --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-6">
                    <div class="relative w-20 h-20">
                        <svg class="w-full h-full text-gray-100" viewBox="0 0 36 36">
                            <path class="stroke-current text-indigo-100" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="stroke-current {{ $stats['moyenne_generale'] >= 10 ? 'text-green-500' : 'text-rose-500' }}" 
                                  stroke-width="3" stroke-dasharray="{{ ($stats['moyenne_generale'] / 20) * 100 }}, 100" stroke-linecap="round" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-xl font-black text-gray-800">
                            {{ number_format($stats['moyenne_generale'], 1) }}
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Moyenne Générale</p>
                        <p class="text-gray-500 text-sm">Sur un total de {{ $stats['total_quiz'] }} quiz</p>
                    </div>
                </div>

                {{-- Carte Quiz Réussis --}}
                <div class="bg-indigo-600 p-8 rounded-3xl shadow-xl shadow-indigo-100 text-white flex items-center space-x-6">
                    <div class="p-4 bg-white/10 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black">{{ $stats['quiz_reussis'] }}</p>
                        <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Quiz Validés ✅</p>
                    </div>
                </div>

                {{-- Carte Niveau --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-6">
                    <div class="p-4 bg-amber-50 rounded-2xl text-amber-500">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-800">
                            {{ $stats['moyenne_generale'] >= 15 ? 'Expert 🎓' : ($stats['moyenne_generale'] >= 10 ? 'Apprenant 📖' : 'Débutant 🌱') }}
                        </p>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Rang actuel</p>
                    </div>
                </div>
            </div>

            {{-- 2. HISTORIQUE DÉTAILLÉ --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="font-black text-gray-700 uppercase text-sm tracking-widest">Historique des examens</h3>
                </div>
                
                <div class="divide-y divide-gray-50">
                    @forelse($notes as $note)
                        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between hover:bg-indigo-50/20 transition group">
                            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                                <div class="w-12 h-12 rounded-2xl {{ $note->note >= 10 ? 'bg-green-50 text-green-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center shadow-sm">
                                    <span class="font-black text-sm">{{ round($note->note, 0) }}</span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 group-hover:text-indigo-600 transition">
                                        {{ $note->quiz->titre ?? 'Quiz sans nom' }}
                                    </p>
                                    <p class="text-xs text-gray-400 font-medium">
                                        {{ $note->quiz->sousChapitre->chapitre->formation->nom ?? 'Formation inconnue' }} 
                                        • {{ $note->created_at->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <span class="px-4 py-1.5 rounded-xl text-xs font-black tracking-widest uppercase {{ $note->note >= 10 ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $note->note >= 10 ? 'Réussi' : 'À retravailler' }}
                                </span>
                                <a href="{{ route('quizzes.show', $note->quiz_id) }}" class="p-2 bg-gray-50 text-gray-400 rounded-lg hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <p class="text-gray-400 font-medium italic">Vous n'avez pas encore passé de quiz. Lancez-vous !</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>