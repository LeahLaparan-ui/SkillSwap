@props(['skill', 'type' => 'teach'])

@php
    $colors = $type === 'teach'
        ? 'bg-sage-50 text-sage-700 ring-sage-600/20'
        : 'bg-beige-100 text-beige-700 ring-beige-500/30';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {$colors}"]) }}>
    {{ $skill->name }}
</span>
