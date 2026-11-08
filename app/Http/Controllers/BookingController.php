<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /** My Orders page */
    public function index(Request $request)
    {
        $bookings = Booking::with('service')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }

    /** Booking form */
    public function create()
    {
        $services = Service::orderBy('name')->get();

        return view('customer.bookings.create', compact('services'));
    }

    /** Save booking */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => ['required', 'string', 'max:120'],
            'service_id'     => ['required', 'exists:services,id'],
            'weight_kg'      => ['required', 'numeric', 'min:1'],
            'service_option' => ['required', 'in:drop_off,pickup_delivery'],
            'pickup_date'    => ['required', 'date', 'after_or_equal:today'],
            'pickup_time'    => ['required', 'date_format:H:i'],
            'phone'          => ['required_if:service_option,pickup_delivery', 'nullable', 'string', 'max:30'],
            'pickup_address' => ['required_if:service_option,pickup_delivery', 'nullable', 'string', 'max:1000'],
            'delivery_address' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:online,in_person'],
            'payment_provider' => ['nullable', 'required_if:payment_method,online', 'in:paypal'],
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $booking = new Booking();

        $booking->fill([
            'user_id'         => $request->user()->id,
            'customer_name'   => $validated['customer_name'],
            'phone'           => $validated['phone'] ?? null,
            'pickup_address'  => $validated['pickup_address'] ?? null,
            'delivery_address'=> $validated['delivery_address'] ?? null,
            'service_id'      => $service->id,
            'weight_kg'       => $validated['weight_kg'],
            'service_option'  => $validated['service_option'],
            'pickup_date'     => $validated['pickup_date'],
            'pickup_time'     => $validated['pickup_time'],
            'total_price'     => ($service->price_per_kg ?? $service->price ?? 0) * $validated['weight_kg'],
            'payment_method'  => $validated['payment_method'],
            'payment_provider'=> $validated['payment_provider'] ?? null,
            'payment_status'  => 'pending',
            'delivery_status' => $validated['service_option'] === 'pickup_delivery'
    ? 'pending'
    : 'not_required',
            'status'          => 'pending',
        ]);

        $booking->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message'  => 'Your booking has been submitted successfully.',
                'redirect' => route('customer.bookings.show', $booking->id),
            ]);
        }

        return redirect()
            ->route('customer.bookings.show', $booking->id)
            ->with('success', 'Your booking has been submitted successfully!');
    }

    /** Booking details page */
    public function show(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load('service');

        return view('customer.bookings.show', compact('booking'));
    }
}
