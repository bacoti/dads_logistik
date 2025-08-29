@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'size' => 'md'
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';

$variants = [
    'primary' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm focus:ring-red-500',
    'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-700 shadow-sm focus:ring-gray-500',
    'success' => 'bg-green-600 hover:bg-green-700 text-white shadow-sm focus:ring-green-500',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white shadow-sm focus:ring-yellow-500',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm focus:ring-red-500',
];

$sizes = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="mr-2">
                {!! $icon !!}
            </span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="mr-2">
                {!! $icon !!}
            </span>
        @endif
        {{ $slot }}
    </button>
@endif
