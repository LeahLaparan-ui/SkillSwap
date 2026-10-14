@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-sage-400 text-sm font-medium leading-5 text-beige-900 focus:outline-none focus:border-sage-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-beige-500 hover:text-beige-700 hover:border-beige-300 focus:outline-none focus:text-beige-700 focus:border-beige-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
