@extends('layouts.customer-dashboard')

@section('title', 'New Booking | LinisLaba')
@section('page-heading', '')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<style>
    #pickupContactFields[hidden],
    #paypalOptions[hidden],
    #bookingMessage[hidden],
    #gpsModal[hidden] {
        display: none !important;
    }

    #locationMap {
        width: 100%;
        height: 350px;
        z-index: 1;
    }

    .payment-option input:checked + .payment-option-content {
        border-color: #009B70;
        background: #F0FAF5;
    }

    .payment-option input:focus-visible + .payment-option-content {
        outline: 3px solid rgba(0,155,112,.15);
    }

    @media (max-width: 850px) {
        .booking-summary {
            position: static !important;
        }
    }

    @media (max-width: 500px) {
        #locationMap {
            height: 300px;
        }
    }
</style>
@endpush

@section('content')

@php
    $customerName = auth()->user()->name ?? '';
@endphp

<div class="box-border pb-[30px] font-['DM_Sans'] text-[#25332C]">

    {{-- PAGE HEADER --}}
    <div class="mb-[25px]">

        <a href="{{ route('customer.dashboard') }}"
           class="mb-[19px] inline-flex items-center gap-2 text-[12px] font-bold text-[#78857D] no-underline transition hover:text-[#009B70]">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>

        <div class="flex items-center justify-between gap-5">

            <div>
                <h1 class="mb-[7px] text-[27px] font-extrabold tracking-[-0.7px] text-[#24342B]">
                    Book a Laundry Service
                </h1>

                <p class="m-0 text-[13px] text-[#78857D]">
                    Complete the details below to reserve your laundry service.
                </p>
            </div>

            <div class="grid h-[52px] w-[52px] shrink-0 place-items-center rounded-[10px] bg-[#EAF7F1] text-[21px] text-[#009B70]">
                <i class="fa-solid fa-shirt"></i>
            </div>

        </div>
    </div>

    {{-- PROGRESS --}}
    <div class="mb-[22px] grid grid-cols-3 rounded-[9px] border border-[#E3EAE5] bg-white px-[22px] py-[17px] max-[600px]:px-[10px] max-[600px]:py-[13px]">

        @foreach([
            ['number' => '1', 'title' => 'Service Details', 'description' => 'Choose your service'],
            ['number' => '2', 'title' => 'Schedule', 'description' => 'Select date and time'],
            ['number' => '3', 'title' => 'Payment', 'description' => 'Choose payment method']
        ] as $step)

            <div class="flex items-center gap-[10px] max-[600px]:gap-[6px]">

                <div class="grid h-[30px] w-[30px] shrink-0 place-items-center rounded-full bg-[#009B70] text-[12px] font-extrabold text-white max-[600px]:h-[27px] max-[600px]:w-[27px] max-[600px]:text-[10px]">
                    {{ $step['number'] }}
                </div>

                <div>
                    <strong class="block text-[11px] font-extrabold max-[600px]:text-[9px]">
                        {{ $step['title'] }}
                    </strong>

                    <small class="mt-[3px] block text-[10px] text-[#78857D] max-[600px]:hidden">
                        {{ $step['description'] }}
                    </small>
                </div>

            </div>

        @endforeach

    </div>

    {{-- BOOKING LAYOUT --}}
    <div class="grid grid-cols-[minmax(0,1fr)_300px] items-start gap-5 max-[1050px]:grid-cols-[minmax(0,1fr)_260px] max-[850px]:grid-cols-1 max-[850px]:gap-[14px]">

        {{-- MAIN FORM --}}
        <div class="min-w-0 overflow-hidden rounded-[10px] border border-[#E3EAE5] bg-white">

            <form id="createBookingForm"
                  action="{{ route('customer.bookings.store') }}"
                  method="POST">

                @csrf

                {{-- MESSAGE --}}
                <div id="bookingMessage"
                     class="mx-[25px] mt-[18px] rounded-md border p-3 text-[11px] leading-[1.7]"
                     role="alert"
                     hidden></div>

                {{-- SERVICE DETAILS --}}
                <section class="border-b border-[#E3EAE5] p-[25px] max-[600px]:px-4 max-[600px]:py-[19px]">

                    <div class="mb-[23px] flex items-start gap-3">

                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-[#EAF7F1] text-base text-[#009B70]">
                            <i class="fa-solid fa-shirt"></i>
                        </div>

                        <div>
                            <h2 class="mb-[5px] mt-[2px] text-[15px] font-extrabold">
                                Service Details
                            </h2>

                            <p class="m-0 text-[11px] text-[#78857D]">
                                Tell us what laundry service you need.
                            </p>
                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-[19px] max-[600px]:grid-cols-1 max-[600px]:gap-4">

                        {{-- CUSTOMER NAME --}}
                        <div class="col-span-full min-w-0 max-[600px]:col-auto">

                            <label for="customerName"
                                   class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                Customer Name <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                   name="customer_name"
                                   id="customerName"
                                   maxlength="120"
                                   value="{{ old('customer_name', $customerName) }}"
                                   placeholder="Full name of the customer"
                                   required
                                   class="min-h-[44px] w-full rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                            @error('customer_name')
                                <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                            @enderror

                        </div>

                        {{-- SERVICE TYPE --}}
                        <div class="col-span-full min-w-0 max-[600px]:col-auto">

                            <label for="serviceSelect"
                                   class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                Service Type <span class="text-red-500">*</span>
                            </label>

                            <select name="service_id"
                                    id="serviceSelect"
                                    required
                                    class="min-h-[44px] w-full rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                                <option value="" data-price="0">Select a service...</option>

                                @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                            data-price="{{ (float) ($service->price_per_kg ?? $service->price) }}"
                                            @selected(old('service_id', request('service')) == $service->id)>
                                        {{ $service->name }} — ₱{{ number_format((float) ($service->price_per_kg ?? $service->price), 2) }} / kg
                                    </option>
                                @endforeach

                            </select>

                            @error('service_id')
                                <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                            @enderror

                        </div>

                        {{-- WEIGHT --}}
                        <div class="min-w-0">

                            <label for="weightInput"
                                   class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                Laundry Weight (kg) <span class="text-red-500">*</span>
                            </label>

                            <input type="number"
                                   name="weight_kg"
                                   id="weightInput"
                                   min="1"
                                   step="0.1"
                                   value="{{ old('weight_kg', 1) }}"
                                   placeholder="Example: 3.5"
                                   required
                                   class="min-h-[44px] w-full rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                            <span class="mt-[7px] block text-[10px] text-[#78857D]">
                                Enter your estimated laundry weight in kilograms.
                            </span>

                            @error('weight_kg')
                                <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                            @enderror

                        </div>

                        {{-- SERVICE METHOD --}}
                        <div class="min-w-0">

                            <label for="serviceOption"
                                   class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                Service Method <span class="text-red-500">*</span>
                            </label>

                            <select name="service_option"
                                    id="serviceOption"
                                    required
                                    class="min-h-[44px] w-full rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                                <option value="drop_off" @selected(old('service_option', 'drop_off') === 'drop_off')>
                                    Drop-off
                                </option>

                                <option value="pickup_delivery" @selected(old('service_option') === 'pickup_delivery')>
                                    Pick-up &amp; Delivery
                                </option>

                            </select>

                            @error('service_option')
                                <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                            @enderror

                        </div>

                        {{-- PICKUP DETAILS --}}
                        <div class="col-span-full min-w-0 rounded-[7px] border border-[#D8E9DF] bg-[#F8FBF8] p-[17px] max-[600px]:col-auto"
                             id="pickupContactFields"
                             hidden>

                            <div class="mb-4 text-[12px] font-extrabold text-[#007F5C]">
                                <i class="fa-solid fa-location-dot"></i>
                                Pick-up &amp; Delivery Information
                            </div>

                            <div class="grid grid-cols-2 gap-[19px] max-[600px]:grid-cols-1">

                                {{-- PHONE --}}
                                <div class="col-span-full min-w-0">

                                    <label for="phone"
                                           class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                        Contact Number <span class="text-red-500">*</span>
                                    </label>

                                    <input type="tel"
                                           name="phone"
                                           id="phone"
                                           maxlength="30"
                                           value="{{ old('phone') }}"
                                           placeholder="09XXXXXXXXX"
                                           disabled
                                           class="min-h-[44px] w-full rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                                    @error('phone')
                                        <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                                    @enderror

                                </div>

                                {{-- PICKUP ADDRESS --}}
                                <div class="col-span-full min-w-0">

                                    <label for="pickupAddress"
                                           class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                        Pickup Address <span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex items-stretch gap-[9px] max-[500px]:flex-col">

                                        <input type="text"
                                               name="pickup_address"
                                               id="pickupAddress"
                                               maxlength="1000"
                                               value="{{ old('pickup_address') }}"
                                               placeholder="House number, street, barangay, city"
                                               disabled
                                               class="min-h-[44px] min-w-0 flex-1 rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                                        <button type="button"
                                                class="btn-map-picker min-w-[110px] rounded-md border border-[#BBDCCB] bg-[#EAF7F1] px-3 py-[10px] text-[10px] font-extrabold text-[#007F5C] hover:bg-[#DDF1E6] disabled:cursor-not-allowed disabled:opacity-50 max-[500px]:min-h-10"
                                                data-location-type="pickup"
                                                disabled>
                                            <i class="fa-solid fa-map-location-dot"></i>
                                            Select on Map
                                        </button>

                                    </div>

                                    <input type="hidden" name="pickup_latitude" id="pickupLatitude" value="{{ old('pickup_latitude') }}">
                                    <input type="hidden" name="pickup_longitude" id="pickupLongitude" value="{{ old('pickup_longitude') }}">

                                    <small class="mt-[7px] block text-[10px] text-[#78857D]" id="pickupCoordinates">
                                        No GPS location selected.
                                    </small>

                                    @error('pickup_address')
                                        <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                                    @enderror

                                </div>

                                {{-- DELIVERY ADDRESS --}}
                                <div class="col-span-full min-w-0">

                                    <label for="deliveryAddress"
                                           class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                        Delivery Address
                                    </label>

                                    <div class="flex items-stretch gap-[9px] max-[500px]:flex-col">

                                        <input type="text"
                                               name="delivery_address"
                                               id="deliveryAddress"
                                               maxlength="1000"
                                               value="{{ old('delivery_address') }}"
                                               placeholder="Leave blank if same as pickup address"
                                               disabled
                                               class="min-h-[44px] min-w-0 flex-1 rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                                        <button type="button"
                                                class="btn-map-picker min-w-[110px] rounded-md border border-[#BBDCCB] bg-[#EAF7F1] px-3 py-[10px] text-[10px] font-extrabold text-[#007F5C] hover:bg-[#DDF1E6] disabled:cursor-not-allowed disabled:opacity-50 max-[500px]:min-h-10"
                                                data-location-type="delivery"
                                                disabled>
                                            <i class="fa-solid fa-map-location-dot"></i>
                                            Select on Map
                                        </button>

                                    </div>

                                    <input type="hidden" name="delivery_latitude" id="deliveryLatitude" value="{{ old('delivery_latitude') }}">
                                    <input type="hidden" name="delivery_longitude" id="deliveryLongitude" value="{{ old('delivery_longitude') }}">

                                    <small class="mt-[7px] block text-[10px] text-[#78857D]" id="deliveryCoordinates">
                                        No GPS location selected.
                                    </small>

                                    <span class="mt-[7px] block text-[10px] text-[#78857D]">
                                        Leave blank if the delivery address is the same.
                                    </span>

                                    @error('delivery_address')
                                        <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                                    @enderror

                                </div>

                            </div>
                        </div>

                    </div>
                </section>

                {{-- SCHEDULE --}}
                <section class="border-b border-[#E3EAE5] p-[25px] max-[600px]:px-4 max-[600px]:py-[19px]">

                    <div class="mb-[23px] flex items-start gap-3">

                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-[#EAF7F1] text-base text-[#009B70]">
                            <i class="fa-regular fa-calendar-check"></i>
                        </div>

                        <div>
                            <h2 class="mb-[5px] mt-[2px] text-[15px] font-extrabold">
                                Schedule
                            </h2>

                            <p class="m-0 text-[11px] text-[#78857D]">
                                Choose your preferred pickup or drop-off schedule.
                            </p>
                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-[19px] max-[600px]:grid-cols-1">

                        <div>
                            <label for="pickupDate"
                                   class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                Preferred Date <span class="text-red-500">*</span>
                            </label>

                            <input type="date"
                                   name="pickup_date"
                                   id="pickupDate"
                                   min="{{ now()->toDateString() }}"
                                   value="{{ old('pickup_date') }}"
                                   required
                                   class="min-h-[44px] w-full rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                            @error('pickup_date')
                                <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="pickupTime"
                                   class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                                Preferred Time <span class="text-red-500">*</span>
                            </label>

                            <select name="pickup_time"
                                    id="pickupTime"
                                    required
                                    class="min-h-[44px] w-full rounded-md border border-[#DCE5DE] bg-white px-[13px] py-[11px] text-[12px] outline-none focus:border-[#009B70] focus:ring-[3px] focus:ring-[#009B70]/10">

                                <option value="">Select a time...</option>

                                @foreach([
                                    '08:00' => '8:00 AM',
                                    '09:00' => '9:00 AM',
                                    '10:00' => '10:00 AM',
                                    '11:00' => '11:00 AM',
                                    '12:00' => '12:00 PM',
                                    '13:00' => '1:00 PM',
                                    '14:00' => '2:00 PM',
                                    '15:00' => '3:00 PM',
                                    '16:00' => '4:00 PM',
                                    '17:00' => '5:00 PM'
                                ] as $value => $label)

                                    <option value="{{ $value }}"
                                            @selected(old('pickup_time') === $value)>
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                            @error('pickup_time')
                                <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <p class="mt-3 text-[10px] leading-[1.7] text-[#78857D]">
                        <i class="fa-solid fa-circle-info text-[#009B70]"></i>
                        Your requested schedule will be subject to availability and admin confirmation.
                    </p>

                </section>

                {{-- PAYMENT --}}
                <section class="p-[25px] max-[600px]:px-4 max-[600px]:py-[19px]">

                    <div class="mb-[23px] flex items-start gap-3">

                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-[#EAF7F1] text-base text-[#009B70]">
                            <i class="fa-regular fa-credit-card"></i>
                        </div>

                        <div>
                            <h2 class="mb-[5px] mt-[2px] text-[15px] font-extrabold">
                                Payment Method
                            </h2>

                            <p class="m-0 text-[11px] text-[#78857D]">
                                Select how you want to pay for your laundry service.
                            </p>
                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-[13px] max-[600px]:grid-cols-1">

                        {{-- ONLINE PAYMENT --}}
                        <label class="payment-option relative block cursor-pointer">

                            <input type="radio"
                                   name="payment_method"
                                   value="online"
                                   class="absolute left-3 top-3 h-3 w-3 accent-[#009B70]"
                                   @checked(old('payment_method') === 'online')>

                            <div class="payment-option-content flex min-h-[100px] items-start gap-3 rounded-[8px] border border-[#E3EAE5] p-4 transition">

                                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-md bg-[#EAF7F1] text-[#009B70]">
                                    <i class="fa-solid fa-globe"></i>
                                </div>

                                <div class="pt-1">
                                    <strong class="block text-[12px] font-extrabold">
                                        Online Payment
                                    </strong>

                                    <span class="mt-1 block text-[10px] leading-[1.6] text-[#78857D]">
                                        Pay using the available online payment provider.
                                    </span>
                                </div>

                            </div>
                        </label>

                        {{-- IN PERSON --}}
                        <label class="payment-option relative block cursor-pointer">

                            <input type="radio"
                                   name="payment_method"
                                   value="in_person"
                                   class="absolute left-3 top-3 h-3 w-3 accent-[#009B70]"
                                   @checked(old('payment_method', 'in_person') === 'in_person')>

                            <div class="payment-option-content flex min-h-[100px] items-start gap-3 rounded-[8px] border border-[#E3EAE5] p-4 transition">

                                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-md bg-[#EAF7F1] text-[#009B70]">
                                    <i class="fa-solid fa-shop"></i>
                                </div>

                                <div class="pt-1">
                                    <strong class="block text-[12px] font-extrabold">
                                        Pay In Person
                                    </strong>

                                    <span class="mt-1 block text-[10px] leading-[1.6] text-[#78857D]">
                                        Pay directly at the laundry shop.
                                    </span>
                                </div>

                            </div>
                        </label>

                    </div>

                    {{-- ONLINE PROVIDER --}}
                    <div id="paypalOptions"
                         class="mt-4 rounded-[8px] border border-[#E3EAE5] bg-[#F8FBF8] p-4"
                         hidden>

                        <label class="mb-2 block text-[11px] font-extrabold text-[#39483F]">
                            Select Online Payment Provider
                        </label>

                        <label class="flex cursor-pointer items-center gap-3 rounded-md border border-[#DCE5DE] bg-white p-3">

                            <input type="radio"
                                   name="payment_provider"
                                   value="paypal"
                                   @checked(old('payment_provider', 'paypal') === 'paypal')
                                   class="h-4 w-4 accent-[#009B70]">

                            <span class="text-[12px] font-bold text-[#25332C]">
                                <i class="fa-brands fa-paypal text-[#0070BA]"></i>
                                PayPal
                            </span>

                        </label>

                    </div>

                    <p id="paymentNote"
                       class="mt-3 text-[10px] leading-[1.7] text-[#78857D]">
                        You can pay at the shop after your laundry service.
                    </p>

                    @error('payment_method')
                        <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                    @enderror

                    @error('payment_provider')
                        <span class="mt-[6px] block text-[10px] text-red-600">{{ $message }}</span>
                    @enderror

                </section>

                {{-- FORM ACTIONS --}}
                <div class="flex items-center justify-between gap-3 border-t border-[#E3EAE5] bg-[#FAFCFA] px-[25px] py-[18px] max-[500px]:flex-col-reverse max-[500px]:items-stretch max-[500px]:px-4">

                    <a href="{{ route('customer.dashboard') }}"
                       class="inline-flex min-h-[42px] items-center justify-center rounded-md border border-[#DCE5DE] bg-white px-5 text-[11px] font-bold text-[#59675F] no-underline hover:bg-[#F3F7F4]">
                        Cancel
                    </a>

                    <button type="submit"
                            id="submitBookingBtn"
                            class="inline-flex min-h-[42px] items-center justify-center gap-2 rounded-md bg-[#009B70] px-6 text-[11px] font-extrabold text-white transition hover:bg-[#007F5C] disabled:cursor-not-allowed disabled:opacity-60">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>Submit Booking</span>
                    </button>

                </div>

            </form>
        </div>

        {{-- BOOKING SUMMARY --}}
        <aside class="booking-summary sticky top-5 overflow-hidden rounded-[10px] border border-[#E3EAE5] bg-white">

            <div class="border-b border-[#E3EAE5] bg-[#F8FBF8] px-[19px] py-[17px]">

                <h3 class="m-0 text-[13px] font-extrabold text-[#25332C]">
                    <i class="fa-solid fa-receipt mr-2 text-[#009B70]"></i>
                    Booking Summary
                </h3>

                <p class="mb-0 mt-[5px] text-[10px] text-[#78857D]">
                    Review your booking details.
                </p>

            </div>

            <div class="p-[19px]">

                {{-- SERVICE --}}
                <div class="mb-[17px]">

                    <span class="mb-[6px] block text-[10px] font-bold uppercase tracking-[.5px] text-[#78857D]">
                        Selected Service
                    </span>

                    <strong id="summaryServiceName"
                            class="block text-[12px] font-extrabold text-[#25332C]">
                        Not selected
                    </strong>

                    <span id="summaryServicePrice"
                          class="mt-1 block text-[10px] text-[#78857D]">
                        ₱0.00 / kg
                    </span>

                </div>

                <div class="my-[15px] border-t border-dashed border-[#DCE5DE]"></div>

                {{-- WEIGHT --}}
                <div class="mb-[14px] flex items-center justify-between gap-3">

                    <span class="text-[11px] text-[#78857D]">Weight</span>

                    <strong id="summaryWeight"
                            class="text-[11px] font-bold">
                        1 kg
                    </strong>

                </div>

                {{-- METHOD --}}
                <div class="mb-[14px] flex items-center justify-between gap-3">

                    <span class="text-[11px] text-[#78857D]">Service Method</span>

                    <strong id="summaryMethod"
                            class="text-right text-[11px] font-bold">
                        Drop-off
                    </strong>

                </div>

                {{-- SCHEDULE --}}
                <div class="mb-[14px] flex items-start justify-between gap-3">

                    <span class="text-[11px] text-[#78857D]">Schedule</span>

                    <strong id="summarySchedule"
                            class="max-w-[145px] text-right text-[11px] font-bold">
                        Not selected
                    </strong>

                </div>

                {{-- PAYMENT --}}
                <div class="mb-[14px] flex items-center justify-between gap-3">

                    <span class="text-[11px] text-[#78857D]">Payment</span>

                    <strong id="summaryPayment"
                            class="text-right text-[11px] font-bold">
                        Pay In Person
                    </strong>

                </div>

                <div class="my-[15px] border-t border-dashed border-[#DCE5DE]"></div>

                {{-- BREAKDOWN --}}
                <div id="priceBreakdown"
                     class="mb-[14px] space-y-2 text-[10px] text-[#78857D]">

                    <div class="flex justify-between gap-3">
                        <span>Service cost</span>
                        <span id="summaryBasePrice">₱0.00</span>
                    </div>

                </div>

                {{-- TOTAL --}}
                <div class="flex items-center justify-between gap-3 rounded-md bg-[#EAF7F1] px-3 py-[13px]">

                    <span class="text-[11px] font-extrabold text-[#007F5C]">
                        Estimated Total
                    </span>

                    <strong id="totalPrice"
                            class="text-[18px] font-extrabold text-[#007F5C]">
                        ₱0.00
                    </strong>

                </div>

                <p class="mb-0 mt-3 text-[9px] leading-[1.7] text-[#78857D]">
                    <i class="fa-solid fa-circle-info text-[#009B70]"></i>
                    Final charges may be adjusted after the laundry is weighed and reviewed.
                </p>

            </div>

        </aside>

    </div>

</div>

{{-- GPS MAP MODAL --}}
<div id="gpsModal"
     class="gps-modal fixed inset-0 z-[9999] grid place-items-center bg-[rgba(20,35,27,0.60)] p-[18px]"
     hidden>

    <div class="gps-modal-panel w-full max-w-[650px] overflow-hidden rounded-[10px] bg-white shadow-xl">

        {{-- MODAL HEADER --}}
        <div class="flex items-center justify-between border-b border-[#E3EAE5] px-5 py-4">

            <div>
                <h3 id="gpsModalTitle"
                    class="m-0 text-[14px] font-extrabold text-[#25332C]">
                    Select Location
                </h3>

                <p class="mb-0 mt-1 text-[10px] text-[#78857D]">
                    Move the map marker or click your preferred location.
                </p>
            </div>

            <button type="button"
                    id="closeGpsModal"
                    class="grid h-8 w-8 place-items-center rounded-md text-[#78857D] hover:bg-[#F0F5F1] hover:text-[#25332C]">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

        {{-- MAP --}}
        <div class="p-4">

            <div id="locationMap"
                 class="overflow-hidden rounded-[7px] border border-[#E3EAE5]">
            </div>

            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">

                <p id="gpsStatus"
                   class="m-0 text-[10px] leading-[1.6] text-[#78857D]">
                    Select a location on the map.
                </p>

                <button type="button"
                        id="useCurrentLocation"
                        class="inline-flex min-h-[36px] items-center gap-2 rounded-md border border-[#BBDCCB] bg-[#EAF7F1] px-3 text-[10px] font-bold text-[#007F5C] hover:bg-[#DDF1E6]">
                    <i class="fa-solid fa-location-crosshairs"></i>
                    Use My Location
                </button>

            </div>

        </div>

        {{-- MODAL ACTIONS --}}
        <div class="flex justify-end gap-2 border-t border-[#E3EAE5] bg-[#FAFCFA] px-5 py-4">

            <button type="button"
                    id="cancelMapLocation"
                    class="min-h-[38px] rounded-md border border-[#DCE5DE] bg-white px-4 text-[10px] font-bold text-[#59675F] hover:bg-[#F3F7F4]">
                Cancel
            </button>

            <button type="button"
                    id="confirmMapLocation"
                    class="min-h-[38px] rounded-md bg-[#009B70] px-4 text-[10px] font-extrabold text-white hover:bg-[#007F5C]">
                Confirm Location
            </button>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const form = $('#createBookingForm');

    const serviceSelect = $('#serviceSelect');
    const weightInput = $('#weightInput');
    const serviceOption = $('#serviceOption');

    const pickupFields = $('#pickupContactFields');
    const phoneInput = $('#phone');
    const pickupAddress = $('#pickupAddress');
    const deliveryAddress = $('#deliveryAddress');

    const pickupDate = $('#pickupDate');
    const pickupTime = $('#pickupTime');

    const paypalOptions = $('#paypalOptions');
    const paymentNote = $('#paymentNote');

    const submitButton = $('#submitBookingBtn');

    let map = null;
    let marker = null;
    let selectedLocationType = null;
    let selectedLatitude = 15.1450;
    let selectedLongitude = 120.5887;
    let currentAddress = '';

    /*
    |--------------------------------------------------------------------------
    | PRICE CALCULATION
    |--------------------------------------------------------------------------
    */

    function formatCurrency(amount) {
        return '₱' + Number(amount || 0).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function calculateTotal() {

        const selectedOption = serviceSelect.find(':selected');

        const serviceName = selectedOption.val()
            ? selectedOption.text().split(' — ')[0]
            : 'Not selected';

        const price = parseFloat(selectedOption.data('price')) || 0;
        const weight = parseFloat(weightInput.val()) || 0;

        const total = price * weight;

        $('#summaryServiceName').text(serviceName);
        $('#summaryServicePrice').text(formatCurrency(price) + ' / kg');

        $('#summaryWeight').text(weight + ' kg');

        $('#summaryBasePrice').text(formatCurrency(total));
        $('#totalPrice').text(formatCurrency(total));

        return total;
    }

    /*
    |--------------------------------------------------------------------------
    | SERVICE METHOD
    |--------------------------------------------------------------------------
    */

    function updateServiceFields() {

        const isPickup = serviceOption.val() === 'pickup_delivery';

        pickupFields.prop('hidden', !isPickup);

        phoneInput.prop('disabled', !isPickup);
        pickupAddress.prop('disabled', !isPickup);
        deliveryAddress.prop('disabled', !isPickup);

        $('.btn-map-picker').prop('disabled', !isPickup);

        if (isPickup) {
            phoneInput.prop('required', true);
            pickupAddress.prop('required', true);
        } else {
            phoneInput.prop('required', false);
            pickupAddress.prop('required', false);
        }

        $('#summaryMethod').text(
            isPickup ? 'Pick-up & Delivery' : 'Drop-off'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT METHOD
    |--------------------------------------------------------------------------
    */

    function updatePaymentNote() {

        const selectedPayment = $('input[name="payment_method"]:checked').val();

        if (selectedPayment === 'online') {

            paypalOptions.prop('hidden', false);

            paymentNote.text(
                'You will be redirected to the selected online payment provider after submitting your booking.'
            );

            submitButton.find('span').text('Continue to Payment');

        } else {

            paypalOptions.prop('hidden', true);

            paymentNote.text(
                'You can pay at the shop after your laundry service.'
            );

            submitButton.find('span').text('Submit Booking');
        }

        $('#summaryPayment').text(
            selectedPayment === 'online' ? 'Online Payment' : 'Pay In Person'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCHEDULE SUMMARY
    |--------------------------------------------------------------------------
    */

    function updateSchedule() {

        const dateValue = pickupDate.val();
        const timeValue = pickupTime.val();

        if (!dateValue || !timeValue) {
            $('#summarySchedule').text('Not selected');
            return;
        }

        const date = new Date(dateValue + 'T00:00:00');

        const formattedDate = date.toLocaleDateString('en-PH', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });

        const [hours, minutes] = timeValue.split(':');

        const time = new Date();
        time.setHours(parseInt(hours), parseInt(minutes));

        const formattedTime = time.toLocaleTimeString('en-PH', {
            hour: 'numeric',
            minute: '2-digit'
        });

        $('#summarySchedule').text(formattedDate + ' • ' + formattedTime);
    }

    /*
    |--------------------------------------------------------------------------
    | MESSAGE HANDLING
    |--------------------------------------------------------------------------
    */

    function showMessage(message, type = 'error') {

        const messageBox = $('#bookingMessage');

        messageBox
            .removeClass(
                'border-red-200 bg-red-50 text-red-700 border-green-200 bg-green-50 text-green-700'
            );

        if (type === 'success') {
            messageBox.addClass('border-green-200 bg-green-50 text-green-700');
        } else {
            messageBox.addClass('border-red-200 bg-red-50 text-red-700');
        }

        messageBox.text(message);
        messageBox.prop('hidden', false);

        $('html, body').animate({
            scrollTop: messageBox.offset().top - 100
        }, 250);
    }

    function clearMessage() {
        $('#bookingMessage').prop('hidden', true).text('');
    }

    function setButtonLoading(loading) {

        submitButton.prop('disabled', loading);

        if (loading) {
            submitButton.html(
                '<i class="fa-solid fa-spinner fa-spin"></i><span>Processing...</span>'
            );
        } else {
            updatePaymentNote();
        }
    }

    function getErrorMessage(xhr) {

        if (xhr.responseJSON) {

            if (xhr.responseJSON.message) {
                return xhr.responseJSON.message;
            }

            if (xhr.responseJSON.errors) {

                const errors = xhr.responseJSON.errors;
                const firstKey = Object.keys(errors)[0];

                if (firstKey && errors[firstKey].length) {
                    return errors[firstKey][0];
                }
            }
        }

        return 'Something went wrong. Please check your information and try again.';
    }

    /*
    |--------------------------------------------------------------------------
    | LEAFLET MAP INITIALIZATION
    |--------------------------------------------------------------------------
    */

    function initializeMap() {

        if (map !== null) {
            setTimeout(function () {
                map.invalidateSize();
            }, 200);

            return;
        }

        map = L.map('locationMap').setView(
            [selectedLatitude, selectedLongitude],
            14
        );

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker(
            [selectedLatitude, selectedLongitude],
            { draggable: true }
        ).addTo(map);

        marker.on('dragend', function (event) {

            const position = event.target.getLatLng();

            selectedLatitude = position.lat;
            selectedLongitude = position.lng;

            reverseGeocode(
                selectedLatitude,
                selectedLongitude
            );
        });

        map.on('click', function (event) {

            selectedLatitude = event.latlng.lat;
            selectedLongitude = event.latlng.lng;

            marker.setLatLng(event.latlng);

            reverseGeocode(
                selectedLatitude,
                selectedLongitude
            );
        });

    }

    /*
    |--------------------------------------------------------------------------
    | REVERSE GEOCODING
    |--------------------------------------------------------------------------
    */

    function reverseGeocode(latitude, longitude) {

        $('#gpsStatus').text('Getting address information...');

        fetch(
            'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
            encodeURIComponent(latitude) +
            '&lon=' +
            encodeURIComponent(longitude)
        )
        .then(function (response) {

            if (!response.ok) {
                throw new Error('Unable to retrieve address.');
            }

            return response.json();
        })
        .then(function (data) {

            currentAddress = data.display_name || '';

            if (currentAddress) {
                $('#gpsStatus').text(currentAddress);
            } else {
                $('#gpsStatus').text('Location selected. No address found.');
            }

        })
        .catch(function () {

            currentAddress = '';

            $('#gpsStatus').text(
                'Location selected. Address lookup is unavailable.'
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | OPEN MAP PICKER
    |--------------------------------------------------------------------------
    */

    $('.btn-map-picker').on('click', function () {

        selectedLocationType = $(this).data('location-type');

        if (selectedLocationType === 'pickup') {

            $('#gpsModalTitle').text('Select Pickup Location');

            const latitude = parseFloat($('#pickupLatitude').val());
            const longitude = parseFloat($('#pickupLongitude').val());

            selectedLatitude = Number.isFinite(latitude)
                ? latitude : 15.1450;

            selectedLongitude = Number.isFinite(longitude)
                ? longitude : 120.5887;

        } else {

            $('#gpsModalTitle').text('Select Delivery Location');

            const latitude = parseFloat($('#deliveryLatitude').val());
            const longitude = parseFloat($('#deliveryLongitude').val());

            selectedLatitude = Number.isFinite(latitude)
                ? latitude : 15.1450;

            selectedLongitude = Number.isFinite(longitude)
                ? longitude : 120.5887;
        }

        currentAddress = '';

        $('#gpsStatus').text('Select a location on the map.');

        $('#gpsModal').prop('hidden', false);

        initializeMap();

        map.setView(
            [selectedLatitude, selectedLongitude],
            15
        );

        marker.setLatLng([
            selectedLatitude,
            selectedLongitude
        ]);

        setTimeout(function () {
            map.invalidateSize();
        }, 250);

    });

    /*
    |--------------------------------------------------------------------------
    | CURRENT LOCATION
    |--------------------------------------------------------------------------
    */

    $('#useCurrentLocation').on('click', function () {

        if (!navigator.geolocation) {

            $('#gpsStatus').text(
                'Geolocation is not supported by your browser.'
            );

            return;
        }

        $('#gpsStatus').text('Getting your current location...');

        navigator.geolocation.getCurrentPosition(

            function (position) {

                selectedLatitude = position.coords.latitude;
                selectedLongitude = position.coords.longitude;

                map.setView(
                    [selectedLatitude, selectedLongitude],
                    17
                );

                marker.setLatLng([
                    selectedLatitude,
                    selectedLongitude
                ]);

                reverseGeocode(
                    selectedLatitude,
                    selectedLongitude
                );

            },

            function (error) {

                let message = 'Unable to get your current location.';

                if (error.code === error.PERMISSION_DENIED) {
                    message = 'Location permission was denied. Please allow location access in your browser.';
                }

                if (error.code === error.POSITION_UNAVAILABLE) {
                    message = 'Your current location is unavailable.';
                }

                if (error.code === error.TIMEOUT) {
                    message = 'Location request timed out. Please try again.';
                }

                $('#gpsStatus').text(message);

            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }

        );

    });

    /*
    |--------------------------------------------------------------------------
    | CONFIRM MAP LOCATION
    |--------------------------------------------------------------------------
    */

    $('#confirmMapLocation').on('click', function () {

        if (!selectedLocationType) {
            return;
        }

        if (selectedLocationType === 'pickup') {

            $('#pickupLatitude').val(selectedLatitude);
            $('#pickupLongitude').val(selectedLongitude);

            if (currentAddress) {
                $('#pickupAddress').val(currentAddress);
            }

            $('#pickupCoordinates').text(
                selectedLatitude.toFixed(6) + ', ' +
                selectedLongitude.toFixed(6)
            );

        } else {

            $('#deliveryLatitude').val(selectedLatitude);
            $('#deliveryLongitude').val(selectedLongitude);

            if (currentAddress) {
                $('#deliveryAddress').val(currentAddress);
            }

            $('#deliveryCoordinates').text(
                selectedLatitude.toFixed(6) + ', ' +
                selectedLongitude.toFixed(6)
            );

        }

        $('#gpsModal').prop('hidden', true);

    });

    /*
    |--------------------------------------------------------------------------
    | CLOSE MAP MODAL
    |--------------------------------------------------------------------------
    */

    function closeMapModal() {
        $('#gpsModal').prop('hidden', true);
        selectedLocationType = null;
    }

    $('#closeGpsModal, #cancelMapLocation').on('click', function () {
        closeMapModal();
    });

    $('#gpsModal').on('click', function (event) {

        if (event.target === this) {
            closeMapModal();
        }

    });

    $(document).on('keydown', function (event) {

        if (event.key === 'Escape' && !$('#gpsModal').prop('hidden')) {
            closeMapModal();
        }

    });

    /*
    |--------------------------------------------------------------------------
    | FORM INPUT EVENTS
    |--------------------------------------------------------------------------
    */

    serviceSelect.on('change', calculateTotal);

    weightInput.on('input change', calculateTotal);

    serviceOption.on('change', updateServiceFields);

    pickupDate.on('change', updateSchedule);

    pickupTime.on('change', updateSchedule);

    $('input[name="payment_method"]').on('change', updatePaymentNote);

    /*
    |--------------------------------------------------------------------------
    | BOOKING FORM SUBMISSION
    |--------------------------------------------------------------------------
    */

    form.on('submit', function (event) {

        event.preventDefault();

        clearMessage();

        const selectedPayment = $('input[name="payment_method"]:checked').val();

        if (!selectedPayment) {
            showMessage('Please select a payment method.');
            return;
        }

        if (selectedPayment === 'online') {

            const provider = $('input[name="payment_provider"]:checked').val();

            if (!provider) {
                showMessage('Please select an online payment provider.');
                return;
            }
        }

        const weight = parseFloat(weightInput.val());

        if (!weight || weight < 1) {
            showMessage('Please enter a valid laundry weight of at least 1 kg.');
            weightInput.trigger('focus');
            return;
        }

        if (serviceOption.val() === 'pickup_delivery') {

            if (!phoneInput.val().trim()) {
                showMessage('Please enter your contact number.');
                phoneInput.trigger('focus');
                return;
            }

            if (!pickupAddress.val().trim()) {
                showMessage('Please enter your pickup address.');
                pickupAddress.trigger('focus');
                return;
            }
        }

        setButtonLoading(true);

        $.ajax({

            url: form.attr('action'),

            method: 'POST',

            data: form.serialize(),

            dataType: 'json',

            headers: {
                'Accept': 'application/json'
            },

            success: function (response) {

                if (response.redirect) {

                    window.location.href = response.redirect;

                    return;
                }

                if (response.success) {

                    showMessage(
                        response.message || 'Your booking was submitted successfully.',
                        'success'
                    );

                    if (response.url) {
                        window.location.href = response.url;
                    }

                    return;
                }

                showMessage(
                    response.message || 'Your booking could not be submitted.'
                );

                setButtonLoading(false);

            },

            error: function (xhr) {

                if (xhr.status === 422 && xhr.responseJSON?.errors) {

                    const errors = xhr.responseJSON.errors;
                    const firstKey = Object.keys(errors)[0];

                    if (firstKey && errors[firstKey].length) {
                        showMessage(errors[firstKey][0]);
                    } else {
                        showMessage(getErrorMessage(xhr));
                    }

                } else {

                    showMessage(getErrorMessage(xhr));

                }

                setButtonLoading(false);

            }

        });

    });

    /*
    |--------------------------------------------------------------------------
    | INITIALIZE PAGE
    |--------------------------------------------------------------------------
    */

    calculateTotal();
    updateServiceFields();
    updatePaymentNote();
    updateSchedule();

});
</script>
@endpush