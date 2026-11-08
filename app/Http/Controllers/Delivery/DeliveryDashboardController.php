<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DeliveryDashboardController extends Controller
{
    private function assignedBookings()
    {
        $user = auth()->user();

        abort_unless($user && $user->role === 'delivery', 403);

        return Booking::with(['user', 'service'])
            ->where('assigned_delivery_id', $user->id)
            ->where('service_option', 'pickup_delivery')
            ->where('status', 'completed');
    }

    public function index()
    {
        $deliveries = $this->assignedBookings()
            ->latest()
            ->get();

        $stats = [
            'total' => $deliveries->count(),
            'pending' => $deliveries
                ->where('delivery_status', 'pending')
                ->count(),
            'out_for_delivery' => $deliveries
                ->where('delivery_status', 'out_for_delivery')
                ->count(),
            'delivered' => $deliveries
                ->where('delivery_status', 'delivered')
                ->count(),
        ];

        return view('delivery.dashboard', compact(
            'deliveries',
            'stats'
        ));
    }

    public function tracking()
    {
        $deliveries = $this->assignedBookings()
            ->whereIn('delivery_status', [
                'pending',
                'out_for_delivery'
            ])
            ->latest()
            ->get();

        return view('delivery.tracking.index', compact('deliveries'));
    }

    public function history()
    {
        $deliveries = $this->assignedBookings()
            ->where('delivery_status', 'delivered')
            ->latest()
            ->paginate(10);

        return view('delivery.history.index', compact('deliveries'));
    }
}
