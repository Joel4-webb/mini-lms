@props(['formation'])

<div x-show="tab === 'toutes' || (tab === 'officielles' && '{{ $formation->creator_id }}' === '{{ auth()->id() }}') || (tab === 'communaute' && '{{ $formation->creator_id }}' !== '{{ auth()->id() }}')" 
     x-transition.opacity 
     class="relative w-full h-[420px] rounded-[2rem] overflow-hidden group shadow-md hover:shadow-xl transition-all">
    
    <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=600&auto=format&fit=crop" 
         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Cover">
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/95"></div>
    
    <div class="absolute inset-0 p-6 flex flex-col justify-between">
        <div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border {{ $formation->is_public ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : 'bg-white/10 text-gray-300 border-white/20' }} backdrop-blur-md">
                    {{ $formation->is_public ? 'Public' : 'Privé' }}
                </span>
                
                <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md p-1.5 rounded-full border border-white/10">
                    <!-- Bouton Mettre en avant Actif -->
                    <form action="{{ route('formations.toggle-featured', $formation->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" title="{{ $formation->is_featured ? 'Retirer la mise en avant' : 'Mettre en avant' }}" 
                                class="p-1.5 transition-colors {{ $formation->is_featured ? 'text-yellow-400' : 'text-gray-400 hover:text-yellow-400' }}">
                            <svg class="w-4 h-4" fill="{{ $formation->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </button>
                    </form>
                    <a href="{{ route('formations.show', $formation->id) }}" title="Gérer" class="p-1.5 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </a>
                    <form action="{{ route('formations.destroy', $formation->id) }}" method="POST" onsubmit="return confirm('Supprimer ce cours ?');" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" title="Supprimer" class="p-1.5 text-gray-400 hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <h2 class="text-2xl font-black text-white uppercase leading-none tracking-tight mb-2">{{ $formation->nom }}</h2>
            <p class="text-sm text-gray-300 font-medium">Par {{ $formation->creator->name ?? 'Admin' }}</p>
        </div>
        
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex -space-x-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-500 border-2 border-slate-900 flex items-center justify-center text-white text-[10px] font-bold">1</div>
                    <div class="w-8 h-8 rounded-full bg-purple-500 border-2 border-slate-900 flex items-center justify-center text-white text-[10px] font-bold">2</div>
                    <div class="w-8 h-8 rounded-full bg-emerald-500 border-2 border-slate-900 flex items-center justify-center text-white text-[10px] font-bold">+</div>
                </div>
                <span class="bg-white/10 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full">
                    {{ $formation->apprenants->count() }} Élèves
                </span>
            </div>
            <div class="w-full bg-white/20 h-1 rounded-full overflow-hidden">
                <div class="bg-white w-1/3 h-full rounded-full"></div>
            </div>
            <div class="flex justify-between items-center mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                <span>{{ $formation->chapitres->count() }} Leçons</span>
                <span>Géré par {{ $formation->creator_id === auth()->id() ? 'Vous' : 'Communauté' }}</span>
            </div>
        </div>
    </div>
</div>