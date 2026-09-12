@props(['active'])

@php
$classes = ($active ?? false) ? 'nb on' : 'nb';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
