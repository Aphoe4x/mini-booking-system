@extends('layouts.app')

@section('content')
<h2>All Bookings (Admin)</h2>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Client</th>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
            <tr>
                <td>{{ $booking->user->name }}</td>
                <td>{{ $booking->service_name }}</td>
                <td>{{ $booking->booking_date->format('M j Y') }}</td>
                <td>{{ $booking->booking_time->format('g:i A') }}</td>
                <td>
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'secondary' : 'warning') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>
                    @if ($booking->status === 'pending')
                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button class="btn btn-sm btn-success">Confirm</button>
                        </form>
                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
