<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class WalkInController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->get();

        $walkIns = Booking::with(['service', 'user', 'assignedStaff'])
            ->where('booking_source', 'walk_in')
            ->latest()
            ->paginate(10);

        return view('staff.walk-ins.index', compact(
            'services',
            'walkIns'
        ));
    }

    public function store(Request $request)
    {
        abort_unless(
            $request->user()?->role === 'staff',
            403,
            'Staff access only.'
        );

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'service_id' => ['required', 'exists:services,id'],
            'weight_kg' => ['required', 'numeric', 'min:0.1'],
            'service_option' => [
                'required',
                'in:drop_off,pickup_delivery',
            ],
            'delivery_address' => [
                'required_if:service_option,pickup_delivery',
                'nullable',
                'string',
                'max:2000',
            ],
            'instructions' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer = User::where('email', $validated['email'])->first();

        if (!$customer) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Walang registered account para sa email na ito.',
                ]);
        }

        $service = Service::findOrFail($validated['service_id']);

        $weight = (float) $validated['weight_kg'];

        $pricePerKg = (float) (
            $service->price_per_kg ?? $service->price ?? 0
        );

        $isDelivery = $validated['service_option'] === 'pickup_delivery';

        $data = [
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'customer_name' => $customer->name,
            'phone' => $validated['phone'],
            'weight_kg' => $weight,
            'total_price' => round($weight * $pricePerKg, 2),

            'booking_source' => 'walk_in',
            'service_option' => $validated['service_option'],

            'pickup_date' => now()->toDateString(),
            'pickup_time' => now()->format('H:i:s'),

            'delivery_address' => $isDelivery
                ? $validated['delivery_address']
                : null,

            'notes' => $validated['instructions'] ?? null,
            'status' => 'pending',

            'delivery_status' => $isDelivery
                ? 'not_ready'
                : 'not_required',

            'assigned_staff_id' => $request->user()->id,
        ];

        $data = array_filter(
            $data,
            fn ($value, $column) =>
                Schema::hasColumn('bookings', $column),
            ARRAY_FILTER_USE_BOTH
        );

        $booking = new Booking();
        $booking->forceFill($data);
        $booking->save();

        return redirect()
            ->route('staff.orders.index')
            ->with(
                'success',
                'Walk-in order #' . $booking->id .
                ' created successfully and assigned to you.'
            );
    }
}
