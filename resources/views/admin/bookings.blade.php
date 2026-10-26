<x-app-layout
    title="All Bookings"
    heading="All Bookings"
    subtitle="Manage and review bookings across all clients."
>

    <x-slot name="actions">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-300">
                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                {{ count($bookings) }} total
            </span>
        </div>
    </x-slot>

    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 px-6 py-4 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider">All Bookings</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Confirm or cancel bookings requested by clients.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm divide-y divide-slate-100 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Client</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Service</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->user->name) }}&background=random" class="h-8 w-8 object-cover" alt="Avatar">
                                    </div>
                                    <div>
                                        <span class="block font-medium text-slate-900 dark:text-white">{{ $booking->user->name }}</span>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $booking->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $booking->service_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $booking->booking_date->format('D, M j, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $booking->booking_time->format('g:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">${{ number_format($booking->price_paid ?? 0, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$booking->status" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($booking->status === 'pending')
                                    <div class="flex items-center gap-2 justify-end">
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="inline" onsubmit="return confirmStatus('confirm')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="button" class="px-3 py-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800 rounded-md hover:bg-emerald-100 dark:hover:bg-emerald-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors" onclick="if(confirmStatus('confirm')) this.closest('form').submit()">
                                                Confirm
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="inline" onsubmit="return confirmStatus('cancel')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="button" class="px-3 py-1.5 text-xs font-medium text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800 rounded-md hover:bg-rose-100 dark:hover:bg-rose-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors" onclick="if(confirmStatus('cancel')) this.closest('form').submit()">
                                                Cancel
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($bookings->isEmpty())
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">No bookings yet.</p>
            </div>
        @endif
    </div>

    <script>
        function confirmStatus(action) {
            const verb = action === 'confirm' ? 'confirm' : 'cancel';
            return confirm(`Are you sure you want to ${verb} this booking?`);
        }
    </script>

</x-app-layout>
