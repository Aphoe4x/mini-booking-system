@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Bookings</h2>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">+ New Booking</a>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Notes</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $booking)
            <tr>
                <td>{{ $booking->service_name }}</td>
                <td>{{ $booking->booking_date->format('D, M j Y') }}</td>
                <td>{{ $booking->booking_time->format('g:i A') }}</td>
                <td>
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'secondary' : 'warning') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>{{ $booking->notes ?? '—' }}</td>
                <td>
                    @if ($booking->status !== 'cancelled')
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                              onsubmit="return confirm('Cancel this booking?')">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger">Cancel</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">No upcoming bookings.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
