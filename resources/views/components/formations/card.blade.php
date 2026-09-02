@props(['formation', 'mode' => 'learning'])

<div class="relative w-full h-[420px] rounded-[2rem] overflow-hidden group shadow-md hover:shadow-xl transition-all cursor-pointer"
     x-show="(search === '' || '{{ strtolower(addslashes($formation->nom)) }}'.includes(search.toLowerCase())) && (niveau === '' || '{{ $formation->niveau }}' === niveau)"
     x-transition.opacity>
    
    <a href="{{ route('formations.show', $formation->id) }}" class="absolute inset-0 z-10"></a>

    <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=600&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Cover">
    <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/50 to-black/95 group-hover:from-black/60 group-hover:via-black/70 group-hover:to-black/95 transition-colors duration-500"></div>

    <div class="absolute inset-0 p-6 flex flex-col justify-between pointer-events-none">
        
        <div class="flex justify-between items-start">
            <span class="bg-white/20 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider border border-white/20">{{ $formation->chapitres->count() }} Chapitres</span>
            <span class="bg-indigo-500/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider border border-indigo-400/50">{{ $formation->niveau ?? 'Général' }}</span>
        </div>

        <div class="flex flex-col justify-end">
            <div class="transform transition-transform duration-500 group-hover:-translate-y-2">
                <h3 class="text-2xl font-black text-white leading-tight mb-2">{{ $formation->nom }}</h3>
                
                @if($formation->is_featured)
                    <span class="inline-flex items-center gap-1 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full mb-3 shadow-sm border border-amber-300/30">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        À la une
                    </span>
                @endif

                <p class="text-xs text-gray-300 font-medium uppercase tracking-wider">Par {{ $formation->creator->name ?? 'Admin' }}</p>
            </div>

            <div class="max-h-0 opacity-0 group-hover:max-h-[200px] group-hover:opacity-100 transition-all duration-500 overflow-hidden">
                <!-- La description s'affiche désormais partout -->
                <p class="text-sm text-gray-300 line-clamp-3 mt-3 mb-5 leading-relaxed">
                    {{ $formation->description ?? 'Découvrez les fondamentaux de cette formation étape par étape.' }}
                </p>

                <div class="pt-4 mt-4 border-t border-white/20 pointer-events-auto relative z-20">
                    @if($mode === 'learning')
                        <span class="flex items-center justify-between text-white group-hover:text-indigo-300 transition-colors">
                            <span class="text-sm font-bold uppercase tracking-wider">Ouvrir le cours</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    @elseif($mode === 'catalog')
                        <form action="{{ route('formations.enroll', $formation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-white text-slate-900 font-bold py-3 rounded-xl hover:bg-indigo-50 transition-colors text-sm">
                                S'inscrire au cours
                            </button>
                        </form>
                    @elseif($mode === 'creations')
                        <div class="flex gap-2">
                            <a href="{{ route('formations.edit', $formation->id) }}" class="flex-1 text-center text-xs font-bold bg-white/10 hover:bg-white text-white hover:text-slate-900 py-3 rounded-xl transition-colors backdrop-blur-md border border-white/20">Éditer</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>