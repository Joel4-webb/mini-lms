<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-3xl text-gray-900 leading-tight">
                🎓 Catalogue des <span class="text-indigo-600">Formations</span>
            </h2>
            {{-- Bouton Créer (Admin seulement)  --}}
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('formations.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle Formation
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($formations as $formation)
                    <div class="group bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col">
                        
                        {{-- Header Visuel avec Badges --}}
                        <div class="h-40 bg-gradient-to-br from-indigo-600 to-violet-700 relative p-6">
                            <div class="absolute inset-0 opacity-10 group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 0 L100 100 L100 0 Z"/></svg>
                            </div>
                            
                            <div class="relative flex justify-between items-start">
                                <span class="bg-white/20 backdrop-blur-md text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest">
                                    {{ $formation->chapitres_count ?? $formation->chapitres->count() }} Chapitres
                                </span>
                                {{-- AFFICHAGE DU NIVEAU (Requis par le cahier des charges)  --}}
                                <span class="bg-amber-400 text-amber-900 text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-lg">
                                    {{ $formation->niveau ?? 'Tout niveau' }}
                                </span>
                            </div>
                        </div>

                        {{-- Contenu de la Formation --}}
                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="text-xl font-black text-gray-900 mb-3 group-hover:text-indigo-600 transition">
                                {{ $formation->nom }}
                            </h3>
                            <p class="text-gray-500 text-sm line-clamp-2 mb-8 font-medium leading-relaxed">
                                {{ $formation->description ?? 'Découvrez le programme complet et testez vos connaissances.' }}
                            </p>

                            <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                                {{-- Accès au contenu [cite: 8, 126] --}}
                                <a href="{{ route('formations.show', $formation->id) }}" class="inline-flex items-center text-indigo-600 font-black text-sm hover:translate-x-1 transition-transform">
                                    Suivre le cours
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>

                                {{-- Actions Admin (Modifier / Supprimer)  --}}
                                @if(auth()->user()->role === 'admin')
                                    <div class="flex items-center space-x-1">
                                        {{-- MODIFIER --}}
                                        <a href="{{ route('formations.edit', $formation->id) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition" title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        {{-- SUPPRIMER  --}}
                                        <form action="{{ route('formations.destroy', $formation->id) }}" method="POST" onsubmit="return confirm('❗ Attention : Supprimer cette formation effacera tous les chapitres, quiz et inscriptions associés. Confirmer ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- État vide --}}
                    <div class="col-span-full bg-white rounded-[3rem] p-20 text-center border-2 border-dashed border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                             <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <p class="text-gray-400 font-black text-xl italic">Aucune formation disponible pour le moment.</p>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('formations.create') }}" class="mt-4 inline-block text-indigo-600 font-black underline">Créez votre première formation</a>
                        @endif
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>