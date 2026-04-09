<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 leading-tight">
                🏁 Résultat : {{ $quiz->titre }}
            </h2>
            <a href="{{ route('formations.show', $quiz->sousChapitre->chapitre->formation_id) }}" 
               class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour au programme
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. HERO SECTION : LE SCORE --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 overflow-hidden mb-10 border border-gray-100">
                <div class="p-10 text-center">
                    @php
                        $scoreFinal = session('score') ?? ($note ? ($note->note * $quiz->questions->count() / 20) : 0);
                        $totalQuestions = session('total') ?? $quiz->questions->count();
                        $noteSur20 = session('noteSur20') ?? ($note ? $note->note : 0);
                        $reussite = $noteSur20 >= 10;
                    @endphp

                    <div class="inline-flex items-center justify-center w-32 h-32 rounded-full mb-6 {{ $reussite ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }}">
                        <span class="text-4xl font-black">{{ round($noteSur20, 1) }}</span>
                        <span class="text-xl font-bold">/20</span>
                    </div>

                    <h3 class="text-2xl font-black text-gray-800 mb-2">
                        {{ $reussite ? 'Félicitations ! 🎉' : 'Encore un petit effort ! 💪' }}
                    </h3>
                    <p class="text-gray-500 font-medium">
                        Tu as validé {{ $scoreFinal }} questions sur {{ $totalQuestions }}.
                    </p>
                </div>
                
                {{-- Barre de progression visuelle --}}
                <div class="w-full bg-gray-100 h-3">
                    <div class="h-full {{ $reussite ? 'bg-green-500' : 'bg-rose-500' }} transition-all duration-1000" 
                         style="width: {{ ($noteSur20 / 20) * 100 }}%"></div>
                </div>
            </div>

            {{-- 2. DÉTAIL DES RÉPONSES --}}
            <h4 class="text-lg font-bold text-gray-700 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Correction détaillée
            </h4>

            <div class="space-y-6">
                @php $details = session('details') ?? []; @endphp
                @forelse($details as $res)
                    <div class="bg-white rounded-2xl shadow-sm border {{ $res['correct'] ? 'border-green-100' : 'border-rose-100' }} overflow-hidden">
                        <div class="p-5 flex items-start space-x-4">
                            {{-- Icône Statut --}}
                            <div class="flex-shrink-0 mt-1">
                                @if($res['correct'])
                                    <span class="flex w-7 h-7 bg-green-100 text-green-600 rounded-full items-center justify-center">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                @else
                                    <span class="flex w-7 h-7 bg-rose-100 text-rose-600 rounded-full items-center justify-center">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1">
                                <p class="font-bold text-gray-800 mb-3">{{ $res['question'] }}</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div class="p-3 rounded-xl {{ $res['correct'] ? 'bg-green-50 text-green-700' : 'bg-rose-50 text-rose-700' }}">
                                        <span class="block text-xs uppercase font-black opacity-60 mb-1">Ta réponse</span>
                                        <span class="font-bold">{{ is_array($res['votre_reponse']) ? implode(', ', $res['votre_reponse']) : ($res['votre_reponse'] ?: 'Aucune') }}</span>
                                    </div>

                                    @if(!$res['correct'])
                                        <div class="p-3 bg-indigo-50 text-indigo-700 rounded-xl">
                                            <span class="block text-xs uppercase font-black opacity-60 mb-1">La bonne réponse</span>
                                            <span class="font-bold">{{ is_array($res['la_bonne_reponse']) ? implode(', ', $res['la_bonne_reponse']) : $res['la_bonne_reponse'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-indigo-50 border border-indigo-100 p-8 rounded-3xl text-center">
                        <p class="text-indigo-600 font-medium">Reviens sur cette page après avoir validé un quiz pour voir tes erreurs détaillées !</p>
                    </div>
                @endforelse
            </div>

            {{-- 3. ACTIONS DE FIN --}}
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4 pb-20">
                <a href="{{ route('notes.index') }}" 
                   class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-50 transition text-center">
                    📊 Mon historique
                </a>
                <a href="{{ route('formations.index') }}" 
                   class="w-full sm:w-auto px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition text-center">
                    🚀 Continuer mes cours
                </a>
            </div>

        </div>
    </div>
</x-app-layout>