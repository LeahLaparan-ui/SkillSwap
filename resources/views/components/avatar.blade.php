@props(['user', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-16 w-16 text-xl',
        'xl' => 'h-24 w-24 text-3xl',
    ];
    $classes = $sizes[$size] ?? $sizes['md'];
@endphp

@if ($user->avatar)
    <img
        src="{{ $user->avatarUrl() }}"
        alt="{{ $user->name }}"
        {{ $attributes->merge(['class' => "inline-flex rounded-full object-cover {$classes}"]) }}
    />
@else
    <span {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-full font-semibold text-white {$classes} {$user->avatarColor()}"]) }}>
        {{ $user->initials() }}
    </span>
@endif
