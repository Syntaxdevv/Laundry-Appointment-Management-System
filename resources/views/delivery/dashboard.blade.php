
@extends('layouts.delivery')

@section('title', 'Delivery Dashboard')
@section('page-heading', '')

@section('content')

<div class="w-full min-w-0 space-y-6 text-[#252A34]">

    {{-- Welcome Section --}}
    <div class="flex items-center justify-between gap-5 rounded-xl border border-[#E1E6E0] bg-white p-6">

        <div>
            <span class="mb-2 inline-flex items-center gap-2 rounded-md bg-[#E8F5EF] px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-[#008C64]">
                <i class="fas fa-truck"></i>
                Delivery Personnel
            </span>

            <h2 class="text-[25px] font-extrabold tracking-tight text-[#252A34]">
                Welcome back, {{ auth()->user()->name }}!
            </h2>

            <p class="mt-1.5 text-sm text-[#78827B]">
                Here's an overview of your assigned delivery activities.
            </p>

            <div class="mt-4 flex items-center gap-2 text-xs text-[#78827B]">
                <i class="far fa-calendar text-[#00A878]"></i>
                {{ now()->format('l, F d, Y') }}
            </div>
        </div>

        <div class="hidden h-[68px] w-[68px] shrink-0 items-center justify-center rounded-xl bg-[#E8F5EF] text-[27px] text-[#00A878] sm:flex">
            <i class="fas fa-shipping-fast"></i>
        </div>

    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-4 gap-4 max-[1100px]:grid-cols-2 max-[600px]:gap-2.5">

        {{-- Total Deliveries --}}
        <div class="rounded-xl border border-[#E1E6E0] bg-white p-5 max-[600px]:p-3.5">

            <div class="mb-5 flex items-center justify-between gap-2">
                <span class="text-[13px] font-medium text-[#78827B] max-[600px]:text-xs">
                    Total Deliveries
                </span>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#E8F5EF] text-[#00A878] max-[600px]:h-8 max-[600px]:w-8">
                    <i class="fas fa-box"></i>
                </div>
            </div>

            <div class="text-[29px] font-extrabold leading-none text-[#252A34] max-[600px]:text-2xl">
                {{ $stats['total'] }}
            </div>

            <p class="mt-2 text-xs text-[#9AA39D]">
                All assigned orders
            </p>

        </div>

        {{-- Pending --}}
        <div class="rounded-xl border border-[#E1E6E0] bg-white p-5 max-[600px]:p-3.5">

            <div class="mb-5 flex items-center justify-between gap-2">
                <span class="text-[13px] font-medium text-[#78827B] max-[600px]:text-xs">
                    Pending
                </span>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#FFF4D9] text-[#A66B00] max-[600px]:h-8 max-[600px]:w-8">
                    <i class="fas fa-clock"></i>
                </div>
            </div>

            <div class="text-[29px] font-extrabold leading-none text-[#252A34] max-[600px]:text-2xl">
                {{ $stats['pending'] }}
            </div>

            <p class="mt-2 text-xs text-[#9AA39D]">
                Waiting for delivery
            </p>

        </div>

        {{-- Out for Delivery --}}
        <div class="rounded-xl border border-[#E1E6E0] bg-white p-5 max-[600px]:p-3.5">

            <div class="mb-5 flex items-center justify-between gap-2">
                <span class="text-[13px] font-medium text-[#78827B] max-[600px]:text-xs">
                    Out for Delivery
                </span>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#E8F0FF] text-[#3565B8] max-[600px]:h-8 max-[600px]:w-8">
                    <i class="fas fa-shipping-fast"></i>
                </div>
            </div>

            <div class="text-[29px] font-extrabold leading-none text-[#252A34] max-[600px]:text-2xl">
                {{ $stats['out_for_delivery'] }}
            </div>

            <p class="mt-2 text-xs text-[#9AA39D]">
                Currently in transit
            </p>

        </div>

        {{-- Delivered --}}
        <div class="rounded-xl border border-[#E1E6E0] bg-white p-5 max-[600px]:p-3.5">

            <div class="mb-5 flex items-center justify-between gap-2">
                <span class="text-[13px] font-medium text-[#78827B] max-[600px]:text-xs">
                    Delivered
                </span>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#E5F5EC] text-[#16834D] max-[600px]:h-8 max-[600px]:w-8">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>

            <div class="text-[29px] font-extrabold leading-none text-[#252A34] max-[600px]:text-2xl">
                {{ $stats['delivered'] }}
            </div>

            <p class="mt-2 text-xs text-[#9AA39D]">
                Successfully delivered
            </p>

        </div>

    </div>

    {{-- My Deliveries --}}
    <div class="overflow-hidden rounded-xl border border-[#E1E6E0] bg-white">

        {{-- Panel Header --}}
        <div class="flex items-center justify-between gap-4 border-b border-[#E1E6E0] px-6 py-5 max-[600px]:px-4">

            <div>
                <h3 class="text-base font-extrabold text-[#252A34]">
                    My Deliveries
                </h3>

                <p class="mt-1 text-xs text-[#78827B]">
                    Orders assigned to your account.
                </p>
            </div>

            <span class="whitespace-nowrap rounded-md bg-[#E8F5EF] px-3 py-2 text-xs font-bold text-[#008C64]">
                {{ $deliveries->count() }} Orders
            </span>

        </div>

        @if($deliveries->isNotEmpty())

            <div class="w-full overflow-x-auto">

                <table class="w-full min-w-[750px] border-collapse text-left">

                    <thead class="bg-[#F8FAF8]">

                        <tr>
                            <th class="border-b border-[#E1E6E0] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#78827B]">
                                Order ID
                            </th>

                            <th class="border-b border-[#E1E6E0] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#78827B]">
                                Customer
                            </th>

                            <th class="border-b border-[#E1E6E0] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#78827B]">
                                Service
                            </th>

                            <th class="border-b border-[#E1E6E0] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#78827B]">
                                Schedule
                            </th>

                            <th class="border-b border-[#E1E6E0] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#78827B]">
                                Status
                            </th>

                            <th class="border-b border-[#E1E6E0] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#78827B]">
                                Action
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($deliveries as $delivery)

                            @php
                                $status = $delivery->delivery_status;

                                $statusClass = match($status) {
                                    'pending' => 'bg-[#FFF4D9] text-[#A66B00]',
                                    'out_for_delivery' => 'bg-[#E8F0FF] text-[#3565B8]',
                                    'delivered' => 'bg-[#E5F5EC] text-[#16834D]',
                                    default => 'bg-[#F1F3F2] text-[#66736A]',
                                };
                            @endphp

                            <tr class="transition hover:bg-[#FAFCFA]">

                                {{-- Order ID --}}
                                <td class="border-b border-[#EEF1ED] px-5 py-[17px] text-[13px]">
                                    <span class="font-bold text-[#008C64]">
                                        #{{ $delivery->id }}
                                    </span>
                                </td>

                                {{-- Customer --}}
                                <td class="border-b border-[#EEF1ED] px-5 py-[17px]">

                                    <div class="mb-1 text-[13px] font-semibold text-[#303A34]">
                                        {{ $delivery->customer_name ?? $delivery->user?->name ?? 'Customer' }}
                                    </div>

                                    <div class="text-[11px] text-[#9AA39D]">
                                        {{ $delivery->user?->email ?? 'No email available' }}
                                    </div>

                                </td>

                                {{-- Service --}}
                                <td class="border-b border-[#EEF1ED] px-5 py-[17px] text-[13px] text-[#4D5B52]">
                                    {{ $delivery->service?->name ?? 'Laundry Service' }}
                                </td>

                                {{-- Schedule --}}
                                <td class="border-b border-[#EEF1ED] px-5 py-[17px]">

                                    <div class="text-[13px] font-medium text-[#303A34]">
                                        {{ $delivery->pickup_date ?? 'Not scheduled' }}
                                    </div>

                                    <div class="mt-1 text-[11px] text-[#9AA39D]">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $delivery->pickup_time ?? '' }}
                                    </div>

                                </td>

                                {{-- Status --}}
                                <td class="border-b border-[#EEF1ED] px-5 py-[17px]">

                                    <span class="inline-flex whitespace-nowrap rounded-md px-[10px] py-[7px] text-[11px] font-bold {{ $statusClass }}">
                                        {{ ucwords(str_replace('_', ' ', $status ?? 'Unknown')) }}
                                    </span>

                                </td>

                                {{-- Action --}}
                                <td class="border-b border-[#EEF1ED] px-5 py-[17px]">

                                    <a href="{{ route('delivery.deliveries.show', $delivery->id) }}"
                                       class="inline-flex items-center gap-2 rounded-md border border-[#00A878] px-3 py-[7px] text-xs font-semibold text-[#008C64] no-underline transition hover:bg-[#00A878] hover:text-white">

                                        View Details
                                        <i class="fas fa-arrow-right text-[10px]"></i>

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- Empty State --}}
            <div class="px-5 py-[55px] text-center">

                <div class="mx-auto mb-[17px] flex h-[65px] w-[65px] items-center justify-center rounded-[18px] bg-[#F1F5F1] text-[25px] text-[#A8B5AC]">
                    <i class="fas fa-box-open"></i>
                </div>

                <h4 class="text-[15px] font-bold text-[#303A34]">
                    No Assigned Deliveries
                </h4>

                <p class="mx-auto mt-2 max-w-md text-[13px] leading-relaxed text-[#78827B]">
                    You don't have any completed pickup and delivery
                    bookings assigned to you yet.
                </p>

            </div>

        @endif

    </div>

    {{-- Delivery Reminders --}}
    <div class="overflow-hidden rounded-xl border border-[#E1E6E0] bg-white">

        <div class="border-b border-[#E1E6E0] px-6 py-5 max-[600px]:px-4">

            <h3 class="text-base font-extrabold text-[#252A34]">
                Delivery Reminders
            </h3>

            <p class="mt-1 text-xs text-[#78827B]">
                Important reminders for delivery personnel.
            </p>

        </div>

        <div class="flex gap-[13px] px-6 py-5 max-[600px]:px-4">

            <div class="flex h-9 w-9 min-w-9 items-center justify-center rounded-lg bg-[#E8F5EF] text-[#00A878]">
                <i class="fas fa-info-circle"></i>
            </div>

            <div>
                <p class="text-[13px] leading-[1.7] text-[#78827B]">
                    Check the customer and delivery address before leaving.
                    Update the delivery status when the order is out for
                    delivery and after it has been delivered.
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
