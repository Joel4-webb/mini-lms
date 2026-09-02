@props(['title', 'subtitle' => null])

<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 border-b border-slate-100 pb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-sm text-slate-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if($slot->isNotEmpty())
        <div class="shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>