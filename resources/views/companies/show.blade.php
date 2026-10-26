<x-app-layout
    title="{{ $company->name }}"
    heading="{{ $company->name }}"
    subtitle="{{ $company->city }} • {{ $availableServicesCount }} service{{ $availableServicesCount !== 1 ? 's' : '' }} available"
>

    <x-slot name="actions">
        <a href="{{ route('companies.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
            ← Back to companies
        </a>
    </x-slot>

    <!-- Company Header -->
    <div class="mb-8 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-0">
            @php
                $initials = collect(explode(' ', $company->name))->take(2)->map(fn ($word) => mb_substr($word, 0, 1))->implode('');
            @endphp
            <div class="relative h-64 md:h-auto md:min-h-[280px] bg-gradient-to-br from-sky-500 via-blue-600 to-indigo-700 flex items-center justify-center">
                <span class="text-6xl font-bold tracking-tight text-white/90">{{ strtoupper($initials) }}</span>
                <span class="absolute bottom-4 left-4 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 backdrop-blur-sm text-white text-[10px] font-semibold uppercase tracking-wider">
                    Active
                </span>
            </div>
            <div class="p-6 flex flex-col justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-1">{{ $company->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-300">
                        <svg class="h-4 w-4 inline mr-1 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $company->city }}
                    </p>
                    @if ($company->phone)
                        <p class="text-sm text-slate-500 dark:text-slate-300 mt-1">
                            <svg class="h-4 w-4 inline mr-1 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011 1.185l.534 1.604a2 2 0 011.786 2.415l.534-.179a2 2 0 011.746 0l.534.179a2 2 0 011.786 2.415l.534-1.604a1 1 0 011-1.185l.284-.085A2 2 0 0115 7v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zM9 17v1a1 1 0 001 1h6a1 1 0 001-1v-1a2 2 0 10-4 0v.5a2 2 0 01-2 2 2 2 0 01-2-2z" />
                            </svg>
                            {{ $company->phone }}
                        </p>
                    @endif
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ $company->description }}</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('bookings.create') }}?company={{ $company->id }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Book a Service
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Services -->
    @if ($categories->count())
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider mb-6">Services</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($categories as $category => $services)
                    @foreach ($services as $service)
                        @php
                            $companyService = $company->companyServices->firstWhere('service_id', $service->id);
                        @endphp
                        @if ($companyService)
                        <div class="group rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 hover:border-blue-300 dark:hover:border-blue-500 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $service->name }}</h4>
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">${{ number_format($companyService->price, 2) }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-300 mb-3 line-clamp-2">{{ $service->description }}</p>
                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700">
                                <span class="text-xs text-slate-400 dark:text-slate-500">
                                    {{ $companyService->duration_minutes ?? $service->duration_minutes }} min
                                </span>
                                <a href="{{ route('bookings.create') }}?company={{ $company->id }}&service={{ $service->id }}"
                                   class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 border border-transparent rounded hover:bg-blue-700 transition-colors">
                                    Book
                                </a>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.707.293l5.474 5.474a2 2 0 01.182 1.182l.028.099c.072.351.087.735.05 1.103l-.028.17c-.024.197-.106.38-.22.555a2 2 0 01-.893.339l-.614.074a2 2 0 01-1.732-.603l-.054-.108a2 2 0 01-.433-.873l-.028-.099a2 2 0 01-.182-.767l-.028-.17c-.04-.192-.11-.37-.2-.54a2 2 0 01-.324-.69l-.028-.098a2 2 0 01-.12-.323" />
            </svg>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">No services available at this time.</p>
        </div>
    @endif

</x-app-layout>