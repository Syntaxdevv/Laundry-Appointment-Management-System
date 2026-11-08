
@extends('layouts.delivery')

@section('title', 'Delivery Details')
@section('page-heading', '')

@section('content')

<div class="mx-auto w-full max-w-[1200px] min-w-0 font-['DM_Sans',sans-serif] text-[#252A34]">

    {{-- PAGE HEADING --}}
    <div class="mb-6">
        <a href="{{ route('delivery.deliveries.index') }}"
           class="mb-5 inline-flex items-center gap-2 text-[13px] font-semibold text-[#008C64] no-underline hover:text-[#006F50]">
            <i class="fa-solid fa-arrow-left"></i>
            Back to My Deliveries
        </a>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="mb-1 text-2xl font-extrabold text-[#252A34]">
                    Delivery Details
                </h2>
                <p class="m-0 text-[13px] text-[#78827B]">
                    Booking #{{ $delivery->id }}
                </p>
            </div>

            <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-full px-3 py-2 text-xs font-bold
                {{ $delivery->delivery_status === 'delivered'
                    ? 'bg-[#E8F5EF] text-[#008C64]'
                    : 'bg-[#FFF4DF] text-[#A66A00]' }}">
                <i class="fa-solid fa-circle text-[8px]"></i>
                {{ ucwords(str_replace('_', ' ', $delivery->delivery_status)) }}
            </span>
        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-2 rounded-md border border-[#B8EAD8] bg-[#E8F5EF] px-4 py-3 text-[13px] font-semibold text-[#008C64]">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if($errors->any())
        <div class="mb-5 flex items-center gap-2 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-[13px] font-semibold text-red-700">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- CUSTOMER INFORMATION --}}
    <div class="mb-[22px] overflow-hidden rounded-lg border border-[#E1E6E0] bg-white">

        <div class="flex items-center gap-3 border-b border-[#E1E6E0] px-5 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#E8F5EF] text-[#008C64]">
                <i class="fa-solid fa-user"></i>
            </div>

            <h3 class="m-0 text-[15px] font-bold text-[#252A34]">
                Customer Information
            </h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">

                <div class="min-w-0">
                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Customer Name
                    </span>
                    <p class="m-0 break-words text-sm font-semibold text-[#29312D]">
                        {{ $delivery->customer_name ?? $delivery->user?->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="min-w-0">
                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Email Address
                    </span>
                    <p class="m-0 break-words text-sm font-semibold text-[#29312D]">
                        {{ $delivery->user?->email ?? 'N/A' }}
                    </p>
                </div>

                <div class="min-w-0">
                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Phone Number
                    </span>
                    <p class="m-0 text-sm font-semibold text-[#29312D]">
                        {{ $delivery->phone ?? 'N/A' }}
                    </p>
                </div>

                {{-- PICKUP LOCATION --}}
                <div class="col-span-1 min-w-0 rounded-lg border border-[#E1E6E0] bg-[#FBFDFB] p-4 sm:col-span-2">

                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Pickup Location
                    </span>

                    <p class="mb-3 break-words text-sm font-semibold leading-relaxed text-[#29312D]">
                        <i class="fa-solid fa-location-dot mr-2 text-[#00A878]"></i>
                        {{ $delivery->pickup_address ?? 'N/A' }}
                    </p>

                    @php
                        $pickupHasCoordinates =
                            $delivery->pickup_latitude !== null &&
                            $delivery->pickup_longitude !== null;

                        $pickupDestination = $pickupHasCoordinates
                            ? $delivery->pickup_latitude . ',' . $delivery->pickup_longitude
                            : $delivery->pickup_address;
                    @endphp

                    @if(!empty($pickupDestination))
                        <a
                            href="https://www.google.com/maps/search/?api=1&query={{ urlencode($pickupDestination) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-md border border-[#B8EAD8] bg-[#E8F5EF] px-4 py-2.5 text-xs font-bold text-[#008C64] no-underline transition hover:bg-[#D5F0E4] hover:text-[#006F50]"
                        >
                            <i class="fa-solid fa-map-location-dot"></i>
                            View Pickup on Google Maps
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 text-xs font-semibold text-[#A66A00]">
                            <i class="fa-solid fa-circle-info"></i>
                            Pickup location is not available.
                        </span>
                    @endif
                </div>

                {{-- DELIVERY LOCATION --}}
                <div class="col-span-1 min-w-0 rounded-lg border border-[#E1E6E0] bg-[#FBFDFB] p-4 sm:col-span-2">

                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Delivery Location
                    </span>

                    <p class="mb-3 break-words text-sm font-semibold leading-relaxed text-[#29312D]">
                        <i class="fa-solid fa-location-dot mr-2 text-[#00A878]"></i>
                        {{ $delivery->delivery_address ?? 'N/A' }}
                    </p>

                    @php
                        $deliveryHasCoordinates =
                            $delivery->delivery_latitude !== null &&
                            $delivery->delivery_longitude !== null;

                        $deliveryDestination = $deliveryHasCoordinates
                            ? $delivery->delivery_latitude . ',' . $delivery->delivery_longitude
                            : $delivery->delivery_address;
                    @endphp

                    @if(!empty($deliveryDestination))
                        <a
                            href="https://www.google.com/maps/search/?api=1&query={{ urlencode($deliveryDestination) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-md border border-[#B8EAD8] bg-[#E8F5EF] px-4 py-2.5 text-xs font-bold text-[#008C64] no-underline transition hover:bg-[#D5F0E4] hover:text-[#006F50]"
                        >
                            <i class="fa-solid fa-map-location-dot"></i>
                            View Delivery on Google Maps
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 text-xs font-semibold text-[#A66A00]">
                            <i class="fa-solid fa-circle-info"></i>
                            Delivery location is not available.
                        </span>
                    @endif
                </div>

                {{-- DIRECTIONS --}}
                @if(!empty($pickupDestination) && !empty($deliveryDestination))
                    <div class="col-span-1 rounded-lg border border-[#B8EAD8] bg-[#F4FBF7] p-5 sm:col-span-2">
                        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                            <div>
                                <h4 class="mb-1 text-sm font-bold text-[#29312D]">
                                    <i class="fa-solid fa-route mr-2 text-[#008C64]"></i>
                                    Route Navigation
                                </h4>

                                <p class="m-0 text-xs leading-relaxed text-[#78827B]">
                                    Open Google Maps to view the route from pickup to delivery.
                                </p>
                            </div>

                            <a
                                href="https://www.google.com/maps/dir/?api=1&origin={{ urlencode($pickupDestination) }}&destination={{ urlencode($deliveryDestination) }}&travelmode=driving"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md bg-[#008C64] px-4 py-3 text-xs font-bold text-white no-underline transition hover:bg-[#006F50]"
                            >
                                <i class="fa-solid fa-diamond-turn-right"></i>
                                Get Directions
                            </a>

                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- BOOKING INFORMATION --}}
    <div class="mb-[22px] overflow-hidden rounded-lg border border-[#E1E6E0] bg-white">

        <div class="flex items-center gap-3 border-b border-[#E1E6E0] px-5 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#E8F5EF] text-[#008C64]">
                <i class="fa-solid fa-box"></i>
            </div>

            <h3 class="m-0 text-[15px] font-bold text-[#252A34]">
                Booking Information
            </h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">

                <div>
                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Service
                    </span>
                    <p class="m-0 text-sm font-semibold text-[#29312D]">
                        {{ $delivery->service?->name ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Pickup Date
                    </span>
                    <p class="m-0 text-sm font-semibold text-[#29312D]">
                        <i class="fa-regular fa-calendar mr-2 text-[#00A878]"></i>
                        {{ $delivery->pickup_date ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Pickup Time
                    </span>
                    <p class="m-0 text-sm font-semibold text-[#29312D]">
                        <i class="fa-regular fa-clock mr-2 text-[#00A878]"></i>
                        {{ $delivery->pickup_time ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Laundry Status
                    </span>
                    <p class="m-0 text-sm font-semibold text-[#29312D]">
                        {{ ucfirst($delivery->status ?? 'N/A') }}
                    </p>
                </div>

                <div>
                    <span class="mb-2 block text-xs font-semibold text-[#78827B]">
                        Delivery Status
                    </span>
                    <p class="m-0 text-sm font-semibold text-[#29312D]">
                        {{ ucwords(str_replace('_', ' ', $delivery->delivery_status ?? 'N/A')) }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- UPDATE DELIVERY STATUS --}}
    <div class="mb-[22px] overflow-hidden rounded-lg border border-[#E1E6E0] bg-white">

        <div class="flex items-center gap-3 border-b border-[#E1E6E0] px-5 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#E8F5EF] text-[#008C64]">
                <i class="fa-solid fa-truck"></i>
            </div>

            <h3 class="m-0 text-[15px] font-bold text-[#252A34]">
                Update Delivery Status
            </h3>
        </div>

        <div class="p-6">

            @if($delivery->delivery_status === 'pending')

                <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                    <div>
                        <h4 class="mb-1 text-[15px] font-bold text-[#29312D]">
                            Ready to start delivery?
                        </h4>

                        <p class="m-0 text-[13px] leading-relaxed text-[#78827B]">
                            Confirm that you are on your way to the customer.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('delivery.deliveries.status', $delivery->id) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="delivery_status" value="out_for_delivery">

                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-[#00A878] px-5 py-3 text-[13px] font-bold text-white transition hover:bg-[#008C64] sm:w-auto">
                            <i class="fa-solid fa-truck"></i>
                            Start Delivery
                        </button>
                    </form>
                </div>

            @elseif($delivery->delivery_status === 'out_for_delivery')

                <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                    <div>
                        <h4 class="mb-1 text-[15px] font-bold text-[#29312D]">
                            Delivery in progress
                        </h4>

                        <p class="m-0 text-[13px] leading-relaxed text-[#78827B]">
                            Mark this booking as delivered once the customer receives it.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('delivery.deliveries.status', $delivery->id) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="delivery_status" value="delivered">

                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-[#00A878] px-5 py-3 text-[13px] font-bold text-white transition hover:bg-[#008C64] sm:w-auto">
                            <i class="fa-solid fa-check"></i>
                            Mark as Delivered
                        </button>
                    </form>
                </div>

            @else

                <div class="flex items-center gap-4 rounded-lg border border-[#B8EAD8] bg-[#E8F5EF] p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#00A878] text-white">
                        <i class="fa-solid fa-check text-lg"></i>
                    </span>

                    <div>
                        <h4 class="mb-1 text-[15px] font-bold text-[#008C64]">
                            Delivery completed
                        </h4>

                        <p class="m-0 text-[13px] leading-relaxed text-[#387763]">
                            This delivery has been successfully completed.
                        </p>
                    </div>
                </div>

            @endif

        </div>
    </div>

</div>

@endsection
