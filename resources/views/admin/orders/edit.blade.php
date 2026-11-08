@extends('layouts.admin')

@section('title', 'Edit Order')
@section('page-heading', '')

@section('content')
<div class="edit-order-page text-[#172e43]">

    <div class="mb-[18px]">
        <a href="{{ route('admin.orders.show', $booking) }}"
           class="inline-flex items-center justify-center rounded-[5px] bg-[#eef3f8] px-4 py-[11px] text-[14px] text-[#263b50] no-underline transition-colors hover:bg-[#e1e9f1]">
            Cancel
        </a>
    </div>

    <section class="rounded-lg border border-[#e3e8ef] bg-white p-6 max-[650px]:p-4">

        <h2 class="mb-[6px] text-xl font-bold">
            Edit Order #{{ $booking->id }}
        </h2>

        <p class="mb-6 text-sm text-[#788497]">
            Update the customer's order information and staff assignment.
        </p>

        @if ($errors->any())
            <div class="mb-5 rounded-[5px] bg-[#fff0f0] px-4 py-3 text-sm text-[#9b2525]">
                <strong>Please check the following:</strong>

                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.update', $booking) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-5 max-[650px]:grid-cols-1">

                {{-- Customer Name --}}
                <div class="flex flex-col gap-2">
                    <label for="customer_name" class="text-sm font-semibold">
                        Customer Name
                    </label>

                    <input
                        id="customer_name"
                        name="customer_name"
                        type="text"
                        value="{{ old('customer_name', $booking->customer_name) }}"
                        required
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >
                </div>

                {{-- Order Status --}}
                <div class="flex flex-col gap-2">
                    <label for="status" class="text-sm font-semibold">
                        Order Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >
                        @foreach (['pending', 'washing', 'drying', 'processing', 'ready', 'completed', 'cancelled'] as $status)
                            <option
                                value="{{ $status }}"
                                @selected(old('status', strtolower($booking->status)) === $status)
                            >
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Weight --}}
                <div class="flex flex-col gap-2">
                    <label for="weight_kg" class="text-sm font-semibold">
                        Weight (kg)
                    </label>

                    <input
                        id="weight_kg"
                        name="weight_kg"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old('weight_kg', $booking->weight_kg) }}"
                        required
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >
                </div>

                {{-- Total Price --}}
                <div class="flex flex-col gap-2">
                    <label for="total_price" class="text-sm font-semibold">
                        Total Price (₱)
                    </label>

                    <input
                        id="total_price"
                        name="total_price"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old('total_price', $booking->total_price) }}"
                        required
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >
                </div>

                {{-- Service Option --}}
                <div class="flex flex-col gap-2">
                    <label for="service_option" class="text-sm font-semibold">
                        Service Option
                    </label>

                    <input
                        id="service_option"
                        name="service_option"
                        type="text"
                        value="{{ old('service_option', $booking->service_option) }}"
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >
                </div>

                {{-- Pickup Date --}}
                <div class="flex flex-col gap-2">
                    <label for="pickup_date" class="text-sm font-semibold">
                        Pickup Date
                    </label>

                    <input
                        id="pickup_date"
                        name="pickup_date"
                        type="date"
                        value="{{ old('pickup_date', $booking->pickup_date) }}"
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >
                </div>

                {{-- Pickup Time --}}
                <div class="flex flex-col gap-2">
                    <label for="pickup_time" class="text-sm font-semibold">
                        Pickup Time
                    </label>

                    <input
                        id="pickup_time"
                        name="pickup_time"
                        type="text"
                        placeholder="e.g. 10:00 AM"
                        value="{{ old('pickup_time', $booking->pickup_time) }}"
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >
                </div>

                {{-- STAFF ASSIGNMENT --}}
                <div class="flex flex-col gap-2">
                    <label for="assigned_staff_id" class="text-sm font-semibold">
                        Assign Laundry Staff
                    </label>

                    <select
                        name="assigned_staff_id"
                        id="assigned_staff_id"
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >
                        <option value="">Not Assigned</option>

                        @forelse ($staffUsers as $staff)
                            <option
                                value="{{ $staff->id }}"
                                @selected((string) old('assigned_staff_id', $booking->assigned_staff_id) === (string) $staff->id)
                            >
                                {{ $staff->name }}
                            </option>
                        @empty
                            <option value="" disabled>No staff accounts available</option>
                        @endforelse
                    </select>

                    <small class="text-xs text-[#788497]">
                        Select the staff member responsible for this laundry order.
                    </small>
                </div>

                {{-- Notes --}}
                <div class="col-span-full flex flex-col gap-2">
                    <label for="notes" class="text-sm font-semibold">
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        class="box-border w-full rounded-[5px] border border-[#d8e0e9] bg-white px-3 py-[11px] text-sm text-[#172e43] outline-none focus:border-[#2869ca] focus:ring-2 focus:ring-[#c8dcfb]"
                    >{{ old('notes', $booking->notes) }}</textarea>
                </div>

            </div>

            {{-- Form Actions --}}
            <div class="mt-6 flex justify-end gap-[10px]">
                <a href="{{ route('admin.orders.show', $booking) }}"
                   class="inline-block rounded-[5px] bg-[#eef3f8] px-4 py-[11px] text-sm text-[#263b50] no-underline transition-colors hover:bg-[#e1e9f1]">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-block cursor-pointer rounded-[5px] border-0 bg-[#2869ca] px-4 py-[11px] text-sm font-semibold text-white transition-colors hover:bg-[#1e55a8]"
                >
                    Save Changes
                </button>
            </div>

        </form>
    </section>
</div>
@endsection
