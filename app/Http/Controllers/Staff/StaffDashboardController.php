<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            $request->user() && $request->user()->role === 'staff',
            403,
            'Staff access only.'
        );

        $statusCounts = [
            'pending' => Booking::where('status', 'pending')->count(),
            'washing' => Booking::where('status', 'washing')->count(),
            'drying' => Booking::where('status', 'drying')->count(),
            'ready' => Booking::where('status', 'ready')->count(),
        ];

        $recentOrders = Booking::with(['service', 'user'])
            ->latest()
            ->take(8)
            ->get();

        return view('staff.dashboard', compact(
            'statusCounts',
            'recentOrders'
        ));
    }
}
