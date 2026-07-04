<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // List the logged-in user's own bookings
    public function index()
    {
        $bookings = Booking::forUser(Auth::id())->upcoming()->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('bookings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Guard against double-booking the same slot (belt and braces —
        // the DB unique constraint is the real safety net)
        $slotTaken = Booking::where('service_name', $validated['service_name'])
            ->where('booking_date', $validated['booking_date'])
            ->where('booking_time', $validated['booking_time'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($slotTaken) {
            return back()
                ->withInput()
                ->withErrors(['booking_time' => 'That slot is already booked. Please choose another time.']);
        }

        Booking::create([
            'user_id' => Auth::id(),
            ...$validated,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking request submitted. You will be notified once it is confirmed.');
    }

    // A user can cancel their own booking, as long as it's not already cancelled
    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        if ($booking->status !== 'cancelled') {
            $booking->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Booking cancelled.');
    }

    // --- Admin-only actions below ---

    public function adminIndex()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $bookings = Booking::with('user')->orderBy('booking_date')->orderBy('booking_time')->get();

        return view('admin.bookings', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'cancelled'])],
        ]);

        $booking->update($validated);

        return back()->with('success', "Booking #{$booking->id} marked as {$validated['status']}.");
    }
}
