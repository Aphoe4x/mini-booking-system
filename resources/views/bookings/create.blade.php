<x-app-layout
    title="New Booking"
    heading="New Booking"
    subtitle="Choose a company and service — Aphoe will coordinate the details with them."
>

    <x-slot name="actions">
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
            ← Back to bookings
        </a>
    </x-slot>

    <div class="max-w-2xl">
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50 px-6 py-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider">Booking Details</h3>
                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">All fields are required unless marked optional.</p>
            </div>

            <form method="POST" action="{{ route('bookings.store') }}" class="px-6 py-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Company -->
                    <div class="col-span-full sm:col-span-1">
                        <label for="company_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Company</label>
                        <select name="company_id" id="company_id"
                            class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            required>
                            <option value="">Select a company</option>
                            @foreach ($companies as $company)
                                @php
                                    $availableServices = $company->services->map(function ($service) {
                                        return [
                                            'id' => $service->id,
                                            'company_service_id' => $service->pivot->id,
                                            'name' => $service->name,
                                            'price' => (float) $service->pivot->price,
                                            'duration' => $service->pivot->duration_minutes ?? $service->duration_minutes,
                                        ];
                                    })->values();
                                @endphp
                                <option value="{{ $company->id }}"
                                    @selected((string) old('company_id', $preselectedCompany) === (string) $company->id)
                                    data-services="{{ $availableServices->toJson() }}">
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service -->
                    <div class="col-span-full sm:col-span-1">
                        <label for="service_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Service</label>
                        <select name="service_id" id="service_id"
                            class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            required>
                            <option value="">Select a service</option>
                        </select>
                        <input type="hidden" name="company_service_id" id="company_service_id" value="{{ old('company_service_id') }}">
                        @error('company_service_id')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Booking Date -->
                    <div class="col-span-full sm:col-span-1">
                        <label for="booking_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Booking Date</label>
                        <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date') }}"
                            class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            min="{{ now()->toDateString() }}" required>
                        @error('booking_date')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Booking Time -->
                    <div class="col-span-full sm:col-span-1">
                        <label for="booking_time" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Booking Time</label>
                        <input type="time" name="booking_time" id="booking_time" value="{{ old('booking_time') }}"
                            class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            min="09:00" max="17:59" required>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Monday–Friday, 9:00 AM – 6:00 PM.</p>
                        @error('booking_time')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-span-full">
                        <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Notes (optional)</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Any special instructions or requests…"
                            class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price summary -->
                <div class="border border-slate-200 dark:border-slate-600 rounded-lg p-4 bg-blue-50 dark:bg-blue-900/10">
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Price Summary</h4>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-600 dark:text-slate-300">Service Price</span>
                        <span id="price-display" class="font-medium text-slate-900 dark:text-white">$0.00</span>
                    </div>
                    <div class="flex justify-between text-sm font-medium text-slate-900 dark:text-white">
                        <span>Total</span>
                        <span id="total-display" class="font-bold">$0.00</span>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 pt-4">
                    <a href="{{ route('bookings.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-sm disabled:opacity-70 disabled:pointer-events-none">
                        Book Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const companySelect = document.getElementById('company_id');
            const serviceSelect = document.getElementById('service_id');
            const priceDisplay = document.getElementById('price-display');
            const totalDisplay = document.getElementById('total-display');
            const companyServiceIdInput = document.getElementById('company_service_id');

            const preselectedService = @json($preselectedService);
            const initialCompanyServiceId = @json(old('company_service_id'));

            function resetPrice() {
                priceDisplay.textContent = '$0.00';
                totalDisplay.textContent = '$0.00';
                companyServiceIdInput.value = '';
            }

            function updateServices() {
                const selectedOption = companySelect.options[companySelect.selectedIndex];
                const services = selectedOption && selectedOption.dataset.services
                    ? JSON.parse(selectedOption.dataset.services)
                    : [];

                serviceSelect.innerHTML = '<option value="">Select a service</option>';

                services.forEach(function (svc) {
                    const opt = document.createElement('option');
                    opt.value = svc.id;
                    opt.textContent = svc.name + ' — $' + Number(svc.price).toFixed(2);
                    opt.dataset.price = svc.price;
                    opt.dataset.companyServiceId = svc.company_service_id;
                    serviceSelect.appendChild(opt);
                });

                const hasServices = services.length > 0;
                serviceSelect.disabled = !hasServices;

                const wanted = initialCompanyServiceId
                    ? services.find(function (s) { return String(s.company_service_id) === String(initialCompanyServiceId); })
                    : (preselectedService
                        ? services.find(function (s) { return String(s.id) === String(preselectedService); })
                        : null);

                if (wanted) {
                    serviceSelect.value = wanted.id;
                }

                updatePrice();
            }

            function updatePrice() {
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const hasService = selectedOption && selectedOption.value;

                if (hasService) {
                    const price = Number(selectedOption.dataset.price);
                    priceDisplay.textContent = '$' + price.toFixed(2);
                    totalDisplay.textContent = '$' + price.toFixed(2);
                    companyServiceIdInput.value = selectedOption.dataset.companyServiceId;
                } else {
                    resetPrice();
                }
            }

            companySelect.addEventListener('change', updateServices);
            serviceSelect.addEventListener('change', updatePrice);

            updateServices();
        })();
    </script>

</x-app-layout>
