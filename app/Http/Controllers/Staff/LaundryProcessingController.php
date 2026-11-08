<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaundryProcessingController extends Controller
{
    private array $transitions = [
        'pending' => 'processing',
        'processing' => 'washing',
        'washing' => 'drying',
        'drying' => 'ready',
        'ready' => 'completed',
        'completed' => null,
        'cancelled' => null,
    ];

    /**
     * Display laundry orders assigned to the logged-in staff.
     */
    public function index(Request $request)
    {
        abort_unless(
            $request->user()?->role === 'staff',
            403,
            'Staff access only.'
        );

        $staffId = $request->user()->id;

        $orders = Booking::with([
            'user',
            'service',
            'assignedStaff',
        ])
            ->where('assigned_staff_id', $staffId)
            ->whereNotIn('status', ['cancelled'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statusCounts = Booking::where('assigned_staff_id', $staffId)
            ->whereNotIn('status', ['cancelled'])
            ->selectRaw('LOWER(status) as booking_status, COUNT(*) as total')
            ->groupBy('booking_status')
            ->pluck('total', 'booking_status');

        $counts = [
            'pending' => (int) ($statusCounts['pending'] ?? 0),
            'processing' => (int) ($statusCounts['processing'] ?? 0),
            'washing' => (int) ($statusCounts['washing'] ?? 0),
            'drying' => (int) ($statusCounts['drying'] ?? 0),
            'ready' => (int) ($statusCounts['ready'] ?? 0),
            'completed' => (int) ($statusCounts['completed'] ?? 0),
        ];

        return view(
            'staff.laundry-processing.index',
            compact('orders', 'counts')
        );
    }

    /**
     * Update laundry processing status.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        abort_unless(
            $request->user()?->role === 'staff',
            403,
            'Staff access only.'
        );

        abort_unless(
            (int) $booking->assigned_staff_id ===
            (int) $request->user()->id,
            403,
            'This order is not assigned to you.'
        );

        $request->validate([
            'status' => [
                'required',
                'in:processing,washing,drying,ready,completed',
            ],
        ]);

        $result = DB::transaction(function () use ($booking, $request) {
            $order = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Recheck staff assignment.
            if (
                (int) $order->assigned_staff_id !==
                (int) $request->user()->id
            ) {
                abort(403, 'This order is not assigned to you.');
            }

            $currentStatus = strtolower($order->status ?? 'pending');

            $nextStatus = $this->transitions[$currentStatus] ?? null;

            if (!$nextStatus || $request->status !== $nextStatus) {
                return false;
            }

            $order->status = $nextStatus;

            // DELIVERY HANDOFF.
            if ($nextStatus === 'completed') {
                if ($order->service_option === 'pickup_delivery') {
                    $order->delivery_status = 'pending';
                } elseif ($order->service_option === 'drop_off') {
                    $order->delivery_status = 'not_required';
                }
            }

            $order->save();

            return [
                'status' => $nextStatus,
                'service_option' => $order->service_option,
            ];
        });

        if (!$result) {
            return back()->with(
                'error',
                'Invalid status update for this order.'
            );
        }

        $message = 'Order #' . $booking->id .
            ' status updated to ' . ucfirst($result['status']) . '.';

        if (
            $result['status'] === 'completed' &&
            $result['service_option'] === 'pickup_delivery'
        ) {
            $message .=
                ' This booking is now ready for delivery assignment.';
        }

        return back()->with('success', $message);
    }
}
