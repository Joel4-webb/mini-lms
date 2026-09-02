<x-app-layout>
    <div class="max-w-full">
        
        {{-- En-tête / Fil d'Ariane --}}
        <div class="flex items-center text-sm font-bold text-slate-400 mb-6 gap-2">
            <a href="{{ route('formations.show', $formation->id) }}" class="hover:text-indigo-600 transition">{{ $formation->nom }}</a>
            <span>/</span>
            <span class="text-slate-800">{{ $sousChapitre->titre }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            {{-- ========================================== --}}
            {{-- COLONNE GAUCHE (Leçon textuelle)           --}}
            {{-- ========================================== --}}
            <div class="lg:col-span-2">
                
                {{-- Titre de la leçon (Typographie épurée) --}}
                <h1 class="font-black text-4xl text-slate-800 mb-8 tracking-tight">{{ $sousChapitre->titre }}</h1>

                {{-- Carte de lecture du contenu --}}
                <div class="bg-white p-8 sm:p-12 rounded-[2.5rem] shadow-sm border border-slate-100">
                    
                    {{-- Onglet indicateur --}}
                    <div class="flex items-center border-b border-slate-100 pb-4 mb-8">
                        <span class="relative text-indigo-600 font-bold text-sm uppercase tracking-widest">
                            Contenu de la leçon
                            <span class="absolute -bottom-[17px] left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-indigo-600 rounded-full shadow-[0_0_8px_rgba(79,70,229,0.5)]"></span>
                        </span>
                    </div>

                    
                    <div class="max-w-none text-slate-700 leading-relaxed
                                [&>h1]:text-3xl [&>h1]:font-black [&>h1]:text-slate-900 [&>h1]:mt-8 [&>h1]:mb-6
                                [&>h2]:text-2xl [&>h2]:font-bold [&>h2]:text-slate-800 [&>h2]:mt-10 [&>h2]:mb-5 [&>h2]:border-b [&>h2]:border-slate-100 [&>h2]:pb-2
                                [&>h3]:text-xl [&>h3]:font-bold [&>h3]:text-slate-800 [&>h3]:mt-8 [&>h3]:mb-4
                                [&>p]:mb-6 [&>p]:text-lg
                                [&>ul]:list-disc [&>ul]:pl-6 [&>ul]:mb-6 [&>ul>li]:mb-2 [&>ul>li]:text-lg
                                [&>ol]:list-decimal [&>ol]:pl-6 [&>ol]:mb-6 [&>ol>li]:mb-2 [&>ol>li]:text-lg
                                [&>strong]:font-bold [&>strong]:text-slate-900">
                        {!! Str::markdown($sousChapitre->contenu) !!}
                    </div>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- COLONNE DROITE (Widgets)                   --}}
            {{-- ========================================== --}}
            <div class="space-y-6">
                
                {{-- Widget 1 : L'Auteur (Ton IA) --}}
                <div class="bg-white/60 backdrop-blur-sm border border-slate-100 rounded-[2rem] p-6 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-bold text-slate-800">Auteur du cours</h3>
                        <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">IA GROQ</span>
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xl shadow-sm shadow-indigo-200">
                            IA
                        </div>
                        <div>
                            <p class="font-black text-sm text-slate-800">Llama 3.3</p>
                            <p class="text-xs text-slate-500 font-medium">Assistant Pédagogique</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Ce contenu a été rédigé automatiquement par l'API Groq pour vous fournir une base d'apprentissage structurée.
                    </p>
                </div>

                {{-- Widget 2 : Le Quiz (S'il existe) --}}
                <div class="bg-white/60 backdrop-blur-sm border border-slate-100 rounded-[2rem] p-6 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Évaluation</h3>
                    @if($sousChapitre->quiz)
                        <p class="text-xs text-slate-500 mb-6 font-medium leading-relaxed">Validez vos acquis en répondant aux questions (QCM) générées sur ce chapitre.</p>
                        <a href="{{ route('quizzes.show', $sousChapitre->quiz->id) }}" 
                           class="block w-full text-center bg-slate-900 text-white py-3.5 rounded-full text-sm font-bold hover:bg-indigo-600 transition-colors shadow-sm">
                            RÉPONDRE AU QUIZ
                        </a>
                    @else
                        <p class="text-xs text-slate-400 italic text-center py-2 font-medium">Aucun quiz généré pour le moment.</p>
                    @endif
                </div>

                {{-- Widget 3 : Le Sommaire --}}
                <div class="bg-white/60 backdrop-blur-sm border border-slate-100 rounded-[2rem] p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-800">Sommaire du chapitre</h3>
                    </div>
                    <div class="space-y-3">
                        @foreach($sousChapitre->chapitre->sousChapitres as $lecon)
                            <a href="{{ route('sous-chapitres.show', $lecon->id) }}" 
                               class="flex items-center p-3 rounded-2xl transition {{ $sousChapitre->id == $lecon->id ? 'bg-white shadow-sm border border-slate-100' : 'hover:bg-white' }}">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3 transition-colors {{ $sousChapitre->id == $lecon->id ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-slate-100 text-slate-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold {{ $sousChapitre->id == $lecon->id ? 'text-indigo-600' : 'text-slate-500' }} line-clamp-2 leading-snug">
                                        {{ $lecon->titre }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>