<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $allBookings = Booking::where('user_id', $user->id)
            ->with('service')
            ->latest()
            ->get();

        $totalBookings = $allBookings->count();

        $activeStatuses = [
            'pending',
            'washing',
            'drying',
            'processing',
            'ready',
        ];

        $inProgressCount = $allBookings
            ->filter(function ($booking) use ($activeStatuses) {
                return in_array(
                    strtolower($booking->status ?? ''),
                    $activeStatuses,
                    true
                );
            })
            ->count();

        $completedCount = $allBookings
            ->filter(function ($booking) {
                return strtolower($booking->status ?? '') === 'completed';
            })
            ->count();

        $cancelledCount = $allBookings
            ->filter(function ($booking) {
                return strtolower($booking->status ?? '') === 'cancelled';
            })
            ->count();

        $activeBooking = $allBookings->first(
            function ($booking) use ($activeStatuses) {
                return in_array(
                    strtolower($booking->status ?? ''),
                    $activeStatuses,
                    true
                );
            }
        );

        $recentBookings = $allBookings->take(5);

        $services = Service::orderBy('id')
            ->limit(4)
            ->get();

        return view('customer.dashboard', compact(
            'user',
            'totalBookings',
            'inProgressCount',
            'completedCount',
            'cancelledCount',
            'recentBookings',
            'activeBooking',
            'services'
        ));
    }

    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('service')
            ->latest()
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $services = Service::all();

        return view('customer.bookings.create', compact('services'));
    }

    /**
     * Create a booking and start PayPal checkout
     * when the customer selects online payment.
     */
    public function store(Request $request, PayPalService $paypal)
    {
        $validated = $request->validate([
            'customer_name' => [
                'nullable',
                'string',
                'max:120',
            ],

            'service_id' => [
                'required',
                'exists:services,id',
            ],

            'weight_kg' => [
                'required',
                'numeric',
                'min:1',
            ],

            'service_option' => [
                'required',
                'in:drop_off,pickup_delivery',
            ],

            'pickup_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'pickup_time' => [
                'required',
                'date_format:H:i',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'payment_method' => [
                'required',
                'in:online,in_person,demo',
            ],

            'payment_provider' => [
                'nullable',
                'required_if:payment_method,online',
                'in:paypal',
            ],

            // Pickup and delivery details
            'phone' => [
                'required_if:service_option,pickup_delivery',
                'nullable',
                'string',
                'max:30',
            ],

            'pickup_address' => [
                'required_if:service_option,pickup_delivery',
                'nullable',
                'string',
                'max:1000',
            ],

            'delivery_address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            // Pickup GPS coordinates
            'pickup_latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'pickup_longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            // Delivery GPS coordinates
            'delivery_latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'delivery_longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $pricePerKg = (float) (
            $service->price_per_kg ?? $service->price ?? 0
        );

        $totalPrice = round(
            $pricePerKg * (float) $validated['weight_kg'],
            2
        );

        if ($totalPrice <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid booking amount.',
            ], 422);
        }

        $isOnline = $validated['payment_method'] === 'online';
        $isDemo = $validated['payment_method'] === 'demo';

        $isPickupDelivery =
            $validated['service_option'] === 'pickup_delivery';

        if ($isDemo && !(
            app()->environment('local') &&
            config('services.demo_payments_enabled')
        )) {
            abort(404);
        }

        // If delivery address is blank, use pickup address.
        $pickupAddress = $isPickupDelivery
            ? ($validated['pickup_address'] ?? null)
            : null;

        $deliveryAddress = $isPickupDelivery
            ? (
                !empty($validated['delivery_address'])
                    ? $validated['delivery_address']
                    : $pickupAddress
            )
            : null;

        // If delivery is at the same address, reuse pickup coordinates.
        $deliveryLatitude = $isPickupDelivery
            ? (
                !empty($validated['delivery_address'])
                    ? ($validated['delivery_latitude'] ?? null)
                    : ($validated['pickup_latitude'] ?? null)
            )
            : null;

        $deliveryLongitude = $isPickupDelivery
            ? (
                !empty($validated['delivery_address'])
                    ? ($validated['delivery_longitude'] ?? null)
                    : ($validated['pickup_longitude'] ?? null)
            )
            : null;

        $data = [
            'user_id' => Auth::id(),

            'customer_name' => $validated['customer_name']
                ?: Auth::user()->name,

            'service_id' => $service->id,
            'weight_kg' => $validated['weight_kg'],
            'total_price' => $totalPrice,

            'service_option' => $validated['service_option'],
            'pickup_date' => $validated['pickup_date'],
            'pickup_time' => $validated['pickup_time'],

            'notes' => $validated['notes'] ?? null,

            'phone' => $isPickupDelivery
                ? ($validated['phone'] ?? null)
                : null,

            'pickup_address' => $pickupAddress,
            'delivery_address' => $deliveryAddress,

            'pickup_latitude' => $isPickupDelivery
                ? ($validated['pickup_latitude'] ?? null)
                : null,

            'pickup_longitude' => $isPickupDelivery
                ? ($validated['pickup_longitude'] ?? null)
                : null,

            'delivery_latitude' => $deliveryLatitude,
            'delivery_longitude' => $deliveryLongitude,

            'status' => 'pending',

            'payment_method' => $isDemo
                ? 'demo'
                : ($isOnline ? 'online' : 'in_person'),

            'payment_provider' => $isDemo
                ? 'offline_demo'
                : ($isOnline ? 'paypal' : null),

            'payment_status' => 'pending',
        ];

        // Save only fields that exist in the current bookings table.
        $data = array_filter(
            $data,
            fn ($value, $column) =>
                Schema::hasColumn('bookings', $column),
            ARRAY_FILTER_USE_BOTH
        );

        $booking = new Booking();
        $booking->forceFill($data);
        $booking->save();

        if ($isOnline) {
            try {
                $approvalUrl = $paypal->createOrder($booking);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Booking created. Continue to PayPal.',
                    'redirect' => $approvalUrl,
                ]);
            } catch (\Throwable $e) {
                Log::error('PayPal checkout creation failed.', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Your booking was saved, but PayPal checkout could not be created. Please try again later.',
                    'redirect' => route(
                        'customer.bookings.show',
                        $booking->id
                    ),
                ], 422);
            }
        }

        if ($isDemo) {
            return response()->json([
                'status' => 'success',
                'message' => 'Booking created. Continue to demo payment.',
                'redirect' => route(
                    'customer.demo-payment.show',
                    $booking->id
                ),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your booking has been submitted successfully.',
            'redirect' => route(
                'customer.bookings.show',
                $booking->id
            ),
        ]);
    }

    /**
     * PayPal redirects the customer here after approval.
     * Verify and capture the actual PayPal order before marking paid.
     */
    public function paypalSuccess(
        Request $request,
        $id,
        PayPalService $paypal
    ) {
        $booking = Booking::where('user_id', Auth::id())
            ->with('service')
            ->findOrFail($id);

        $orderId = $request->query('token');

        if (
            !$orderId ||
            !$booking->paypal_order_id ||
            !hash_equals(
                (string) $booking->paypal_order_id,
                (string) $orderId
            )
        ) {
            return redirect()
                ->route('customer.bookings.show', $booking->id)
                ->with('error', 'PayPal order could not be verified.');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route(
                'customer.paypal.success-page',
                $booking->id
            );
        }

        try {
            $capture = $paypal->captureOrder($orderId);

            $purchaseUnit = $capture['purchase_units'][0] ?? [];
            $captureDetails = $purchaseUnit['payments']['captures'][0] ?? [];
            $amount = $captureDetails['amount'] ?? [];

            $expectedAmount = number_format(
                (float) $booking->total_price,
                2,
                '.',
                ''
            );

            $capturedAmount = (string) ($amount['value'] ?? '');
            $currency = $amount['currency_code'] ?? '';
            $referenceId = $purchaseUnit['reference_id'] ?? '';

            $validPayment =
                ($capture['status'] ?? '') === 'COMPLETED' &&
                ($captureDetails['status'] ?? '') === 'COMPLETED' &&
                $currency === 'PHP' &&
                $capturedAmount === $expectedAmount &&
                $referenceId === 'BOOKING-' . $booking->id;

            if (!$validPayment) {
                Log::warning('PayPal payment verification mismatch.', [
                    'booking_id' => $booking->id,
                    'order_id' => $orderId,
                ]);

                return redirect()
                    ->route('customer.bookings.show', $booking->id)
                    ->with(
                        'error',
                        'Payment could not be verified. Please contact the shop.'
                    );
            }

            $booking->forceFill([
                'payment_status' => 'paid',
                'paypal_capture_id' => $captureDetails['id'] ?? null,
                'paid_at' => now(),
            ])->save();

            return redirect()->route(
                'customer.paypal.success-page',
                $booking->id
            );

        } catch (\Throwable $e) {
            Log::error('PayPal payment capture failed.', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('customer.bookings.show', $booking->id)
                ->with(
                    'error',
                    'Unable to verify your PayPal payment. Please contact the shop if you were charged.'
                );
        }
    }

    public function paypalCancel($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->findOrFail($id);

        return redirect()
            ->route('customer.bookings.show', $booking->id)
            ->with(
                'info',
                'PayPal checkout was cancelled. Your booking is still pending payment.'
            );
    }

    public function paymentSuccess($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with('service')
            ->findOrFail($id);

        if (
            $booking->payment_method !== 'online' ||
            $booking->payment_provider !== 'paypal' ||
            $booking->payment_status !== 'paid'
        ) {
            return redirect()
                ->route('customer.bookings.show', $booking->id)
                ->with('error', 'Payment has not been verified.');
        }

        return view('customer.bookings.success', compact('booking'));
    }

    public function show($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with('service')
            ->findOrFail($id);

        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $booking->update([
            'status' => 'cancelled',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your booking has been cancelled.',
        ]);
    }

    public function demoPayment($id)
    {
        abort_unless(
            app()->environment('local') &&
            config('services.demo_payments_enabled'),
            404
        );

        $booking = Booking::where('user_id', Auth::id())
            ->with('service')
            ->findOrFail($id);

        abort_unless(
            $booking->payment_method === 'demo' &&
            $booking->payment_provider === 'offline_demo',
            404
        );

        if ($booking->payment_status !== 'pending') {
            return redirect()
                ->route('customer.bookings.show', $booking->id)
                ->with(
                    'info',
                    'This demo payment has already been processed.'
                );
        }

        return view('customer.bookings.demo-payment', compact('booking'));
    }

    public function simulateDemoPayment(Request $request, $id)
    {
        abort_unless(
            app()->environment('local') &&
            config('services.demo_payments_enabled'),
            404
        );

        $validated = $request->validate([
            'result' => ['required', 'in:success,failed'],
        ]);

        $booking = Booking::where('user_id', Auth::id())
            ->findOrFail($id);

        abort_unless(
            $booking->payment_method === 'demo' &&
            $booking->payment_provider === 'offline_demo',
            404
        );

        if ($booking->payment_status !== 'pending') {
            return redirect()
                ->route('customer.bookings.show', $booking->id)
                ->with(
                    'info',
                    'This demo payment has already been processed.'
                );
        }

        $booking->update([
            'payment_status' => $validated['result'] === 'success'
                ? 'paid'
                : 'failed',
        ]);

        return redirect()
            ->route('customer.bookings.show', $booking->id)
            ->with(
                $validated['result'] === 'success' ? 'success' : 'error',
                $validated['result'] === 'success'
                    ? 'DEMO ONLY: Payment simulation successful. No real money was received.'
                    : 'DEMO ONLY: Payment simulation failed. No real payment was made.'
            );
    }
}
