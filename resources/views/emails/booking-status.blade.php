@component('mail::message')
# {{ $booking->service_name }}

@switch($booking->status)
    @case('confirmed')
        Your booking has been confirmed!
    @break
    @case('cancelled')
        Your booking has been cancelled.
    @break
    @default
        We have received your booking request. It is currently pending approval.
@endswitch

<table>
    <tr>
        <td style="padding: 10px 0;"><strong>Service:</strong></td>
        <td style="padding: 10px 0;">{{ $booking->service_name }}</td>
    </tr>
    <tr>
        <td style="padding: 10px 0;"><strong>Date:</strong></td>
        <td style="padding: 10px 0;">{{ $booking->booking_date->format('l, F j, Y') }}</td>
    </tr>
    <tr>
        <td style="padding: 10px 0;"><strong>Time:</strong></td>
        <td style="padding: 10px 0;">{{ $booking->booking_time->format('g:i A') }}</td>
    </tr>
    <tr>
        <td style="padding: 10px 0;"><strong>Status:</strong></td>
        <td style="padding: 10px 0;">
            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: 600;
                @if($booking->status === 'confirmed') background: #dcfce7; color: #166534;
                @elseif($booking->status === 'cancelled') background: #fee2e2; color: #991b1b;
                @else background: #fef9c3; color: #713f12; @endif">
                {{ ucfirst($booking->status) }}
            </span>
        </td>
    </tr>
    @if ($booking->notes)
    <tr>
        <td style="padding: 10px 0;"><strong>Notes:</strong></td>
        <td style="padding: 10px 0;">{{ $booking->notes }}</td>
    </tr>
    @endif
</table>

@if ($booking->status === 'confirmed')
@component('mail::button', ['url' => route('bookings.index')])
View My Bookings
@endcomponent
@endif

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
