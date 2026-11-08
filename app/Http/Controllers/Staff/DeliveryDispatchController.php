<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DeliveryDispatchController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->role === 'staff', 403);

        $query = Booking::with(['user', 'service', 'assignedDelivery'])
            ->where('service_option', 'pickup_delivery')
            ->where('status', 'completed');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filter === 'unassigned') {
            $query->whereNull('assigned_delivery_id');
        } elseif ($request->filter === 'assigned') {
            $query->whereNotNull('assigned_delivery_id');
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        $deliveryUsers = User::where('role', 'delivery')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $statsQuery = Booking::where('service_option', 'pickup_delivery')
            ->where('status', 'completed');

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'assigned' => (clone $statsQuery)
                ->whereNotNull('assigned_delivery_id')->count(),
            'unassigned' => (clone $statsQuery)
                ->whereNull('assigned_delivery_id')->count(),
        ];

        return view('staff.delivery-dispatch.index', compact(
            'bookings',
            'deliveryUsers',
            'stats'
        ));
    }

    public function assign(Request $request, $booking)
    {
        abort_unless(auth()->user()->role === 'staff', 403);

        $validated = $request->validate([
            'assigned_delivery_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('role', 'delivery')
                ),
            ],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $record = Booking::whereKey($booking)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $record->service_option === 'pickup_delivery' &&
                $record->status === 'completed',
                422,
                'Only completed pickup and delivery bookings can be assigned.'
            );

            abort_if(
                $record->delivery_status === 'delivered',
                422,
                'This booking has already been delivered.'
            );

            $record->assigned_delivery_id =
                $validated['assigned_delivery_id'];

            $record->delivery_status = 'pending';

            $record->save();
        });

        return redirect()
            ->route('staff.delivery-dispatch.index')
            ->with('success', 'Delivery personnel assigned successfully!');
    }
}
