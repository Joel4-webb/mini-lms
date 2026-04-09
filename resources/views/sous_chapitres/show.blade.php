<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                
                {{-- SOMMAIRE (Sidebar gauche) --}}
                <div class="w-full md:w-1/4">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                        <div class="mb-6 pb-4 border-b border-gray-50">
                            <a href="{{ route('formations.show', $formation->id) }}" 
                            class="flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition group">
                                <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 0118 0z"></path>
                                </svg>
                                Quitter la leçon
                            </a>
                        </div>

                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            Sommaire
                        </h3>
                        
                        <div class="space-y-4">
                            @foreach($formation->chapitres as $chapitre)
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ $chapitre->titre }}</h4>
                                    <ul class="space-y-1">
                                        @foreach($chapitre->sousChapitres as $lecon)
                                            <li>
                                                <a href="{{ route('sous-chapitres.show', $lecon->id) }}" 
                                                   class="block px-3 py-2 rounded-lg text-sm transition {{ $sousChapitre->id == $lecon->id ? 'bg-indigo-50 text-indigo-700 font-bold border-l-4 border-indigo-500' : 'text-gray-600 hover:bg-gray-50' }}">
                                                    {{ $lecon->titre }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- CONTENU DE LA LEÇON (Droite) --}}
                <div class="w-full md:w-3/4">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        {{-- Fil d'Ariane --}}
                        <nav class="text-sm text-gray-400 mb-4">
                            <a href="{{ route('formations.show', $formation->id) }}" class="hover:underline">{{ $formation->nom }}</a> 
                            <span class="mx-2">/</span>
                            <span class="text-gray-600">{{ $sousChapitre->titre }}</span>
                        </nav>

                        <h1 class="text-3xl font-black text-gray-900 mb-6">{{ $sousChapitre->titre }}</h1>
                        
                        <div class="prose prose-indigo max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($sousChapitre->contenu)) !!}
                        </div>

                        {{-- Navigation Bas de page --}}
                        <div class="mt-12 pt-6 border-t border-gray-100 flex justify-between items-center">
                            <p class="text-sm text-gray-500 italic">Prenez le temps de bien lire avant de passer au quiz !</p>
                            
                            @if($sousChapitre->quiz)
                                <a href="{{ route('quizzes.show', $sousChapitre->quiz->id) }}" 
                                   class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition flex items-center">
                                    🚀 Passer le Quiz
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>