<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex-1">
                <a href="{{ route('formations.index') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 mb-3 transition group">
                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour au catalogue
                </a>

                <nav class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                    <a href="{{ route('formations.index') }}" class="hover:text-indigo-600 transition">Formations</a>
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                    <span class="text-gray-600">Programme</span>
                </nav>
                <h2 class="font-black text-4xl text-gray-900 leading-tight">
                    {{ $formation->nom }}
                </h2>
            </div>

            @if(auth()->user()->role === 'admin')
                <div class="flex space-x-3">
                    <a href="{{ route('chapitres.create', ['formation_id' => $formation->id]) }}" 
                       class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Ajouter un Chapitre
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- COLONNE GAUCHE : LE PROGRAMME --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-black text-gray-800 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Sommaire de la formation
                        </h3>

                        <div class="space-y-10 relative">
                            <div class="absolute left-4 top-2 bottom-0 w-0.5 bg-gray-100"></div>

                            @forelse($formation->chapitres as $index => $chapitre)
                                <div class="relative pl-12">
                                    <div class="absolute left-0 w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black text-xs shadow-lg shadow-indigo-100 z-10">
                                        {{ $index + 1 }}
                                    </div>

                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-lg font-black text-gray-800 uppercase tracking-tight">{{ $chapitre->titre }}</h4>
                                        
                                        @if(auth()->user()->role === 'admin')
                                            <div class="flex items-center space-x-4">
                                                <a href="{{ route('chapitres.show', $chapitre->id) }}" class="text-xs font-bold text-indigo-500 hover:underline">Gérer</a>
                                                
                                                {{-- AJOUT : FORMULAIRE DE SUPPRESSION CHAPITRE --}}
                                                <form action="{{ route('chapitres.destroy', $chapitre->id) }}" method="POST" onsubmit="return confirm('Supprimer ce chapitre et toutes ses leçons ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 transition">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 gap-3">
                                        @foreach($chapitre->sousChapitres as $lecon)
                                            <a href="{{ route('sous-chapitres.show', $lecon->id) }}" 
                                               class="flex items-center p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-indigo-200 hover:bg-white hover:shadow-sm transition-all group">
                                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-indigo-500 mr-3 shadow-sm group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                                </div>
                                                <span class="text-sm font-bold text-gray-700 group-hover:text-indigo-700">{{ $lecon->titre }}</span>
                                                @if($lecon->quiz)
                                                    <span class="ml-auto text-[10px] font-black bg-green-100 text-green-600 px-2 py-0.5 rounded-full">QUIZ</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-center py-10 text-gray-400 italic">Le programme est en cours de rédaction...</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- COLONNE DROITE --}}
                <div class="space-y-6">
                    <div class="bg-indigo-900 text-white p-8 rounded-3xl shadow-xl shadow-indigo-100 sticky top-6">
                        <h4 class="text-lg font-black mb-4">À propos de ce cours</h4>
                        <p class="text-indigo-100 text-sm leading-relaxed mb-6">
                            {{ $formation->description ?? 'Apprenez pas à pas avec nos experts.' }}
                        </p>
                        
                        {{-- BOUTON DÉMARRER CORRIGÉ --}}
                        @if(isset($firstLecon))
                            <a href="{{ route('sous-chapitres.show', $firstLecon->id) }}" class="block w-full text-center mt-8 bg-white text-indigo-900 py-4 rounded-2xl font-black hover:bg-indigo-50 transition shadow-lg">
                                Démarrer la formation
                            </a>
                        @else
                            <button disabled class="w-full mt-8 bg-indigo-800 text-indigo-300 py-4 rounded-2xl font-black cursor-not-allowed shadow-lg">
                                Bientôt disponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>