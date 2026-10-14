@props(['class' => 'h-20 w-20'])

<img
    src="{{ asset('images/logo.png') }}"
    alt="SwapSkills"
    {{ $attributes->merge(['class' => $class.' object-contain']) }}
/>
