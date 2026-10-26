<x-app-layout
    title="My Bookings"
    heading="My Bookings"
    subtitle="Manage your upcoming and past service appointments."
>

    <x-slot name="actions">
        <div x-data="{ open: false, activeTab: 'upcoming' }" class="flex items-center gap-3">
            <button
                type="button"
                @click="open = true"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Booking
            </button>

            <!-- New Booking Modal -->
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
            >
                <div
                    x-show="open"
                    x-on:click="open = false"
                    class="fixed inset-0 transform transition-all"
                >
                    <div class="absolute inset-0 bg-gray-500 dark:bg-slate-900 opacity-75"></div>
                </div>

                <div
                    x-show="open"
                    class="mb-6 bg-white dark:bg-slate-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">New Booking</h3>
                        </div>
                        <button
                            type="button"
                            @click="open = false"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('bookings.store') }}" class="px-6 py-4 space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Service Name</label>
                                <input
                                    type="text"
                                    name="service_name"
                                    value="{{ old('service_name') }}"
                                    placeholder="e.g. Haircut, Massage, Consultation"
                                    class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                    required
                                    autofocus
                                >
                                @error('service_name')
                                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Booking Date</label>
                                <input
                                    type="date"
                                    name="booking_date"
                                    value="{{ old('booking_date') }}"
                                    class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                    min="{{ now()->toDateString() }}"
                                    required
                                >
                                @error('booking_date')
                                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Booking Time</label>
                                <input
                                    type="time"
                                    name="booking_time"
                                    value="{{ old('booking_time') }}"
                                    class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                    required
                                >
                                @error('booking_time')
                                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notes (optional)</label>
                                <textarea
                                    name="notes"
                                    rows="2"
                                    placeholder="Any special instructions or requests…"
                                    class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                >{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <button
                                type="button"
                                @click="open = false"
                                class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                            >
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-70 disabled:pointer-events-none">
                                Submit Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex space-x-1 mb-6 border-b border-slate-200 dark:border-slate-700">
            <button
                @click="activeTab = 'upcoming'"
                :class="activeTab === 'upcoming'
                    ? 'border-b-2 border-blue-600 text-blue-600 dark:text-blue-400'
                    : 'border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                class="px-6 py-3 text-sm font-medium transition-colors"
            >
                Upcoming
            </button>
            <button
                @click="activeTab = 'past'"
                :class="activeTab === 'past'
                    ? 'border-b-2 border-blue-600 text-blue-600 dark:text-blue-400'
                    : 'border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                class="px-6 py-3 text-sm font-medium transition-colors"
            >
                History
            </button>
        </div>

        <!-- Upcoming Bookings -->
        <div x-show="activeTab === 'upcoming'" x-transition>
            <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider">Upcoming Bookings</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ count($upcoming ?? []) }} booking{{ count($upcoming ?? []) !== 1 ? 's' : '' }} scheduled</p>
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Notes</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse ($upcoming as $booking)
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
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $booking->notes ?? '&mdash;' }}
                                    </td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002-2h2a2 2 0 002-2m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">No upcoming bookings.</p>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-300"><a href="{{ route('bookings.create') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">Schedule your first booking</a>.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Past Bookings (History) -->
        <div x-show="activeTab === 'past'" x-transition>
            <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider">Booking History</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Your completed and cancelled bookings.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm divide-y divide-slate-200 dark:divide-slate-800">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Service</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse ($past as $booking)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-slate-900 dark:text-white">{{ $booking->service_name }}</span>
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
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $booking->notes ?? '&mdash;' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002-2h2a2 2 0 002-2m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">No past bookings yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

</x-app-layout>

<script>
    function confirmCancel(form) {
        return confirm('Are you sure you want to cancel this booking? This cannot be undone.');
    }
</script>