<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Book with Aphoe') }}</title>

        <!-- Apply the saved theme before paint to avoid a white flash -->
        <script>
            (function () {
                const saved = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = saved ? saved === 'dark' : prefersDark;
                document.documentElement.classList.toggle('dark', isDark);
                document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
            })();
        </script>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 antialiased font-sans transition-colors">
        <div class="min-h-screen flex flex-col justify-center py-12 px-6 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>