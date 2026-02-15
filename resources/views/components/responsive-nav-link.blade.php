@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-amber-400 dark:border-amber-500 text-start text-base font-medium text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/20 focus:outline-none focus:text-amber-800 dark:focus:text-amber-200 focus:bg-amber-100 dark:focus:bg-amber-900/30 focus:border-amber-700 dark:focus:border-amber-400 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:border-neutral-300 dark:hover:border-neutral-600 focus:outline-none focus:text-neutral-800 dark:focus:text-neutral-200 focus:bg-neutral-50 dark:focus:bg-neutral-800 focus:border-neutral-300 dark:focus:border-neutral-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
