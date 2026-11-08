
@extends('layouts.staff')

@section('title', 'Walk-In Management')
@section('page-heading', '')

@section('content')
<div class="w-full space-y-6">

    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#252A34]">New Walk-In Order</h2>
            <p class="mt-1 text-sm text-gray-500">Register a customer and record their laundry order.</p>
        </div>

        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E6F7F2] text-xl text-[#00A878]">
            <i class="fa-solid fa-shirt"></i>
        </div>
    </div>

    <form action="{{ route('staff.walk-ins.store') }}" method="POST" id="walkinForm">
        @csrf

        <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">

            <div class="min-w-0 space-y-6">
                <section class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#E6F7F2] text-[#00A878]">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#252A34]">Customer Information</h3>
                            <p class="text-sm text-gray-500">Enter the customer's contact details.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <label for="customer_name" class="mb-2 block text-sm font-semibold text-gray-700">
                                Customer Name <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="customer_name"
                                name="customer_name"
                                value="{{ old('customer_name') }}"
                                placeholder="Enter full name"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00A878] focus:ring-2 focus:ring-[#00A878]/10"
                            >

                            @error('customer_name')
                                <small class="mt-1 block text-xs text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="mb-2 block text-sm font-semibold text-gray-700">
                                Contact Number <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="09XX XXX XXXX"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00A878] focus:ring-2 focus:ring-[#00A878]/10"
                            >

                            @error('phone')
                                <small class="mt-1 block text-xs text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Registered customer email"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00A878] focus:ring-2 focus:ring-[#00A878]/10"
                            >

                            @error('email')
                                <small class="mt-1 block text-xs text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </section>

                <section class="rounded-xl border border-gray-200 bg-white p-5 sm:p-6">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#E6F7F2] text-[#00A878]">
                            <i class="fa-solid fa-soap"></i>
                        </div>

                        <div>
                            <h3 class="font-bold text-[#252A34]">Laundry Details</h3>
                            <p class="text-sm text-gray-500">Select the service and enter the laundry weight.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="service_id" class="mb-2 block text-sm font-semibold text-gray-700">
                                Laundry Service <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="service_id"
                                name="service_id"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00A878] focus:ring-2 focus:ring-[#00A878]/10"
                            >
                                <option value="">Select a service</option>

                                @foreach(($services ?? collect()) as $service)
                                    <option
                                        value="{{ $service->id }}"
                                        data-price="{{ $service->price_per_kg ?? $service->price ?? 0 }}"
                                        {{ old('service_id') == $service->id ? 'selected' : '' }}
                                    >
                                        {{ $service->name }} — ₱{{ number_format((float) ($service->price_per_kg ?? $service->price ?? 0), 2) }}
                                    </option>
                                @endforeach
                            </select>

                            @if(empty($services) || collect($services)->isEmpty())
                                <small class="mt-2 block text-xs text-gray-500">
                                    No services are available yet. Add services in your system first.
                                </small>
                            @endif

                            @error('service_id')
                                <small class="mt-1 block text-xs text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- SERVICE OPTION --}}
                        <div class="sm:col-span-2">
                            <label for="service_option" class="mb-2 block text-sm font-semibold text-gray-700">
                                Service Option <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="service_option"
                                name="service_option"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00A878] focus:ring-2 focus:ring-[#00A878]/10"
                            >
                                <option value="drop_off" {{ old('service_option', 'drop_off') === 'drop_off' ? 'selected' : '' }}>
                                    Store Collection — Customer collects at the shop
                                </option>

                                <option value="pickup_delivery" {{ old('service_option') === 'pickup_delivery' ? 'selected' : '' }}>
                                    Delivery — Rider delivers finished laundry
                                </option>
                            </select>

                            <small class="mt-2 block text-xs text-gray-500">
                                Choose how the customer wants to receive the finished laundry.
                            </small>

                            @error('service_option')
                                <small class="mt-1 block text-xs text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div
                            class="hidden sm:col-span-2"
                            id="deliveryAddressGroup"
                        >
                            <label for="delivery_address" class="mb-2 block text-sm font-semibold text-gray-700">
                                Delivery Address <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="delivery_address"
                                name="delivery_address"
                                rows="3"
                                placeholder="Enter the complete delivery address"
                                class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00A878] focus:ring-2 focus:ring-[#00A878]/10"
                            >{{ old('delivery_address') }}</textarea>

                            @error('delivery_address')
                                <small class="mt-1 block text-xs text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- WEIGHT --}}
                        <div>
                            <label for="weight_kg" class="mb-2 block text-sm font-semibold text-gray-700">
                                Weight (kg) <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="number"
                                id="weight_kg"
                                name="weight_kg"
                                value="{{ old('weight_kg') }}"
                                min="0.1"
                                step="0.1"
                                placeholder="e.g. 5"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00A878] focus:ring-2 focus:ring-[#00A878]/10"
                            >

                            @error('weight_kg')
                                <small class="mt-1 block text-xs text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div>
                            <label for="instructions" class="mb-2 block text-sm font-semibold text-gray-700">
                                Additional Instructions
                            </label>

                            <input
                                type="text"
                                id="instructions"
                                name="instructions"
                                value="{{ old('instructions') }}"
                                placeholder="Optional laundry notes"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00A878] focus:ring-2 focus:ring-[#00A878]/10"
                            >

                            @error('instructions')
                                <small class="mt-1 block text-xs text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </section>

            </div>

            <aside class="h-fit rounded-xl border border-gray-200 bg-white p-5">

                <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                    <i class="fa-solid fa-receipt text-lg text-[#00A878]"></i>
                    <h3 class="font-bold text-[#252A34]">Order Summary</h3>
                </div>

                <div class="space-y-4">

                    <div class="flex items-start justify-between gap-3 text-sm">
                        <span class="text-gray-500">Customer</span>
                        <strong id="summaryCustomer" class="max-w-[170px] break-words text-right font-semibold text-gray-800">Not entered</strong>
                    </div>

                    <div class="flex items-start justify-between gap-3 text-sm">
                        <span class="text-gray-500">Service</span>
                        <strong id="summaryService" class="max-w-[170px] break-words text-right font-semibold text-gray-800">Not selected</strong>
                    </div>

                    <div class="flex items-center justify-between gap-3 text-sm">
                        <span class="text-gray-500">Weight</span>
                        <strong id="summaryWeight" class="font-semibold text-gray-800">0 kg</strong>
                    </div>

                    <div class="flex items-center justify-between gap-3 text-sm">
                        <span class="text-gray-500">Rate</span>
                        <strong id="summaryRate" class="font-semibold text-gray-800">₱0.00</strong>
                    </div>

                    <div class="flex items-start justify-between gap-3 text-sm">
                        <span class="text-gray-500">Collection</span>
                        <strong id="summaryOption" class="max-w-[170px] text-right font-semibold text-gray-800">Store Collection</strong>
                    </div>

                    <div class="border-t border-dashed border-gray-200 pt-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm font-semibold text-gray-700">Estimated Total</span>
                            <strong id="summaryTotal" class="text-xl font-bold text-[#00A878]">₱0.00</strong>
                        </div>
                    </div>

                    <p class="text-xs leading-relaxed text-gray-400">
                        The total shown is an estimate. Final pricing must be validated by the server.
                    </p>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#00A878] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#008F66] focus:outline-none focus:ring-2 focus:ring-[#00A878]/30"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Walk-In Order
                    </button>

                    <a
                        href="{{ route('staff.orders.index') }}"
                        class="block w-full rounded-lg border border-gray-300 px-4 py-3 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                    >
                        Cancel
                    </a>

                </div>
            </aside>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const nameInput = document.getElementById('customer_name');
    const serviceSelect = document.getElementById('service_id');
    const weightInput = document.getElementById('weight_kg');
    const serviceOption = document.getElementById('service_option');
    const deliveryAddressGroup = document.getElementById('deliveryAddressGroup');
    const deliveryAddress = document.getElementById('delivery_address');
    const summaryCustomer = document.getElementById('summaryCustomer');
    const summaryService = document.getElementById('summaryService');
    const summaryWeight = document.getElementById('summaryWeight');
    const summaryRate = document.getElementById('summaryRate');
    const summaryTotal = document.getElementById('summaryTotal');
    const summaryOption = document.getElementById('summaryOption');

    function formatCurrency(amount) {
        return '₱' + amount.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function toggleDeliveryAddress() {
        const isDelivery = serviceOption.value === 'pickup_delivery';

        deliveryAddressGroup.classList.toggle('hidden', !isDelivery);
        deliveryAddress.required = isDelivery;

        if (!isDelivery) {
            deliveryAddress.value = '';
        }

        summaryOption.textContent = isDelivery
            ? 'Delivery'
            : 'Store Collection';
    }

    function updateSummary() {
        const customerName = nameInput.value.trim();

        const selectedOption =
            serviceSelect.options[serviceSelect.selectedIndex];

        const rate = Number(selectedOption?.dataset.price || 0);
        const weight = Number(weightInput.value || 0);
        const total = rate * weight;

        summaryCustomer.textContent =
            customerName || 'Not entered';

        summaryService.textContent = selectedOption?.value
            ? selectedOption.text.split(' — ')[0].trim()
            : 'Not selected';

        summaryWeight.textContent =
            weight > 0 ? weight + ' kg' : '0 kg';

        summaryRate.textContent = formatCurrency(rate);
        summaryTotal.textContent = formatCurrency(total);
    }

    nameInput.addEventListener('input', updateSummary);
    serviceSelect.addEventListener('change', updateSummary);
    weightInput.addEventListener('input', updateSummary);

    serviceOption.addEventListener('change', toggleDeliveryAddress);

    toggleDeliveryAddress();
    updateSummary();
});
</script>
@endsection
