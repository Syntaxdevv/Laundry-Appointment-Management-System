<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DeliveryHistoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        abort_unless($user && $user->role === 'delivery', 403);

        $deliveries = Booking::with(['user', 'service'])
            ->where('assigned_delivery_id', $user->id)
            ->where('service_option', 'pickup_delivery')
            ->where('status', 'completed')
            ->where('delivery_status', 'delivered')
            ->latest()
            ->paginate(10);

        return view('delivery.history.index', compact('deliveries'));
    }
}
