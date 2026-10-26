<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Mini Booking') }} — {{ $title ?? 'Dashboard' }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased font-sans">

        <x-app-nav />

        <div x-data="{ open: false }" class="flex flex-col lg:flex-row min-h-screen">
            <!-- Sidebar (always visible on desktop, togglable on mobile/tablet) -->
            <aside
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed lg:relative lg:static lg:inset-0 lg:h-full lg:w-64 lg:border-r lg:border-slate-200 dark:border-slate-700 z-40 bg-white dark:bg-slate-900 hidden lg:flex flex-col"
            >
                <div class="flex items-center h-16 border-b border-slate-200 dark:border-slate-700 px-6 shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-slate-900 dark:text-white font-semibold text-lg tracking-tight group">
                        <svg class="h-7 w-7 text-blue-600 dark:text-blue-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 011 1M7 20h4v-2H7z" />
                        </svg>
                        <span>{{ config('app.name', 'Mini Booking') }}</span>
                    </a>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-1" aria-label="Sidebar">
                    <x-dashboard-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-dashboard-nav-link>

                    <x-dashboard-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                        {{ __('My Bookings') }}
                    </x-dashboard-nav-link>

                    <x-dashboard-nav-link :href="route('bookings.create')" :active="request()->routeIs('bookings.create')">
                        {{ __('New Booking') }}
                    </x-dashboard-nav-link>

                    @if (Auth::user()->isAdmin())
                        <div class="border-t border-slate-100 dark:border-slate-700 pt-4 mt-4 space-y-1">
                            <x-dashboard-nav-link :href="route('admin.bookings')" :active="request()->routeIs('admin.*')">
                                {{ __('All Bookings') }}
                            </x-dashboard-nav-link>
                        </div>
                    @endif
                </nav>
            </aside>

            <!-- Main content -->
            <main class="flex-1 flex flex-col min-w-0">
                <!-- Page content -->
                <div class="flex-1 p-6 lg:p-8 overflow-y-auto">
                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 text-blue-800 dark:text-blue-200 text-sm flex items-center gap-3">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-800 dark:text-red-200 text-sm">
                            <h4 class="font-semibold mb-1">{{ __('Please correct the following errors:') }}</h4>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="max-w-7xl mx-auto">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6 gap-4">
                            <div>
                                <h1 class="text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">{{ $heading ?? __('Dashboard') }}</h1>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $subtitle ?? '' }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3">
                                {{ $actions ?? '' }}
                            </div>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

        <!-- Mobile sidebar overlay -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.away="open = false"
            class="fixed inset-0 z-20 bg-black/50 lg:hidden"
        ></div>

    </body>
</html>