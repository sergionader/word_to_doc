@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400 dark:text-neutral-500 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 cursor-not-allowed leading-5 rounded-md">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-200 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 leading-5 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring ring-amber-300 dark:ring-amber-600 focus:border-amber-300 dark:focus:border-amber-600 active:bg-neutral-100 dark:active:bg-neutral-700 transition ease-in-out duration-150">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-200 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 leading-5 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring ring-amber-300 dark:ring-amber-600 focus:border-amber-300 dark:focus:border-amber-600 active:bg-neutral-100 dark:active:bg-neutral-700 transition ease-in-out duration-150">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400 dark:text-neutral-500 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 cursor-not-allowed leading-5 rounded-md">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
