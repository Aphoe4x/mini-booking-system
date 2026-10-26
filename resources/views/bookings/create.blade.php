<x-app-layout
    title="New Booking"
    heading="New Booking"
    subtitle="Schedule a service appointment. You'll be notified once it's confirmed."
>

    <x-slot name="actions">
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
            Back to bookings
        </a>
    </x-slot>

    <div class="max-w-2xl">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Booking Details</h3>
                <p class="text-xs text-slate-500 mt-1">All fields are required unless marked optional.</p>
            </div>

            <form method="POST" action="{{ route('bookings.store') }}" class="px-6 py-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-full sm:col-span-1">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Service Name</label>
                        <input
                            type="text"
                            name="service_name"
                            value="{{ old('service_name') }}"
                            placeholder="e.g. Haircut, Massage, Consultation"
                            class="w-full px-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            required
                        >
                        @error('service_name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Booking Date</label>
                        <input
                            type="date"
                            name="booking_date"
                            value="{{ old('booking_date') }}"
                            class="w-full px-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            min="{{ now()->toDateString() }}"
                            required
                        >
                        @error('booking_date')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Booking Time</label>
                        <input
                            type="time"
                            name="booking_time"
                            value="{{ old('booking_time') }}"
                            class="w-full px-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            required
                        >
                        @error('booking_time')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Notes (optional)</label>
                    <textarea
                        name="notes"
                        rows="3"
                        placeholder="Any special instructions or requests…"
                        class="w-full px-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Submit Booking
                    </button>
                    <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>