<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Files') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Toast notifications --}}
            @if ($conversionResult)
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ $conversionResult }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($conversionError)
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ $conversionError }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Breadcrumbs --}}
                <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                    <nav class="flex items-center space-x-2 text-sm">
                        @foreach ($breadcrumbs as $index => $crumb)
                            @if (!$loop->last)
                                <button wire:click="navigateTo('{{ $crumb['path'] }}')" class="text-indigo-600 hover:text-indigo-800">
                                    {{ $crumb['name'] }}
                                </button>
                                <span class="text-gray-400">/</span>
                            @else
                                <span class="text-gray-700 font-medium">{{ $crumb['name'] }}</span>
                            @endif
                        @endforeach
                    </nav>
                </div>

                {{-- Toolbar --}}
                <div class="px-6 py-2 border-b border-gray-200 flex items-center gap-2">
                    <button wire:click="navigateUp" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        Up
                    </button>

                    {{-- Quick nav shortcuts --}}
                    @foreach ($quickNavItems as $nav)
                        <button wire:click="navigateTo('{{ $nav['path'] }}')"
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                title="{{ $nav['path'] }}">
                            @if ($nav['icon'] === 'cloud')
                                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                            @elseif ($nav['icon'] === 'desktop')
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @elseif ($nav['icon'] === 'download')
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            @endif
                            {{ $nav['name'] }}
                        </button>
                    @endforeach

                    <div class="flex-1"></div>

                    {{-- View mode toggle --}}
                    <button wire:click="toggleViewMode" class="inline-flex items-center px-2 py-1.5 border border-gray-300 text-sm rounded-md text-gray-700 bg-white hover:bg-gray-50" title="{{ $viewMode === 'grid' ? 'Switch to list view' : 'Switch to grid view' }}">
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

                    <span class="text-sm text-gray-500 ml-2 truncate max-w-xs">{{ $currentPath }}</span>
                </div>

                {{-- File listing --}}
                <div class="p-6">
                    @if (empty($items))
                        <p class="text-gray-500 text-center py-8">This directory is empty or contains no .docx files.</p>
                    @else
                        <div x-data="{ contextMenu: { show: false, x: 0, y: 0, filePath: '' } }" @click="contextMenu.show = false">
                            @if ($viewMode === 'grid')
                                {{-- Grid View --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    @foreach ($items as $item)
                                        @if ($item['type'] === 'directory')
                                            <div wire:click="navigateTo('{{ $item['path'] }}')"
                                                 class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-100 cursor-pointer group">
                                                <svg class="w-12 h-12 text-yellow-400 group-hover:text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M10 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V8a2 2 0 00-2-2h-8l-2-2z" />
                                                </svg>
                                                <span class="mt-2 text-sm text-gray-700 text-center truncate w-full">{{ $item['name'] }}</span>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-100 cursor-pointer group relative"
                                                 @contextmenu.prevent="contextMenu = { show: true, x: $event.clientX, y: $event.clientY, filePath: '{{ $item['path'] }}' }">
                                                <svg class="w-12 h-12 text-blue-400 group-hover:text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                                    <path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="1" />
                                                </svg>
                                                <span class="mt-2 text-sm text-gray-700 text-center truncate w-full">{{ $item['name'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                {{-- List View --}}
                                <div class="divide-y divide-gray-100">
                                    @foreach ($items as $item)
                                        @if ($item['type'] === 'directory')
                                            <div wire:click="navigateTo('{{ $item['path'] }}')"
                                                 class="flex items-center px-3 py-2.5 rounded hover:bg-gray-100 cursor-pointer group">
                                                <svg class="w-5 h-5 text-yellow-400 group-hover:text-yellow-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M10 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V8a2 2 0 00-2-2h-8l-2-2z" />
                                                </svg>
                                                <span class="text-sm text-gray-700 truncate">{{ $item['name'] }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center px-3 py-2.5 rounded hover:bg-gray-100 cursor-pointer group"
                                                 @contextmenu.prevent="contextMenu = { show: true, x: $event.clientX, y: $event.clientY, filePath: '{{ $item['path'] }}' }">
                                                <svg class="w-5 h-5 text-blue-400 group-hover:text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                                    <path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="1" />
                                                </svg>
                                                <span class="text-sm text-gray-700 truncate flex-1">{{ $item['name'] }}</span>
                                                @if (isset($item['size']))
                                                    <span class="text-xs text-gray-400 ml-4">{{ number_format($item['size'] / 1024, 1) }} KB</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            {{-- Context Menu --}}
                            <div x-show="contextMenu.show"
                                 x-transition
                                 :style="'position: fixed; left: ' + contextMenu.x + 'px; top: ' + contextMenu.y + 'px;'"
                                 class="z-50 bg-white rounded-md shadow-lg border border-gray-200 py-1 min-w-[160px]"
                                 @click.away="contextMenu.show = false">
                                <button @click="$wire.convertFile(contextMenu.filePath); contextMenu.show = false"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Convert to Markdown
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
