@props([
    'variant' => 'primary',
    'iconOnly' => false,
    'srText' => '',
    'href' => false,
    'size' => 'base',
    'disabled' => false,
    'pill' => false,
    'squared' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 transition-all duration-200
        font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed
        focus:outline-none focus:ring-2 focus:ring-offset-2
        focus:ring-offset-white dark:focus:ring-offset-dark-eval-2';

    switch ($variant) {
        case 'primary':
            $variantClasses = 'bg-blue-600 text-white
                hover:bg-blue-700
                focus:ring-blue-500';
            break;

        case 'secondary':
            $variantClasses = 'bg-white text-gray-600 border border-gray-200
                hover:bg-gray-100
                focus:ring-blue-500
                dark:bg-dark-eval-1 dark:border-dark-eval-3
                dark:text-gray-300 dark:hover:bg-dark-eval-2';
            break;

        case 'success':
            $variantClasses = 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500';
            break;

        case 'danger':
            $variantClasses = 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500';
            break;

        case 'warning':
            $variantClasses = 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-400';
            break;

        case 'info':
            $variantClasses = 'bg-sky-600 text-white hover:bg-sky-700 focus:ring-sky-500';
            break;

        case 'black':
            $variantClasses = 'bg-black text-gray-300 hover:bg-gray-800 hover:text-white focus:ring-gray-700';
            break;

        default:
            $variantClasses = 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500';
    }

    switch ($size) {
        case 'sm':
            $sizeClasses = $iconOnly ? 'p-2' : 'px-3 py-1.5 text-sm';
            break;

        case 'lg':
            $sizeClasses = $iconOnly ? 'p-3.5' : 'px-6 py-3 text-lg';
            break;

        case 'base':
        default:
            $sizeClasses = $iconOnly ? 'p-2.5' : 'px-4 py-2 text-base';
            break;
    }

    $radiusClasses = 'rounded-lg';
    if ($pill) {
        $radiusClasses = 'rounded-full';
    }
    if ($squared) {
        $radiusClasses = 'rounded-md';
    }

    $classes = "$baseClasses $sizeClasses $variantClasses $radiusClasses";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
        @if ($iconOnly)
            <span class="sr-only">{{ $srText }}</span>
        @endif
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        {{ $slot }}
        @if ($iconOnly)
            <span class="sr-only">{{ $srText }}</span>
        @endif
    </button>
@endif
