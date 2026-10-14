@props(['status'])

@php
    $styles = [
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'accepted' => 'bg-sage-50 text-sage-700 ring-sage-600/20',
        'declined' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'cancelled' => 'bg-beige-100 text-beige-600 ring-beige-500/20',
        'completed' => 'bg-sage-600 text-white ring-sage-700/20',
    ];
    $classes = $styles[$status] ?? $styles['pending'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize ring-1 ring-inset {$classes}"]) }}>
    {{ $status }}
</span>
