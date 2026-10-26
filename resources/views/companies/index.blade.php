<x-app-layout
    title="Companies"
    heading="Browse Companies"
    subtitle="Aphoe connects you with trusted local companies — compare services and book in seconds."
>

    <x-slot name="actions">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
            ← Back to home
        </a>
    </x-slot>

    <!-- Search -->
    <div class="mb-6">
        <form method="GET" action="{{ route('companies.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search companies by name or city…"
                class="flex-1 px-4 py-2.5 text-sm text-slate-900 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Search
            </button>
            @if (request('search'))
                <a href="{{ route('companies.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if ($companies->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($companies as $company)
                @php
                    $gradients = [
                        'from-sky-500 via-blue-600 to-indigo-600',
                        'from-emerald-500 via-teal-600 to-cyan-700',
                        'from-amber-500 via-orange-600 to-rose-600',
                        'from-fuchsia-500 via-purple-600 to-indigo-700',
                        'from-rose-500 via-pink-600 to-purple-700',
                    ];
                    $gradient = $gradients[$loop->index % count($gradients)];
                    $initials = collect(explode(' ', $company->name))->take(2)->map(fn ($word) => mb_substr($word, 0, 1))->implode('');
                @endphp
                <div class="group rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm hover:shadow-md transition-all overflow-hidden">
                    <div class="relative h-32 bg-gradient-to-br {{ $gradient }} flex items-center justify-center">
                        <span class="text-4xl font-bold tracking-tight text-white/90">{{ strtoupper($initials) }}</span>
                        <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 backdrop-blur-sm text-white text-[10px] font-semibold uppercase tracking-wider">
                            Active
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start gap-3 mb-1">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $company->name }}
                            </h3>
                            <span class="shrink-0 text-sm font-semibold text-slate-900 dark:text-white">
                                From ${{ number_format($company->min_price ?? 0, 0) }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-300 line-clamp-2">{{ $company->description }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1">
                            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $company->city }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $company->available_services }} service{{ $company->available_services !== 1 ? 's' : '' }}
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('companies.show', $company) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                View Services
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $companies->links() }}
    @else
        <div class="text-center py-16">
            <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.707.293l5.474 5.474a2 2 0 01.182 1.182l.028.099c.072.351.087.735.05 1.103l-.028.17c-.024.197-.106.38-.22.555a2 2 0 01-.893.339l-.614.074a2 2 0 01-1.732-.603l-.054-.108a2 2 0 01-.433-.873l-.028-.099a2 2 0 01-.182-.767l-.028-.17c-.04-.192-.11-.37-.2-.54a2 2 0 01-.324-.69l-.028-.098a2 2 0 01-.12-.323M3 12a2 2 0 012-2h2M3 12v1a2 2 0 002 2v1m-4-4h2m2 12h2a2 2 0 002-2v-1a2 2 0 012-2h2m-2-4l5.474-5.474a2 2 0 011.182-.767l.614-.074a2 2 0 011.106 0l.614.074a2 2 0 011.732.603l.054.108a2 2 0 01.433.873l.028.099a2 2 0 01.182.767l.028.17c.09.355.152.744.152 1.132v.099c0 .387-.062.778-.182 1.13l-.028.17c-.071.198-.165.38-.286.552a2 2 0 01-.893.339l-.614.074a2 2 0 01-1.732-.603l-.054-.108a2 2 0 01-.433-.873l-.028-.099a2 2 0 01-.182-.767l-.028-.17c-.04-.192-.11-.37-.2-.54a2 2 0 01-.324-.69l-.028-.098a2 2 0 01-.12-.323" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">No companies found</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">Try adjusting your search or check back later.</p>
        </div>
    @endif

</x-app-layout>