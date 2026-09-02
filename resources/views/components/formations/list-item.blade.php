@props(['formation', 'mode' => 'learning'])

<div class="relative flex flex-col sm:flex-row gap-6 p-6 border-b border-slate-100 hover:bg-slate-50 transition-colors bg-white group cursor-pointer">
    
    <!-- Lien global invisible -->
    <a href="{{ route('formations.show', $formation->id) }}" class="absolute inset-0 z-10"></a>

    <!-- Couverture -->
    <div class="w-full sm:w-40 h-48 shrink-0 rounded-lg overflow-hidden relative shadow-sm border border-slate-200 pointer-events-none">
        <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=300&auto=format&fit=crop" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" alt="Couverture">
        <div class="absolute top-2 left-2 bg-indigo-600 text-white text-[9px] font-bold px-2 py-1 rounded uppercase shadow-sm">
            {{ $formation->niveau ?? 'Général' }}
        </div>
    </div>
    
    <!-- Détails (pointer-events-none) -->
    <div class="flex-1 flex flex-col justify-between pointer-events-none">
        <div>
            <div class="flex justify-between items-start mb-1">
                <h3 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $formation->nom }}</h3>
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">{{ $formation->chapitres->count() }} Leçons</span>
            </div>
            <div class="flex items-center gap-4 mb-3">
                <p class="text-sm text-indigo-600 font-medium">Par {{ $formation->creator->name ?? 'Admin' }}</p>
            </div>
            <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">{{ $formation->description }}</p>
        </div>
        
        <!-- Actions dynamiques (pointer-events-auto et z-20) -->
        <div class="mt-4 flex justify-end gap-2 pointer-events-auto relative z-20">
            @if($mode === 'catalog')
                <form action="{{ route('formations.enroll', $formation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-slate-900 text-white font-medium px-6 py-2.5 rounded-lg hover:bg-indigo-600 transition-colors text-sm shadow-sm">Emprunter (S'inscrire)</button>
                </form>
            @elseif(in_array($mode, ['creations', 'admin']))
                <a href="{{ route('formations.edit', $formation->id) }}" class="bg-slate-100 text-slate-700 font-medium px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors text-sm">Éditer</a>
                <form action="{{ route('formations.destroy', $formation->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-50 text-red-600 font-medium px-4 py-2 rounded-lg hover:bg-red-100 transition-colors text-sm">Retirer</button>
                </form>
            @endif
        </div>
    </div>
</div>