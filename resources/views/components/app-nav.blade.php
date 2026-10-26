<header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 sticky top-0 z-50 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button
            type="button"
            @click="$store.sidebar.toggle()"
            :aria-expanded="$store.sidebar.open.toString()"
            class="p-2 text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md transition-colors"
            aria-label="Toggle navigation"
        >
            <svg x-show="!$store.sidebar.open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="$store.sidebar.open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <a href="{{ route('dashboard') }}" class="lg:hidden text-slate-900 dark:text-white font-semibold tracking-tight whitespace-nowrap">
            <span class="sm:hidden">Aphoe</span>
            <span class="hidden sm:inline">{{ config('app.name', 'Book with Aphoe') }}</span>
        </a>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 lg:gap-6">
        <!-- Global search -->
        <div class="hidden sm:flex items-center">
            <div class="relative">
                <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    placeholder="Search bookings, clients…"
                    class="pl-9 pr-3 py-1.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all w-48 lg:w-64"
                >
            </div>
        </div>

        <!-- Theme toggle: icon only, state driven purely by the dark class -->
        <button
            type="button"
            onclick="toggleTheme()"
            class="p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors shadow-sm"
            aria-label="Toggle dark mode"
            title="Toggle dark mode"
        >
            <svg class="h-5 w-5 text-amber-500 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg class="hidden h-5 w-5 text-indigo-400 dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>

        <!-- User profile dropdown -->
        <x-dropdown align="right" width="56">
            <x-slot name="trigger">
                <button class="flex items-center gap-2 sm:gap-3 px-2 sm:px-3 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-md transition-colors">
                    <div class="hidden sm:block text-sm font-medium text-slate-900 dark:text-white">{{ Auth::user()->name }}</div>
                    <img class="h-8 w-8 rounded-full border border-slate-200 dark:border-slate-700 bg-slate-100" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Profile">
                    <svg class="h-4 w-4 text-slate-400 dark:text-slate-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="p-2">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white rounded-md">
                        {{ __('Profile') }}
                    </a>
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-2 mt-2">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors font-medium" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </x-slot>
        </x-dropdown>
    </div>
</header>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.toggle('dark');
        html.setAttribute('data-theme', isDark ? 'dark' : 'light');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }
</script>
