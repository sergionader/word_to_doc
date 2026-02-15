<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Word to Markdown</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-50">
        <div class="min-h-screen flex flex-col">
            {{-- Header --}}
            <header class="py-6 px-6 flex items-center justify-between max-w-5xl mx-auto w-full">
                <div class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="font-bold text-xl text-gray-900">Word to Markdown</span>
                </div>
                @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/browse') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Browse Files</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Register</a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            {{-- Hero --}}
            <main class="flex-1 flex items-center justify-center px-6">
                <div class="max-w-2xl text-center">
                    <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">
                        Convert Word docs to Markdown
                    </h1>
                    <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                        Browse your file system, right-click any <strong>.docx</strong> file and convert it to clean Markdown instantly. Or drag and drop files for quick conversion.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                            <a href="{{ url('/browse') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Open File Browser
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Get Started
                            </a>
                        @endauth
                    </div>

                    {{-- Features --}}
                    <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-8 text-left">
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-100 mb-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">File Browser</h3>
                            <p class="mt-1 text-sm text-gray-500">Navigate your file system with quick-access shortcuts to iCloud Drive, Desktop, and more.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-100 mb-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Right-Click Convert</h3>
                            <p class="mt-1 text-sm text-gray-500">Right-click any .docx file in the browser to convert it to Markdown with one click.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-100 mb-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Drag & Drop</h3>
                            <p class="mt-1 text-sm text-gray-500">Upload .docx files by dragging them into the upload zone for instant conversion.</p>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-6 text-center text-xs text-gray-400">
                Powered by Pandoc
            </footer>
        </div>
    </body>
</html>
