@props([
    'color' => '#D32F2F', // Rouge par défaut
    'opacity' => '0.1',   // 10% d'opacité par défaut
    'size' => '16px'      // Espacement de 16px par défaut
])

<div {{ $attributes->merge(['class' => 'pointer-events-none']) }} 
     style="background-image: radial-gradient({{ $color }} 1px, transparent 1px); background-size: {{ $size }} {{ $size }}; opacity: {{ $opacity }};">
</div>