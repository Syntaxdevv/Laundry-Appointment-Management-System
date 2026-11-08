<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PickupDeliveryController extends Controller
{
    private array $statuses = [
        'pending',
        'out_for_delivery',
        'delivered',
    ];

    /**
     * Display bookings that are ready for pickup and delivery.
     */
    public function index(Request $request)
    {
        $statuses = $this->statuses;

        $query = Booking::query()
            ->where('service_option', 'pickup_delivery')
            ->where('status', 'ready')
            ->whereIn('delivery_status', $this->statuses)
            ->with(['user', 'service']);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('pickup_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Delivery status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('delivery_status', $request->status);
        }

        // Service filter
        if ($request->filled('service_type') && $request->service_type !== 'all') {
            $query->whereHas('service', function ($serviceQuery) use ($request) {
                $serviceQuery->where('name', $request->service_type);
            });
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('pickup_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('pickup_date', '<=', $request->date_to);
        }

        $requests = $query
            ->orderByRaw('pickup_date IS NULL')
            ->orderBy('pickup_date')
            ->orderBy('pickup_time')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $requests->getCollection()->transform(
            fn (Booking $booking) => $this->prepareBooking($booking)
        );

        return view('staff.pickup-delivery.index', compact(
            'requests',
            'statuses'
        ));
    }

    /**
     * Show delivery booking details.
     */
    public function show(Booking $pickupDelivery)
    {
        abort_unless(
            $pickupDelivery->service_option === 'pickup_delivery'
            && $pickupDelivery->status === 'ready',
            404
        );

        $pickupDelivery->load(['user', 'service']);

        $pickup = $this->prepareBooking($pickupDelivery);

        return view('staff.pickup-delivery.show', compact('pickup'));
    }

    /**
     * Update delivery progress only.
     */
    public function updateStatus(Request $request, Booking $pickupDelivery)
    {
        abort_unless(
            $pickupDelivery->service_option === 'pickup_delivery'
            && $pickupDelivery->status === 'ready',
            404
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in($this->statuses),
            ],
        ]);

        $current = strtolower(
            $pickupDelivery->delivery_status ?? 'pending'
        );

        $next = $validated['status'];

        $allowedTransitions = [
            'pending' => ['out_for_delivery'],
            'out_for_delivery' => ['delivered'],
            'delivered' => [],
        ];

        if ($next !== $current &&
            ! in_array($next, $allowedTransitions[$current] ?? [], true)
        ) {
            return back()->withErrors([
                'status' => 'Invalid delivery status transition from '
                    . str_replace('_', ' ', $current)
                    . ' to '
                    . str_replace('_', ' ', $next)
                    . '.',
            ]);
        }

        if ($next === $current) {
            return back()->with(
                'success',
                'Delivery status is already '
                . str_replace('_', ' ', ucfirst($next))
                . '.'
            );
        }

        // IMPORTANT:
        // Only delivery_status is updated.
        // The laundry processing status remains READY.
        $pickupDelivery->update([
            'delivery_status' => $next,
        ]);

        return back()->with(
            'success',
            'Delivery status updated to '
            . str_replace('_', ' ', ucfirst($next))
            . ' successfully.'
        );
    }

    /**
     * Prepare booking information for the views.
     */
    private function prepareBooking(Booking $booking): Booking
    {
        $dateTime = null;

        if ($booking->pickup_date) {
            $dateTime = Carbon::parse(
                $booking->pickup_date
                . ' '
                . ($booking->pickup_time ?: '00:00:00')
            );
        }

        $booking->setAttribute(
            'request_number',
            'LL-' . str_pad(
                (string) $booking->id,
                6,
                '0',
                STR_PAD_LEFT
            )
        );

        $booking->setAttribute(
            'email',
            $booking->user?->email
        );

        $booking->setAttribute(
            'service_type',
            $booking->service?->name ?? 'Laundry Service'
        );

        $booking->setAttribute(
            'scheduled_at',
            $dateTime
        );

        $booking->setAttribute(
            'instructions',
            $booking->notes
        );

        $booking->setAttribute(
            'request_type',
            'pickup'
        );

        return $booking;
    }
}
