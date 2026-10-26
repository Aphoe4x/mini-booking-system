<x-app-layout
    title="My Bookings"
    heading="My Bookings"
    subtitle="Manage your upcoming and past service appointments."
>

    <x-slot name="actions">
        <a href="{{ route('bookings.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            New Booking
        </a>
    </x-slot>

    <!-- Tab Navigation -->
    <div class="flex space-x-1 mb-6 border-b border-slate-200 dark:border-slate-700">
        <a href="{{ route('bookings.index') }}?tab=upcoming"
           class="px-6 py-3 text-sm font-medium transition-colors
                  {{ request('tab', 'upcoming') === 'upcoming' ? 'border-b-2 border-blue-600 text-blue-600 dark:text-blue-400' : 'border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
            Upcoming
        </a>
        <a href="{{ route('bookings.index') }}?tab=past"
           class="px-6 py-3 text-sm font-medium transition-colors
                  {{ request('tab', 'upcoming') === 'past' ? 'border-b-2 border-blue-600 text-blue-600 dark:text-blue-400' : 'border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
            History
        </a>
    </div>

    @php
        $activeTab = request('tab', 'upcoming');
        $bookings = $activeTab === 'upcoming' ? ($upcoming ?? collect()) : ($past ?? collect());
    @endphp

    <!-- Bookings Table -->
    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 px-6 py-4 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider">
                    {{ $activeTab === 'upcoming' ? 'Upcoming Bookings' : 'Booking History' }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ count($bookings) }} booking{{ count($bookings) !== 1 ? 's' : '' }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Service</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Notes</th>
                        @if ($activeTab === 'upcoming')
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-slate-900 dark:text-white">{{ $booking->service_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                {{ $booking->booking_date->format('D, M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                {{ $booking->booking_time->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$booking->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">
                                ${{ number_format($booking->price_paid ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                {{ $booking->notes ?? '—' }}
                            </td>
                            @if ($activeTab === 'upcoming')
                                <td class="px-6 py-4 text-right">
                                    @if ($booking->status !== 'cancelled')
                                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="inline"
                                              onsubmit="return confirmCancel(this)">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800 rounded-md hover:bg-red-100 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="if(confirmCancel(this)) this.closest('form').submit()">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $activeTab === 'upcoming' ? '7' : '6' }}" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002-2h2a2 2 0 002-2m0 0l3-3m-3 3l3 3" />
                                </svg>
                                <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">
                                    {{ $activeTab === 'upcoming' ? 'No upcoming bookings.' : 'No past bookings yet.' }}
                                </p>
                                @if ($activeTab === 'upcoming')
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-300"><a href="{{ route('bookings.create') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">Schedule your first booking</a>.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmCancel() {
            return confirm('Are you sure you want to cancel this booking? This cannot be undone.');
        }
    </script>

</x-app-layout>
