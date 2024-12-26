@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex text-sm font-medium text-blue-600 border-b-2 border-blue-600 hover:text-primary-700 dark:text-white dark:hover:text-primary-500'
            : 'flex text-sm font-medium text-gray-900 hover:text-primary-700 dark:text-white dark:hover:text-primary-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
