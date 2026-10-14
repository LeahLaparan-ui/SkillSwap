@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-beige-300 focus:border-sage-500 focus:ring-sage-500 rounded-md shadow-sm']) }}>
