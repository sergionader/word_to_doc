<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Word to Markdown</title>

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
                    <span class="font-serif font-semibold text-xl tracking-tight">Word to Markdown</span>
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
                    <h1 class="font-serif text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl">
                        Convert Word docs<br>to Markdown
                    </h1>
                    <p class="mt-6 text-lg text-neutral-600 dark:text-neutral-400 leading-relaxed font-light">
                        Browse your file system, right-click any <strong class="font-medium">.docx</strong> or <strong class="font-medium">.md</strong> file and convert between Word and Markdown instantly. Or drag and drop files for quick conversion.
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                </svg>
                            </div>
                            <h3 class="font-serif font-semibold text-lg">File Browser</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">Navigate your file system with quick-access shortcuts to iCloud Drive, Desktop, and more. Browse .docx and .md files.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 mb-3">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M2.985 19.644l3.181-3.182" />
                                </svg>
                            </div>
                            <h3 class="font-serif font-semibold text-lg">Right-Click Convert</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">Right-click any .docx or .md file in the browser to convert it with one click.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 mb-3">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>
                            </div>
                            <h3 class="font-serif font-semibold text-lg">Drag & Drop</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">Upload .docx or .md files by dragging them into the upload zone for instant conversion.</p>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-6 text-center text-xs text-neutral-400 dark:text-neutral-600">
                Powered by Pandoc
            </footer>
        </div>
    </body>
</html>
