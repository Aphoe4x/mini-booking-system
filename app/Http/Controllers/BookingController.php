<?php

namespace App\Http\Controllers;

use App\Mail\BookingStatusChanged;
use App\Models\Booking;
use App\Models\Company;
use App\Models\CompanyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // Business hours configuration
    const BUSINESS_HOURS_START = 9;   // 9:00 AM

    const BUSINESS_HOURS_END = 18;    // 6:00 PM

    const BUSINESS_DAYS = [1, 2, 3, 4, 5]; // Monday through Friday

    // List the logged-in user's own bookings
    public function index()
    {
        Log::info('BookingController@index called', ['path' => request()->path(), 'url' => request()->url()]);
        $userId = Auth::id();

        $upcoming = Booking::forUser($userId)->upcoming()->get();
        $past = Booking::forUser($userId)->past()->get();

        return view('bookings.index', compact('upcoming', 'past'));
    }

    public function create(Request $request)
    {
        $companies = Company::active()
            ->whereHas('companyServices', fn ($query) => $query->where('is_available', true))
            ->with(['services' => fn ($query) => $query->wherePivot('is_available', true)])
            ->orderBy('name')
            ->get();

        $preselectedCompany = $request->integer('company') ?: null;
        $preselectedService = $request->integer('service') ?: null;

        return view('bookings.create', compact('companies', 'preselectedCompany', 'preselectedService'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_service_id' => 'required|exists:company_services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Never trust a price posted by the browser — look it up from the database.
        $companyService = CompanyService::with(['service', 'company'])
            ->where('is_available', true)
            ->findOrFail($validated['company_service_id']);

        abort_unless($companyService->company?->is_active, 404);

        $serviceName = "{$companyService->service->name} at {$companyService->company->name}";
        $bookingTime = Carbon::createFromFormat('H:i', $validated['booking_time'])->format('H:i:s');

        // Business hours validation
        $bookingDateTime = Carbon::parse($validated['booking_date'].' '.$validated['booking_time']);
        $dayOfWeek = (int) $bookingDateTime->format('N'); // 1=Monday, 7=Sunday
        $hour = (int) $bookingDateTime->format('H');

        if (! in_array($dayOfWeek, self::BUSINESS_DAYS)) {
            return back()
                ->withInput()
                ->withErrors(['booking_date' => 'Bookings are only available Monday through Friday.']);
        }

        if ($hour < self::BUSINESS_HOURS_START || $hour >= self::BUSINESS_HOURS_END) {
            return back()
                ->withInput()
                ->withErrors(['booking_time' => 'Bookings are only available between 9:00 AM and 6:00 PM.']);
        }

        // Guard against double-booking the same slot
        $slotTaken = Booking::where('company_service_id', $validated['company_service_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('booking_time', $bookingTime)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($slotTaken) {
            return back()
                ->withInput()
                ->withErrors(['booking_time' => 'That slot is already booked. Please choose another time.']);
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'company_service_id' => $companyService->id,
            'service_name' => $serviceName,
            'price_paid' => $companyService->price,
            'booking_date' => $validated['booking_date'],
            'booking_time' => $bookingTime,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        // Send confirmation email to client
        try {
            Mail::to(Auth::user()->email)->send(new BookingStatusChanged($booking, 'pending'));
        } catch (\Exception $e) {
            // Log but don't fail the booking
        }

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking request submitted. You will be notified once it is confirmed.');
    }

    // A user can cancel their own booking, as long as it's not already cancelled
    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        $previousStatus = $booking->status;

        if ($booking->status !== 'cancelled') {
            $booking->update(['status' => 'cancelled']);

            // Send cancellation email to client
            try {
                Mail::to($booking->user->email)->send(new BookingStatusChanged($booking, $previousStatus));
            } catch (\Exception $e) {
                // Log but don't fail
            }
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

        $previousStatus = $booking->status;

        $booking->update($validated);

        // Send status change email to client
        try {
            Mail::to($booking->user->email)->send(new BookingStatusChanged($booking, $previousStatus));
        } catch (\Exception $e) {
            // Log but don't fail
        }

        return back()->with('success', "Booking #{$booking->id} marked as {$validated['status']}.");
    }
}
