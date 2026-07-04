@extends('layouts.app')

@section('content')
<h2>New Booking</h2>

<form method="POST" action="{{ route('bookings.store') }}" class="col-md-6">
    @csrf

    <div class="mb-3">
        <label class="form-label">Service</label>
        <input type="text" name="service_name" value="{{ old('service_name') }}"
               class="form-control @error('service_name') is-invalid @enderror" required>
        @error('service_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="booking_date" value="{{ old('booking_date') }}"
               class="form-control @error('booking_date') is-invalid @enderror" required>
        @error('booking_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Time</label>
        <input type="time" name="booking_time" value="{{ old('booking_time') }}"
               class="form-control @error('booking_time') is-invalid @enderror" required>
        @error('booking_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Notes (optional)</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit Booking</button>
</form>
@endsection
