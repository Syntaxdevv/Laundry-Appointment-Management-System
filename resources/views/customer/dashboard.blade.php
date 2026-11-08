@extends('layouts.customer-dashboard')

@section('title', 'Customer Dashboard')
@section('page-heading', '')

@section('content')

@php
    $customerName = auth()->user()->name ?? 'Customer';
    $firstName = explode(' ', trim($customerName))[0];
    $today = now();

    $badgeClass = fn ($s) => match ($s) {
        'washing', 'drying' => 'bg-[#E8F5EF] text-[#008C64]',
        'ready' => 'bg-[#DDF3E9] text-[#007B58]',
        'completed' => 'bg-[#E0F7ED] text-[#16845C]',
        'cancelled' => 'bg-[#FDE8E8] text-[#B42318]',
        default => 'bg-[#FFF3DC] text-[#A06500]',
    };

    $status = $activeBooking?->status;

    $steps = [
        ['name' => 'Order Received', 'icon' => 'fa-check', 'statuses' => ['pending', 'washing', 'drying', 'ready', 'completed']],
        ['name' => 'Washing', 'icon' => 'fa-soap', 'statuses' => ['washing', 'drying', 'ready', 'completed']],
        ['name' => 'Drying', 'icon' => 'fa-water', 'statuses' => ['drying', 'ready', 'completed']],
        ['name' => 'Folding & Packaging', 'icon' => 'fa-shirt', 'statuses' => ['ready', 'completed']],
        ['name' => 'Ready for Release', 'icon' => 'fa-box', 'statuses' => ['completed']],
    ];

    $statusIndex = match($status) {
        'pending' => 0,
        'washing' => 1,
        'drying' => 2,
        'ready' => 3,
        'completed' => 4,
        default => -1,
    };

    $serviceName = $activeBooking?->service?->name ?? 'Laundry Service';

    $bookingDate = $activeBooking?->pickup_date
        ? \Illuminate\Support\Carbon::parse($activeBooking->pickup_date)
        : $activeBooking?->created_at;

    $bookingTime = $activeBooking?->pickup_time
        ? \Illuminate\Support\Carbon::parse($activeBooking->pickup_time)->format('h:i A')
        : null;

    $bookingNumber = $activeBooking
        ? '#LL-' . ($activeBooking->created_at?->format('Y') ?? $today->format('Y')) . '-' . str_pad((string) $activeBooking->id, 3, '0', STR_PAD_LEFT)
        : null;

    $weight = $activeBooking?->weight_kg;
    $totalAmount = $activeBooking?->total_price;

    if (($totalAmount === null || (float) $totalAmount <= 0) && $activeBooking && $activeBooking->service) {
        $servicePrice = $activeBooking->service->price_per_kg
            ?? $activeBooking->service->price
            ?? null;

        if ($servicePrice !== null && $weight !== null) {
            $totalAmount = $servicePrice * $weight;
        }
    }

    $serviceOption = $activeBooking?->service_option ?? 'drop_off';
    $isDelivery = $serviceOption === 'pickup_delivery';

    $hasShowRoute = \Illuminate\Support\Facades\Route::has('customer.bookings.show');
@endphp

<main class="m-0 box-border w-full p-0 font-['DM_Sans'] text-[#29312D] [&_*]:box-border">

    {{-- PAGE HEADING --}}
    <div class="mb-[26px] flex items-center justify-between gap-5 px-0 pb-[5px] pt-[2px] max-[760px]:flex-col max-[760px]:items-start">
        <div>
            <h1 class="mb-2 text-[28px] font-extrabold tracking-[-1px] text-[#252A34] max-[760px]:text-[23px]">
                Good {{ $today->hour < 12 ? 'morning' : ($today->hour < 18 ? 'afternoon' : 'evening') }},
                {{ $firstName }}!
            </h1>

            <p class="m-0 text-[13px] leading-[1.6] text-[#78827B]">
                Here's the status of your laundry orders.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('customer.bookings.create') }}"
               class="inline-flex min-h-[45px] items-center justify-center gap-[9px] rounded-md border border-[#00A878] bg-[#00A878] px-[19px] text-[12px] font-bold text-white no-underline transition-colors duration-200 hover:border-[#008C64] hover:bg-[#008C64]">
                <i class="fa-solid fa-plus"></i>
                New Booking
            </a>
        </div>
    </div>

    {{-- CURRENT ORDER AND LAUNDRY PROGRESS --}}
    <section class="mb-4 overflow-hidden rounded-[9px] border border-[#E1E6E0] bg-white shadow-[0_2px_8px_rgba(37,42,52,0.035)]">

        @if($activeBooking)

            <div class="flex items-center gap-[15px] border-b border-[#E1E6E0] px-[22px] py-5 max-[760px]:flex-wrap max-[760px]:p-[15px]">

                <div class="grid h-[58px] w-[58px] shrink-0 place-items-center rounded-[9px] bg-[#E8F5EF] text-[23px] text-[#008C64] max-[760px]:h-12 max-[760px]:w-12 max-[760px]:text-xl">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>

                <div>
                    <div class="mb-1 text-[11px] text-[#78827B]">
                        Current Order
                    </div>

                    <div class="mb-1.5 text-[19px] font-extrabold max-[760px]:text-base">
                        {{ $bookingNumber }}
                    </div>

                    <div class="text-xs text-[#78827B]">
                        {{ $isDelivery ? 'Pickup & Delivery' : 'Walk-in / Self Pickup' }}

                        <span class="mx-1.5 text-[#AAB3AD]">•</span>

                        {{ $bookingDate?->format('M d, Y') ?? 'Date not available' }}

                        {{ $bookingTime ? ' · ' . $bookingTime : '' }}

                        @if($weight !== null)
                            <span class="mx-1.5 text-[#AAB3AD]">•</span>{{ $weight }} kg
                        @endif
                    </div>
                </div>

                <span class="ml-auto self-start whitespace-nowrap rounded-full border border-[#B8E5D2] bg-[#E8F5EF] px-[13px] py-[7px] text-[11px] font-bold text-[#008C64] max-[760px]:text-[10px]">
                    {{ ucfirst($status ?? 'Pending') }}
                </span>
            </div>

            <div class="px-[22px] pb-5 pt-[18px] max-[760px]:px-3 max-[760px]:py-4">

                <h2 class="mb-[22px] text-[15px] font-extrabold">
                    Laundry Progress
                </h2>

                <div class="relative grid grid-cols-5 gap-1 max-[760px]:grid-cols-1 max-[760px]:gap-0">

                    <div class="absolute left-[10%] right-[10%] top-[22px] h-0.5 bg-[#E1E6E0] max-[760px]:hidden"></div>

                    @php
                        $progressPercent = $statusIndex < 0
                            ? 0
                            : ($statusIndex / 4) * 80;
                    @endphp

                    <div class="absolute left-[10%] top-[22px] z-[1] h-0.5 bg-[#00A878] transition-[width] duration-200 max-[760px]:hidden"
                         style="width: {{ $progressPercent }}%;">
                    </div>

                    @foreach($steps as $index => $step)

                        @php
                            $isDone = $statusIndex >= $index;
                            $isCurrent = $statusIndex === $index;

                            $stepClass = $isDone
                                ? ($isCurrent ? 'current' : 'done')
                                : 'pending';
                        @endphp

                        <div class="relative z-[2] min-w-0 text-center max-[760px]:grid max-[760px]:grid-cols-[42px_1fr] max-[760px]:items-center max-[760px]:gap-x-3 max-[760px]:pb-[14px] max-[760px]:text-left">

                            <div class="mx-auto mb-3 grid h-[46px] w-[46px] place-items-center rounded-full border-[3px] border-white text-[17px] max-[760px]:row-span-3 max-[760px]:m-0 max-[760px]:h-10 max-[760px]:w-10
                                {{ $isDone ? 'bg-[#00A878] text-white' : 'bg-[#EEF0ED] text-[#929D96]' }}">
                                <i class="fa-solid {{ $step['icon'] }}"></i>
                            </div>

                            <div class="min-h-[34px] text-[11px] font-bold leading-[1.5] max-[1050px]:text-[10px] max-[760px]:min-h-0 max-[760px]:text-xs">
                                {{ $step['name'] }}
                            </div>

                            <div class="mt-[3px] min-h-7 text-[10px] leading-[1.5] text-[#78827B] max-[760px]:min-h-0">

                                @if($index === 0 && $bookingDate)
                                    {{ $bookingDate->format('M d, Y') }}
                                @elseif($isCurrent)
                                    In progress
                                @elseif($isDone)
                                    Done
                                @else
                                    Waiting for previous step
                                @endif

                            </div>

                            <div>
                                <span class="mt-[5px] inline-block rounded-full px-[9px] py-[5px] text-[10px]
                                    {{ $isDone
                                        ? ($isCurrent
                                            ? 'bg-[#D9F2E8] text-[#008C64]'
                                            : 'bg-[#E8F5EF] text-[#008C64]')
                                        : 'bg-[#F0F2EF] text-[#78827B]' }}
                                    max-[760px]:mt-1">

                                    {{ $isDone
                                        ? ($isCurrent ? 'In Progress' : 'Completed')
                                        : 'Pending' }}

                                </span>
                            </div>
                        </div>

                    @endforeach

                </div>

                <div class="mt-6 flex items-center gap-[15px] rounded-lg border border-[#D5EDE2] bg-[#F4FAF6] px-[18px] py-[15px] max-[760px]:mt-2 max-[760px]:p-3">

                    <div class="grid h-[46px] w-[46px] shrink-0 place-items-center rounded-[9px] bg-[#E8F5EF] text-xl text-[#008C64]">
                        <i class="fa-regular fa-clock"></i>
                    </div>

                    <div>
                        <div class="mb-[5px] text-[11px] font-bold">
                            Estimated Ready Time
                        </div>

                        <div class="mb-1 text-base font-extrabold">
                            {{ $status === 'completed' ? 'Ready for release' : 'To be confirmed' }}
                        </div>

                        <div class="text-[11px] leading-[1.6] text-[#78827B]">
                            {{ $status === 'completed'
                                ? 'Your order is marked completed. Please check with the laundry shop if you have not collected it yet.'
                                : 'The estimated ready time will be updated when it is confirmed by the laundry staff.' }}
                        </div>
                    </div>
                </div>
            </div>

        @else

            <div class="px-[22px] pb-5 pt-[18px] max-[760px]:px-3 max-[760px]:py-4">

                <h2 class="mb-[22px] text-[15px] font-extrabold">
                    Current Laundry Order
                </h2>

                <div class="px-[15px] pb-8 pt-[38px] text-center text-[#78827B]">

                    <div class="mx-auto mb-[14px] grid h-[58px] w-[58px] place-items-center rounded-[10px] bg-[#E8F5EF] text-2xl text-[#008C64]">
                        <i class="fa-solid fa-shirt"></i>
                    </div>

                    <h3 class="mb-2 text-[15px] text-[#29312D]">
                        No active laundry order
                    </h3>

                    <p class="m-0 text-[11px] leading-[1.7]">
                        Your laundry progress will appear here when you have an active order.
                    </p>

                    <div class="mt-[18px] flex flex-wrap justify-center gap-2.5">

                        <a href="{{ route('customer.bookings.create') }}"
                           class="inline-flex min-h-[45px] items-center justify-center gap-[9px] rounded-md border border-[#00A878] bg-[#00A878] px-[19px] text-[12px] font-bold text-white no-underline transition-colors duration-200 hover:border-[#008C64] hover:bg-[#008C64]">

                            <i class="fa-solid fa-plus"></i>
                            Book a Laundry Service
                        </a>

                    </div>
                </div>
            </div>

        @endif

    </section>

    {{-- PICKUP / DELIVERY AND ORDER DETAILS --}}
    @if($activeBooking)

        <div class="grid grid-cols-2 gap-4 max-[760px]:grid-cols-1 max-[760px]:gap-0">

            <section class="mb-4 overflow-hidden rounded-[9px] border border-[#E1E6E0] bg-white p-5 shadow-[0_2px_8px_rgba(37,42,52,0.035)] max-[760px]:p-4">

                <h2 class="mb-5 flex items-center gap-3 text-sm font-extrabold">
                    <i class="fa-solid fa-truck-fast text-[17px] text-[#008C64]"></i>
                    Pickup or Delivery
                </h2>

                <div class="mt-[9px] flex items-center gap-[13px] rounded-lg border p-[13px]
                    {{ !$isDelivery ? 'border-[#A9DCC5] bg-[#F4FAF6]' : 'border-[#E1E6E0]' }}">

                    <div class="grid h-[43px] w-[43px] shrink-0 place-items-center rounded-lg bg-[#E8F5EF] text-lg text-[#008C64]">
                        <i class="fa-solid fa-store"></i>
                    </div>

                    <div>
                        <div class="mb-[5px] text-xs font-bold">
                            Self Pickup
                        </div>

                        <div class="text-[10px] leading-[1.7] text-[#78827B]">
                            Collect your laundry from the shop once it's ready.
                        </div>
                    </div>

                    @if(!$isDelivery)
                        <i class="fa-solid fa-circle-check ml-auto text-[15px] text-[#00A878]"></i>
                    @endif
                </div>

                <div class="mt-[9px] flex items-center gap-[13px] rounded-lg border p-[13px]
                    {{ $isDelivery ? 'border-[#A9DCC5] bg-[#F4FAF6]' : 'border-[#E1E6E0]' }}">

                    <div class="grid h-[43px] w-[43px] shrink-0 place-items-center rounded-lg bg-[#E8F5EF] text-lg text-[#008C64]">
                        <i class="fa-solid fa-truck"></i>
                    </div>

                    <div>
                        <div class="mb-[5px] text-xs font-bold">
                            Home Delivery
                        </div>

                        <div class="text-[10px] leading-[1.7] text-[#78827B]">
                            Have your laundry delivered to your address.
                        </div>
                    </div>

                    @if($isDelivery)
                        <i class="fa-solid fa-circle-check ml-auto text-[15px] text-[#00A878]"></i>
                    @endif
                </div>

            </section>

            <section class="mb-4 overflow-hidden rounded-[9px] border border-[#E1E6E0] bg-white p-5 shadow-[0_2px_8px_rgba(37,42,52,0.035)] max-[760px]:p-4">

                <h2 class="mb-5 flex items-center gap-3 text-sm font-extrabold">
                    <i class="fa-regular fa-file-lines text-[17px] text-[#008C64]"></i>
                    Order Details
                </h2>

                <div class="flex items-start justify-between gap-[15px] border-b border-[#EDF0EC] py-3 text-[11px]">
                    <span class="text-[#78827B]">Customer</span>
                    <span class="break-words text-right font-bold">
                        {{ $activeBooking->customer_name ?? $customerName }}
                    </span>
                </div>

                <div class="flex items-start justify-between gap-[15px] border-b border-[#EDF0EC] py-3 text-[11px]">
                    <span class="text-[#78827B]">Service Type</span>
                    <span class="break-words text-right font-bold">
                        {{ $serviceName }}
                    </span>
                </div>

                <div class="flex items-start justify-between gap-[15px] border-b border-[#EDF0EC] py-3 text-[11px]">
                    <span class="text-[#78827B]">Total Weight</span>
                    <span class="break-words text-right font-bold">
                        {{ $weight !== null ? $weight . ' kg' : 'Not recorded' }}
                    </span>
                </div>

                <div class="flex items-start justify-between gap-[15px] border-b border-[#EDF0EC] py-3 text-[11px]">
                    <span class="text-[#78827B]">Total Amount</span>
                    <span class="break-words text-right font-bold">
                        {{ $totalAmount !== null
                            ? '₱ ' . number_format((float) $totalAmount, 2)
                            : 'Not recorded' }}
                    </span>
                </div>

                <div class="flex items-start justify-between gap-[15px] border-b border-[#EDF0EC] py-3 text-[11px]">
                    <span class="text-[#78827B]">Payment Status</span>
                    <span class="break-words text-right font-bold">
                        Not recorded
                    </span>
                </div>

                <div class="flex items-start justify-between gap-[15px] py-3 text-[11px]">
                    <span class="text-[#78827B]">Pickup Option</span>
                    <span class="break-words text-right font-bold">
                        {{ $isDelivery ? 'Home Delivery' : 'Self Pickup' }}
                    </span>
                </div>

            </section>

        </div>

    @endif

    {{-- SERVICES --}}
    @php
        try {
            $services = \App\Models\Service::query()->limit(4)->get();
        } catch (\Throwable $e) {
            $services = collect();
        }
    @endphp

    @if($services->isNotEmpty())


    @endif

    {{-- RECENT ORDERS --}}
    <section class="mb-4 overflow-hidden rounded-[9px] border border-[#E1E6E0] bg-white p-5 shadow-[0_2px_8px_rgba(37,42,52,0.035)]">

        <div class="mb-[17px] flex items-center justify-between gap-3">

            <h2 class="m-0 flex items-center gap-3 text-sm font-extrabold">
                <i class="fa-solid fa-list-ul text-[17px] text-[#008C64]"></i>
                Recent Orders
            </h2>

            <a href="{{ route('customer.bookings.index') }}"
               class="text-[11px] font-bold text-[#008C64] no-underline hover:text-[#00A878] hover:underline">
                View All
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[650px] border-collapse">

                <thead>
                    <tr>
                        <th class="bg-[#F6F8F5] px-2.5 py-3 text-left text-[10px] font-bold text-[#68746D]">
                            Order No.
                        </th>

                        <th class="bg-[#F6F8F5] px-2.5 py-3 text-left text-[10px] font-bold text-[#68746D]">
                            Service
                        </th>

                        <th class="bg-[#F6F8F5] px-2.5 py-3 text-left text-[10px] font-bold text-[#68746D]">
                            Date
                        </th>

                        <th class="bg-[#F6F8F5] px-2.5 py-3 text-left text-[10px] font-bold text-[#68746D]">
                            Status
                        </th>

                        <th class="bg-[#F6F8F5] px-2.5 py-3 text-left text-[10px] font-bold text-[#68746D]">
                            Total
                        </th>

                        <th class="bg-[#F6F8F5] px-2.5 py-3 text-left text-[10px] font-bold text-[#68746D]">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($recentBookings as $booking)

                        @php
                            $rowNumber = '#LL-' .
                                ($booking->created_at?->format('Y') ?? $today->format('Y')) .
                                '-' .
                                str_pad((string) $booking->id, 3, '0', STR_PAD_LEFT);

                            $rowAmount = $booking->total_price ?? null;

                            $rowDate = $booking->pickup_date
                                ? \Illuminate\Support\Carbon::parse($booking->pickup_date)
                                : $booking->created_at;

                            if (
                                ($rowAmount === null || (float) $rowAmount <= 0)
                                && $booking->service
                            ) {
                                $rowPrice = $booking->service->price_per_kg
                                    ?? $booking->service->price
                                    ?? null;

                                if ($rowPrice !== null && $booking->weight_kg !== null) {
                                    $rowAmount = $rowPrice * $booking->weight_kg;
                                }
                            }
                        @endphp

                        <tr>

                            <td class="border-t border-[#E1E6E0] px-2.5 py-[13px] text-[11px] font-extrabold">
                                {{ $rowNumber }}
                            </td>

                            <td class="border-t border-[#E1E6E0] px-2.5 py-[13px] text-[11px]">
                                {{ $booking->service?->name ?? 'Laundry Service' }}
                            </td>

                            <td class="border-t border-[#E1E6E0] px-2.5 py-[13px] text-[11px]">
                                {{ $rowDate?->format('M d, Y') ?? '—' }}
                            </td>

                            <td class="border-t border-[#E1E6E0] px-2.5 py-[13px] text-[11px]">
                                <span class="inline-block rounded-full px-2.5 py-[5px] text-[10px] {{ $badgeClass($booking->status) }}">
                                    {{ ucfirst($booking->status ?? 'pending') }}
                                </span>
                            </td>

                            <td class="border-t border-[#E1E6E0] px-2.5 py-[13px] text-[11px]">
                                {{ $rowAmount !== null
                                    ? '₱ ' . number_format((float) $rowAmount, 2)
                                    : '—' }}
                            </td>

                            <td class="border-t border-[#E1E6E0] px-2.5 py-[13px] text-[11px]">

                                @if($hasShowRoute)

                                    <a class="text-[11px] font-bold text-[#008C64] no-underline hover:text-[#00A878] hover:underline"
                                       href="{{ route('customer.bookings.show', $booking->id) }}">
                                        View
                                    </a>

                                @else
                                    —
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="p-[25px] text-center text-[11px] text-[#78827B]">
                                No orders to display yet.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</main>

{{-- AUTO REFRESH --}}
@if($activeBooking && $status !== 'completed')
    <script>
        setTimeout(function () {
            window.location.reload();
        }, 60000);
    </script>
@endif

@endsection
