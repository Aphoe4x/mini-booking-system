<header class="bg-white border-b border-slate-200 sticky top-0 z-10 h-16 flex items-center justify-between px-6 lg:px-8">
    <div class="flex items-center gap-4">
        <button
            @click="open = ! open"
            class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-md transition-colors"
            aria-label="Toggle navigation"
        >
            <span :class="open ? 'hidden' : 'block'" class="block h-[2px] w-6 bg-slate-900 rounded"></span>
            <span :class="open ? 'block' : 'hidden'" class="hidden h-[2px] w-6 bg-slate-900 rounded"></span>
            <span :class="open ? 'block' : 'hidden'" class="h-[2px] w-6 bg-slate-900 rounded"></span>
            <span :class="open ? 'hidden' : 'block'" class="block h-[2px] w-6 bg-slate-900 rounded"></span>
        </button>
    </div>

    <div class="flex items-center gap-4 lg:gap-6">
        <!-- Global search -->
        <div class="hidden sm:flex items-center">
            <div class="relative">
                <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    placeholder="Search bookings, clients…"
                    class="pl-9 pr-3 py-1.5 text-sm text-slate-900 placeholder-slate-400 bg-slate-50 border border-slate-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all w-64"
                >
            </div>
        </div>

        <!-- Theme toggle -->
        <button
            @click="toggleTheme"
            class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-md transition-colors"
            aria-label="Toggle dark mode"
        >
            <span x-show="theme === 'light'" class="h-5 w-5 text-yellow-500">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </span>
            <span x-show="theme === 'dark'" class="h-5 w-5 text-indigo-500">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </span>
        </button>

        <!-- User profile dropdown -->
        <x-dropdown align="right" width="56">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 px-3 py-2 text-slate-700 hover:bg-slate-50 rounded-md transition-colors">
                    <div class="hidden sm:block text-sm font-medium text-slate-900">{{ Auth::user()->name }}</div>
                    <img class="h-8 w-8 rounded-full border border-slate-200 bg-slate-100" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Profile">
                    <svg class="h-4 w-4 text-slate-400 ml-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="p-2">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900 rounded-md">
                        {{ __('Profile') }}
                    </a>
                    <div class="border-t border-slate-100 pt-2 mt-2">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors font-medium" onclick="event.preventDefault(); this.closest('form').submit();">
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
        const isDark = html.classList.contains('dark');
        if (isDark) {
            html.classList.remove('dark');
            html.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            html.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Initialize theme on load
    document.addEventListener('DOMContentLoaded', function() {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = saved ? saved === 'dark' : prefersDark;
        if (isDark) {
            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.setAttribute('data-theme', 'light');
        }
    });
</script>
