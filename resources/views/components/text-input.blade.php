@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 focus:border-amber-500 dark:focus:border-amber-500 focus:ring-amber-500 dark:focus:ring-amber-500 rounded-md shadow-sm dark:placeholder-neutral-500']) }}>
