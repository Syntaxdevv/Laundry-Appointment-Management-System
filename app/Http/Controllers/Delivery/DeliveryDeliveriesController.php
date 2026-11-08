<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliveryDeliveriesController extends Controller
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
            ->whereIn('delivery_status', [
                'pending',
                'out_for_delivery'
            ])
            ->latest()
            ->paginate(10);

        return view('delivery.deliveries.index', compact('deliveries'));
    }

    public function show($id)
    {
        $delivery = $this->assignedBookings()
            ->where('id', $id)
            ->firstOrFail();

        return view('delivery.deliveries.show', compact('delivery'));
    }

    public function updateStatus(Request $request, $id)
    {
        $delivery = $this->assignedBookings()
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'delivery_status' => [
                'required',
                Rule::in([
                    'out_for_delivery',
                    'delivered'
                ]),
            ],
        ]);

        $transitions = [
            'pending' => 'out_for_delivery',
            'out_for_delivery' => 'delivered',
        ];

        $expectedStatus = $transitions[$delivery->delivery_status] ?? null;

        if ($expectedStatus !== $validated['delivery_status']) {
            return back()->withErrors([
                'delivery_status' => 'Invalid delivery status transition.'
            ]);
        }

        $delivery->delivery_status = $validated['delivery_status'];
        $delivery->save();

        return back()->with(
            'success',
            'Delivery status updated successfully.'
        );
    }
}
