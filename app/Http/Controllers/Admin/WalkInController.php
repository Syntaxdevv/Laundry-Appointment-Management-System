<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class WalkInController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->get();

        $walkIns = Booking::with(['service', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.walk-ins.index', compact(
            'services',
            'walkIns'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'service_id' => ['required', 'exists:services,id'],
            'weight' => ['required', 'numeric', 'min:0.1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $weight = (float) $validated['weight'];
        $totalPrice = $weight * (float) $service->price;

        Booking::create([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'customer_name' => $validated['customer_name'],
            'weight_kg' => $weight,
            'total_price' => $totalPrice,
            'service_option' => 'Walk-in',
            'pickup_date' => now()->toDateString(),
            'pickup_time' => now()->format('H:i:s'),
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('admin.walk-ins.index')
            ->with('success', 'Walk-in order added successfully!');
    }
}
