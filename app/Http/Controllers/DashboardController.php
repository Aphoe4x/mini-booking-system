<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __invoke()
    {
        Log::info('DashboardController@__invoke called', ['path' => request()->path(), 'url' => request()->url()]);
        $user = Auth::user();

        $query = $user->isAdmin() ? Booking::query() : Booking::forUser($user->id);

        $totalBookings = $query->count();
        $pendingCount = $query->pending()->count();
        $confirmedCount = $query->where('status', 'confirmed')->count();
        $cancelledCount = $query->where('status', 'cancelled')->count();

        $recentBookings = $query->with('user')
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalBookings',
            'pendingCount',
            'confirmedCount',
            'cancelledCount',
            'recentBookings'
        ));
    }
}
