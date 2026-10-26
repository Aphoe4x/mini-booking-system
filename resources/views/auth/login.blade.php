<x-guest-layout>
    <div class="flex justify-center">
        <a href="{{ route('bookings.index') }}" class="flex items-center gap-2 text-slate-900 font-semibold text-xl tracking-tight group">
            <svg class="h-8 w-8 text-blue-600 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 011 1M7 20h4v-2H7z" />
            </svg>
            <span>{{ config('app.name', 'Book with Aphoe') }}</span>
        </a>
    </div>

    <div class="mt-8">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900 tracking-tight">Sign in</h2>
        <p class="mt-2 text-center text-sm text-slate-600">Sign in to Book with Aphoe, your personal booking agent for trusted local companies.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
            @csrf

            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-700" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->has('email'))
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-700" role="alert">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        class="block w-full px-3 py-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 font-medium hover:text-blue-500" href="{{ route('password.request') }}">Forgot password?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="block w-full px-3 py-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50">
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="h-4 w-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 focus:ring-1">
                    <label for="remember" class="ml-2 block text-sm text-slate-600">Remember me</label>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all disabled:opacity-70 disabled:pointer-events-none">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>