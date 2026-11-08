<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class StaffOrdersController extends Controller
{
    /**
     * Display orders assigned to the logged-in staff.
     */
    public function index(Request $request)
    {
        abort_unless(
            $request->user()?->role === 'staff',
            403,
            'Staff access only.'
        );

        $staffId = $request->user()->id;

        $query = Booking::with([
            'service',
            'user',
            'assignedStaff',
        ])->where('assigned_staff_id', $staffId);

        // SEARCH: order number, customer name, or service.
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // STATUS FILTER.
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
// SOURCE FILTER.
if ($request->source === 'walk-in') {
    $query->where('booking_source', 'walk_in');
} elseif ($request->source === 'online') {
    $query->where('booking_source', 'online');
}

        // DATE FILTER.
        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        $orders = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('staff.orders.index', compact('orders'));
    }

    /**
     * Display one assigned order.
     */
    public function show(Request $request, Booking $booking)
    {
        abort_unless(
            $request->user()?->role === 'staff',
            403,
            'Staff access only.'
        );

        // Only allow the assigned staff to view the order.
        abort_unless(
            (int) $booking->assigned_staff_id ===
            (int) $request->user()->id,
            403,
            'This order is not assigned to you.'
        );

        $booking->load([
            'service',
            'user',
            'assignedStaff',
        ]);

        return view('staff.orders.show', compact('booking'));
    }
}
