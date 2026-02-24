<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Word to MD') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <!-- Theme (prevent FOUC) -->
        <script>
            (function(){var t=localStorage.getItem('theme')||'dark';if(t==='dark')document.documentElement.classList.add('dark');})();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cormorant:400,500,600,700&family=outfit:300,400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-neutral-50 dark:bg-neutral-950 text-neutral-900 dark:text-neutral-100">
        <div class="min-h-screen flex flex-col">
            {{-- Header --}}
            <header class="py-6 px-6 flex items-center justify-between max-w-5xl mx-auto w-full">
                <div class="flex items-center gap-2.5">
                    <svg class="h-7 w-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span class="font-semibold text-xl tracking-tight">{{ config('app.name', 'Word to MD') }}</span>
                </div>
                <nav class="flex items-center gap-3">
                    <x-theme-toggle />
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/browse') }}" class="text-sm font-medium text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors">Browse Files</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 dark:bg-amber-500 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-amber-700 dark:hover:bg-amber-400 transition-colors">Register</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </header>

            {{-- Hero --}}
            <main class="flex-1 flex items-center justify-center px-6">
                <div class="max-w-2xl text-center">
                    <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl">
                        Word & Markdown,<br>back and forth
                    </h1>
                    <p class="mt-6 text-lg text-neutral-600 dark:text-neutral-400 leading-relaxed font-light">
                        Browse your file system, read <strong class="font-medium">.md</strong> files directly in the browser, and convert between <strong class="font-medium">.docx</strong> and <strong class="font-medium">.md</strong> with a right-click. Drag and drop files for quick conversion.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                            <a href="{{ url('/browse') }}" class="inline-flex items-center px-6 py-3 bg-amber-600 dark:bg-amber-500 border border-transparent rounded-lg font-medium text-sm text-white uppercase tracking-widest hover:bg-amber-700 dark:hover:bg-amber-400 transition-colors">
                                Open File Browser
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-amber-600 dark:bg-amber-500 border border-transparent rounded-lg font-medium text-sm text-white uppercase tracking-widest hover:bg-amber-700 dark:hover:bg-amber-400 transition-colors">
                                Get Started
                            </a>
                        @endauth
                    </div>

                    {{-- Features --}}
                    <div class="mt-20 grid grid-cols-1 sm:grid-cols-3 gap-10 text-left">
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 mb-3">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-lg">Read Markdown</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">Click any .md file to read it rendered in the browser. No extra tools needed.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 mb-3">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M2.985 19.644l3.181-3.182" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-lg">Right-Click Convert</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">Right-click any .docx or .md file to convert between formats with one click.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 mb-3">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-lg">File Browser</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">Navigate your file system with shortcuts to iCloud Drive, Desktop, and Downloads. Drag and drop files for quick conversion.</p>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-4 text-center text-xs text-neutral-400 dark:text-neutral-600">
                Powered by <a href="https://adaptai.chat/en/about" target="_blank" rel="noopener noreferrer" class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors">TimeSaver Systems</a>
            </footer>
        </div>
    </body>
</html>
