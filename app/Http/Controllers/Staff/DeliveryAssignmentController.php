<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DeliveryAssignmentController extends Controller
{
    public function index()
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'staff',
            403
        );

        $query = Booking::with([
            'user',
            'service',
            'assignedDelivery',
        ])
            ->where('service_option', 'pickup_delivery')
            ->where('status', 'completed')
            ->latest();

        if ($search = trim((string) request('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if (request('filter') === 'unassigned') {
            $query->whereNull('assigned_delivery_id');
        } elseif (request('filter') === 'assigned') {
            $query->whereNotNull('assigned_delivery_id');
        }

        $bookings = $query->paginate(10)->withQueryString();

        $deliveryUsers = User::where('role', 'delivery')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $completed = Booking::where('service_option', 'pickup_delivery')
            ->where('status', 'completed');

        $stats = [
            'total' => (clone $completed)->count(),
            'assigned' => (clone $completed)
                ->whereNotNull('assigned_delivery_id')->count(),
            'unassigned' => (clone $completed)
                ->whereNull('assigned_delivery_id')->count(),
        ];

        return view('staff.delivery-assignments.index', compact(
            'bookings',
            'deliveryUsers',
            'stats'
        ));
    }

    public function assign(Request $request, $bookingId)
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'staff',
            403
        );

        $validated = $request->validate([
            'assigned_delivery_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('role', 'delivery')
                ),
            ],
        ]);

        $booking = DB::transaction(function () use ($bookingId, $validated) {

            $booking = Booking::whereKey($bookingId)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $booking->service_option === 'pickup_delivery' &&
                $booking->status === 'completed',
                422,
                'Only completed pickup and delivery bookings can be assigned.'
            );

            abort_if(
                $booking->delivery_status === 'delivered',
                422,
                'This booking has already been delivered.'
            );

            $deliveryBoy = User::where('role', 'delivery')
                ->findOrFail($validated['assigned_delivery_id']);

            $booking->assigned_delivery_id = $deliveryBoy->id;
            $booking->delivery_status = 'pending';
            $booking->save();

            return $booking->fresh();
        });

        return redirect()
            ->route('staff.delivery-assignments.index')
            ->with(
                'success',
                'Booking #' . $booking->id .
                ' successfully assigned to the selected Delivery Boy.'
            );
    }
}
