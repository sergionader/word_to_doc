<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-100 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-neutral-900 shadow-sm dark:shadow-neutral-900/50 sm:rounded-lg border border-neutral-200 dark:border-neutral-800">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
                                {{ __('Default Directory') }}
                            </h2>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                {{ __('Set the default directory that opens when you visit the file browser. You can also right-click any folder in the browser and select "Set as Default".') }}
                            </p>
                        </header>

                        <form wire:submit="save" class="mt-6 space-y-6">
                            <div>
                                <x-input-label for="defaultFolder" :value="__('Default Folder Path')" />
                                <div class="flex gap-2 mt-1">
                                    <x-text-input
                                        wire:model="defaultFolder"
                                        id="defaultFolder"
                                        type="text"
                                        class="block w-full"
                                        placeholder="/Users/username/Documents"
                                    />
                                    <a href="{{ route('browse') }}"
                                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors flex-shrink-0"
                                       title="Browse and right-click a folder to set as default">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                        {{ __('Browse') }}
                                    </a>
                                </div>
                                <x-input-error :messages="$errors->get('defaultFolder')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if ($defaultFolder)
                                    <button type="button" wire:click="clearDefault"
                                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                                        {{ __('Clear Default') }}
                                    </button>
                                @endif

                                @if ($saved)
                                    <p x-data="{ show: true }"
                                       x-show="show"
                                       x-transition
                                       x-init="setTimeout(() => show = false, 2000)"
                                       class="text-sm text-green-600 dark:text-green-400">
                                        {{ __('Saved.') }}
                                    </p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            {{-- Pinned Folders Management --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-neutral-900 shadow-sm dark:shadow-neutral-900/50 sm:rounded-lg border border-neutral-200 dark:border-neutral-800">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
                                {{ __('Pinned Folders') }}
                            </h2>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                {{ __('Manage your pinned folders. You can pin folders by right-clicking them in the file browser.') }}
                            </p>
                        </header>

                        <div class="mt-6 space-y-2">
                            @php
                                $pinnedFolders = auth()->user()->pinned_folders ?? [];
                            @endphp

                            @if (empty($pinnedFolders))
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">No pinned folders yet. Right-click a folder in the file browser to pin it.</p>
                            @else
                                @foreach ($pinnedFolders as $index => $folder)
                                    <div class="flex items-center justify-between px-4 py-3 bg-neutral-50 dark:bg-neutral-800/50 rounded-lg border border-neutral-200 dark:border-neutral-700">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <svg class="w-5 h-5 text-amber-500 dark:text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ $folder['name'] }}</p>
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ $folder['path'] }}</p>
                                            </div>
                                        </div>
                                        <button wire:click="unpinFolder('{{ $folder['path'] }}')"
                                                class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors flex-shrink-0 ml-4"
                                                title="Unpin">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
