@props(['tabs', 'default'])

<div x-data="{ tab: '{{ $default }}' }" class="w-full">
    <!-- Barre de navigation (Filtres) -->
    <div class="flex flex-wrap gap-2 mb-8 bg-slate-100/50 p-1.5 rounded-2xl border border-slate-200 max-w-fit">
        @foreach($tabs as $key => $label)
            <button @click="tab = '{{ $key }}'" 
                    :class="tab === '{{ $key }}' ? 'bg-white text-indigo-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100/75 border-transparent'" 
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all border">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Conteneur des vues -->
    <div class="relative min-h-[400px]">
        {{ $slot }}
    </div>
</div>