@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-amber-400 dark:border-amber-500 text-sm font-medium leading-5 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-amber-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-500 dark:text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-200 hover:border-neutral-300 dark:hover:border-neutral-600 focus:outline-none focus:text-neutral-700 dark:focus:text-neutral-200 focus:border-neutral-300 dark:focus:border-neutral-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
