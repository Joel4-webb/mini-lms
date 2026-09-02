@props(['title' => 'Rien à afficher', 'message' => null])

<div class="col-span-full border-2 border-dashed border-slate-200 rounded-[2.5rem] p-12 text-center bg-slate-50/50">
    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm border border-slate-100">
        @if(isset($icon))
            {{ $icon }}
        @else
            <!-- Icône par défaut (dossier vide) -->
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        @endif
    </div>
    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $title }}</h3>
    @if($message)
        <p class="text-sm text-slate-500 mb-6">{{ $message }}</p>
    @endif
    {{ $slot }}
</div>