@props(['disabled' => false, 'error' => false])

@php
    $classes = $error ? 'form-control is-invalid' : 'form-control'
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
