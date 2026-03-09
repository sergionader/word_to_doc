<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-100 leading-tight">
            {{ __('Browse Files') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Toast notifications --}}
            @if ($conversionResult)
                <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800/50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ $conversionResult }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($conversionError)
                <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800/50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $conversionError }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-neutral-900 overflow-hidden shadow-sm dark:shadow-neutral-900/50 sm:rounded-lg border border-neutral-200 dark:border-neutral-800">
                {{-- Breadcrumbs --}}
                <div class="px-6 py-3 border-b border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/50">
                    <nav class="flex items-center space-x-2 text-sm">
                        @foreach ($breadcrumbs as $index => $crumb)
                            @if (!$loop->last)
                                <button wire:click="navigateTo('{{ $crumb['path'] }}')" class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition-colors">
                                    {{ $crumb['name'] }}
                                </button>
                                <span class="text-neutral-400 dark:text-neutral-600">/</span>
                            @else
                                <span class="text-neutral-700 dark:text-neutral-300 font-medium">{{ $crumb['name'] }}</span>
                            @endif
                        @endforeach
                    </nav>
                </div>

                {{-- Toolbar --}}
                <div class="px-6 py-2 border-b border-neutral-200 dark:border-neutral-800 flex items-center gap-2">
                    <button wire:click="navigateUp" class="inline-flex items-center px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 text-sm font-medium rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        Up
                    </button>

                    {{-- Quick nav shortcuts --}}
                    @foreach ($quickNavItems as $nav)
                        <button wire:click="navigateTo('{{ $nav['path'] }}')"
                                class="inline-flex items-center px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 text-sm font-medium rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors"
                                title="{{ $nav['path'] }}">
                            @if ($nav['icon'] === 'cloud')
                                <svg class="w-4 h-4 mr-1 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                            @elseif ($nav['icon'] === 'desktop')
                                <svg class="w-4 h-4 mr-1 text-neutral-500 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @elseif ($nav['icon'] === 'download')
                                <svg class="w-4 h-4 mr-1 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 mr-1 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            @endif
                            {{ $nav['name'] }}
                        </button>
                    @endforeach

                    {{-- Pinned folders --}}
                    @foreach ($pinnedFolders as $pinned)
                        <button wire:click="navigateTo('{{ $pinned['path'] }}')"
                                class="inline-flex items-center px-3 py-1.5 border border-amber-300 dark:border-amber-700 text-sm font-medium rounded-md text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors"
                                title="{{ $pinned['path'] }}">
                            <svg class="w-4 h-4 mr-1 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2m4-2l-3 3h2v3h2V5h2l-3-3z"/>
                            </svg>
                            {{ $pinned['name'] }}
                        </button>
                    @endforeach

                    <div class="flex-1"></div>

                    {{-- View mode toggle --}}
                    <button wire:click="toggleViewMode" class="inline-flex items-center px-2 py-1.5 border border-neutral-300 dark:border-neutral-600 text-sm rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors" title="{{ $viewMode === 'grid' ? 'Switch to list view' : 'Switch to grid view' }}">
                        @if ($viewMode === 'grid')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        @endif
                    </button>

                    {{-- Refresh button --}}
                    <button wire:click="refreshDirectory" class="inline-flex items-center px-2 py-1.5 border border-neutral-300 dark:border-neutral-600 text-sm rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors" title="Refresh directory">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>

                    {{-- Copy path button --}}
                    <button x-data="{ copied: false }"
                            x-on:click="navigator.clipboard.writeText('{{ $currentPath }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                            class="inline-flex items-center px-2 py-1.5 border border-neutral-300 dark:border-neutral-600 text-sm rounded-md text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors"
                            title="Copy path">
                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        <svg x-show="copied" x-cloak class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>

                    <span class="text-sm text-neutral-500 dark:text-neutral-400 ml-2 truncate max-w-xs">{{ $currentPath }}</span>
                </div>

                {{-- File listing --}}
                <div class="p-6">
                    @if (empty($items))
                        <p class="text-neutral-500 dark:text-neutral-400 text-center py-8">This directory is empty or contains no convertible files.</p>
                    @else
                        <div x-data="{ contextMenu: { show: false, x: 0, y: 0, filePath: '', isDir: false, isPinned: false } }" @click="contextMenu.show = false">
                            @if ($viewMode === 'grid')
                                {{-- Grid View --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    @foreach ($items as $item)
                                        @if ($item['type'] === 'directory')
                                            <div wire:click="navigateTo('{{ $item['path'] }}')"
                                                 @contextmenu.prevent="contextMenu = { show: true, x: $event.clientX, y: $event.clientY, filePath: '{{ $item['path'] }}', isDir: true, isPinned: {{ $this->isFolderPinned($item['path']) ? 'true' : 'false' }} }"
                                                 class="flex flex-col items-center p-4 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 cursor-pointer group transition-colors">
                                                <svg class="w-12 h-12 text-amber-400 dark:text-amber-500 group-hover:text-amber-500 dark:group-hover:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M10 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V8a2 2 0 00-2-2h-8l-2-2z" />
                                                </svg>
                                                <span class="mt-2 text-sm text-neutral-700 dark:text-neutral-300 text-center truncate w-full">{{ $item['name'] }}</span>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center p-4 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 cursor-pointer group relative transition-colors"
                                                 @if (str_ends_with(strtolower($item['name']), '.md'))
                                                     wire:click="readFile('{{ $item['path'] }}')"
                                                 @endif
                                                 @contextmenu.prevent="contextMenu = { show: true, x: $event.clientX, y: $event.clientY, filePath: '{{ $item['path'] }}', isDir: false, isPinned: false }">
                                                <svg class="w-12 h-12 text-blue-400 dark:text-blue-500 group-hover:text-blue-500 dark:group-hover:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                                    <path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="1" />
                                                </svg>
                                                <span class="mt-2 text-sm text-neutral-700 dark:text-neutral-300 text-center truncate w-full">{{ $item['name'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                {{-- List View --}}
                                {{-- List View Table --}}
                                <table class="w-full table-fixed">
                                    <colgroup>
                                        <col />
                                        <col style="width: 100px" />
                                        <col style="width: 140px" />
                                        <col style="width: 140px" />
                                    </colgroup>
                                    <thead>
                                        <tr class="border-b border-neutral-200 dark:border-neutral-700 text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                            <th class="text-left px-3 py-2">
                                                <button wire:click="sortItems('name')" class="hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors">
                                                    Name @if ($sortBy === 'name'){{ $sortDirection === 'asc' ? "\u{25B2}" : "\u{25BC}" }}@endif
                                                </button>
                                            </th>
                                            <th class="text-right px-3 py-2">
                                                <button wire:click="sortItems('size')" class="w-full text-right hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors">
                                                    Size @if ($sortBy === 'size'){{ $sortDirection === 'asc' ? "\u{25B2}" : "\u{25BC}" }}@endif
                                                </button>
                                            </th>
                                            <th class="text-right px-3 py-2">
                                                <button wire:click="sortItems('created_at')" class="w-full text-right hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors">
                                                    Created @if ($sortBy === 'created_at'){{ $sortDirection === 'asc' ? "\u{25B2}" : "\u{25BC}" }}@endif
                                                </button>
                                            </th>
                                            <th class="text-right px-3 py-2">
                                                <button wire:click="sortItems('modified_at')" class="w-full text-right hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors">
                                                    Modified @if ($sortBy === 'modified_at'){{ $sortDirection === 'asc' ? "\u{25B2}" : "\u{25BC}" }}@endif
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                                        @foreach ($items as $item)
                                            @if ($item['type'] === 'directory')
                                                <tr wire:click="navigateTo('{{ $item['path'] }}')"
                                                    @contextmenu.prevent="contextMenu = { show: true, x: $event.clientX, y: $event.clientY, filePath: '{{ $item['path'] }}', isDir: true, isPinned: {{ $this->isFolderPinned($item['path']) ? 'true' : 'false' }} }"
                                                    class="hover:bg-neutral-100 dark:hover:bg-neutral-800 cursor-pointer group transition-colors">
                                                    <td class="px-3 py-2.5">
                                                        <div class="flex items-center">
                                                            <svg class="w-5 h-5 text-amber-400 dark:text-amber-500 group-hover:text-amber-500 dark:group-hover:text-amber-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M10 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V8a2 2 0 00-2-2h-8l-2-2z" />
                                                            </svg>
                                                            <span class="text-sm text-neutral-700 dark:text-neutral-300 truncate">{{ $item['name'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-2.5 text-xs text-neutral-400 dark:text-neutral-500 text-right whitespace-nowrap">&mdash;</td>
                                                    <td class="px-3 py-2.5 text-xs text-neutral-400 dark:text-neutral-500 text-right whitespace-nowrap">{{ \Carbon\Carbon::createFromTimestamp($item['created_at'])->format('d M y H:i') }}</td>
                                                    <td class="px-3 py-2.5 text-xs text-neutral-400 dark:text-neutral-500 text-right whitespace-nowrap">{{ \Carbon\Carbon::createFromTimestamp($item['modified_at'])->format('d M y H:i') }}</td>
                                                </tr>
                                            @else
                                                <tr class="hover:bg-neutral-100 dark:hover:bg-neutral-800 cursor-pointer group transition-colors"
                                                    @if (str_ends_with(strtolower($item['name']), '.md'))
                                                        wire:click="readFile('{{ $item['path'] }}')"
                                                    @endif
                                                    @contextmenu.prevent="contextMenu = { show: true, x: $event.clientX, y: $event.clientY, filePath: '{{ $item['path'] }}', isDir: false, isPinned: false }">
                                                    <td class="px-3 py-2.5">
                                                        <div class="flex items-center">
                                                            <svg class="w-5 h-5 text-blue-400 dark:text-blue-500 group-hover:text-blue-500 dark:group-hover:text-blue-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                                                <path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="1" />
                                                            </svg>
                                                            <span class="text-sm text-neutral-700 dark:text-neutral-300 truncate">{{ $item['name'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-2.5 text-xs text-neutral-400 dark:text-neutral-500 text-right whitespace-nowrap">{{ number_format($item['size'] / 1024, 1) }} KB</td>
                                                    <td class="px-3 py-2.5 text-xs text-neutral-400 dark:text-neutral-500 text-right whitespace-nowrap">{{ \Carbon\Carbon::createFromTimestamp($item['created_at'])->format('d M y H:i') }}</td>
                                                    <td class="px-3 py-2.5 text-xs text-neutral-400 dark:text-neutral-500 text-right whitespace-nowrap">{{ \Carbon\Carbon::createFromTimestamp($item['modified_at'])->format('d M y H:i') }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            {{-- Context Menu --}}
                            <div x-show="contextMenu.show"
                                 x-transition
                                 :style="'position: fixed; left: ' + contextMenu.x + 'px; top: ' + contextMenu.y + 'px;'"
                                 class="z-50 bg-white dark:bg-neutral-800 rounded-md shadow-lg dark:shadow-neutral-900/50 border border-neutral-200 dark:border-neutral-700 py-1 min-w-[180px]"
                                 @click.away="contextMenu.show = false">

                                {{-- File-only options --}}
                                <template x-if="!contextMenu.isDir">
                                    <div>
                                        <button x-show="contextMenu.filePath.toLowerCase().endsWith('.md')"
                                                @click="$wire.readFile(contextMenu.filePath); contextMenu.show = false"
                                                class="w-full text-left px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 flex items-center transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Read
                                        </button>
                                        <button @click="$wire.convertFile(contextMenu.filePath); contextMenu.show = false"
                                                class="w-full text-left px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-700 dark:hover:text-amber-300 flex items-center transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span x-text="contextMenu.filePath.toLowerCase().endsWith('.md') ? 'Convert to Word' : 'Convert to Markdown'"></span>
                                        </button>
                                    </div>
                                </template>

                                {{-- Folder-only options --}}
                                <template x-if="contextMenu.isDir">
                                    <div>
                                        <button @click="$wire.setAsDefault(contextMenu.filePath); contextMenu.show = false"
                                                class="w-full text-left px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-700 dark:hover:text-amber-300 flex items-center transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Set as Default
                                        </button>
                                        <button x-show="!contextMenu.isPinned"
                                                @click="$wire.pinFolder(contextMenu.filePath); contextMenu.show = false"
                                                class="w-full text-left px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 flex items-center transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                            Pin Folder
                                        </button>
                                        <button x-show="contextMenu.isPinned"
                                                @click="$wire.unpinFolder(contextMenu.filePath); contextMenu.show = false"
                                                class="w-full text-left px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 flex items-center transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                            Unpin Folder
                                        </button>
                                    </div>
                                </template>

                                {{-- Shared option: Copy Path --}}
                                <button @click="navigator.clipboard.writeText(contextMenu.filePath); contextMenu.show = false"
                                        class="w-full text-left px-4 py-2 text-sm text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700 flex items-center transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    Copy Path
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Loading spinner overlay --}}
    <div wire:loading wire:target="readFile" class="fixed inset-0 z-40 flex items-center justify-center bg-neutral-900/50">
        <div class="bg-white dark:bg-neutral-800 rounded-lg p-6 shadow-xl flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm text-neutral-700 dark:text-neutral-300">Loading preview...</span>
        </div>
    </div>

    {{-- Markdown Preview Modal --}}
    @if ($showMarkdownPreview)
        <div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
             x-data
             x-on:keydown.escape.window="$wire.closePreview()">
            {{-- Backdrop --}}
            <div class="fixed inset-0" wire:click="closePreview">
                <div class="absolute inset-0 bg-neutral-500 dark:bg-neutral-900 opacity-75"></div>
            </div>
            {{-- Modal content --}}
            <div class="mb-6 bg-white dark:bg-neutral-800 rounded-lg overflow-hidden shadow-xl dark:shadow-neutral-900/50 sm:w-full sm:max-w-4xl sm:mx-auto relative">
                <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100 truncate pr-4">
                        {{ $previewFileName }}
                    </h3>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        {{-- Refresh button --}}
                        <button wire:click="refreshPreview" class="inline-flex items-center px-3 py-1.5 border border-neutral-400 dark:border-neutral-500 text-sm font-medium rounded-md text-neutral-700 dark:text-neutral-200 hover:bg-neutral-200 dark:hover:bg-neutral-500 transition-colors" title="Refresh">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        {{-- Convert to Word button --}}
                        <button wire:click="convertPreviewFile" class="inline-flex items-center px-3 py-1.5 border border-amber-300 dark:border-amber-700 text-sm font-medium rounded-md text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors" title="Convert to Word">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Convert to Word
                        </button>
                        {{-- Close button --}}
                        <button wire:click="closePreview" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="px-6 py-4 overflow-y-auto max-h-[70vh]">
                    <div class="prose dark:prose-invert max-w-none">
                        {!! $markdownHtml !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
