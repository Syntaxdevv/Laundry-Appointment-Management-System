<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with('service')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $activeBooking = $bookings->first(
            fn ($booking) => in_array($booking->status, [
                'pending',
                'washing',
                'drying',
                'ready',
            ])
        );

        $totalBookings = $bookings->count();

        $inProgressCount = $bookings->whereIn('status', [
            'pending',
            'washing',
            'drying',
        ])->count();

        $completedCount = $bookings
            ->where('status', 'completed')
            ->count();

        $cancelledCount = $bookings
            ->where('status', 'cancelled')
            ->count();

        $recentBookings = $bookings->take(5);
        $services = Service::all();

        return view('dashboard', compact(
            'user',
            'bookings',
            'activeBooking',
            'totalBookings',
            'inProgressCount',
            'completedCount',
            'cancelledCount',
            'recentBookings',
            'services'
        ));
    }
}
