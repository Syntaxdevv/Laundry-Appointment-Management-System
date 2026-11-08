@extends('layouts.customer-dashboard')

@section('title', 'My Orders | LinisLaba')
@section('page-heading', '')

@section('content')

@php
    $orders = $bookings ?? collect();

    $activeCount = $orders->filter(function ($order) {
        return in_array(strtolower($order->status ?? ''), [
            'pending',
            'washing',
            'drying',
            'ready',
            'ready for pickup',
            'in progress',
            'in_progress',
        ]);
    })->count();

    $completedCount = $orders->filter(function ($order) {
        return strtolower($order->status ?? '') === 'completed';
    })->count();
@endphp

<div class="ll-orders-content box-border font-['DM_Sans'] text-[#29312D]">

    {{-- PAGE HEADER --}}
    <header class="mb-[25px] flex items-center justify-between gap-5 max-[600px]:flex-col max-[600px]:items-start max-[600px]:gap-3">

        <div>
            <h1 class="mb-[7px] text-[26px] font-extrabold tracking-[-.5px] text-[#252A34] max-[600px]:text-[23px]">
                My Orders
            </h1>

            <p class="m-0 text-[13px] leading-[1.7] text-[#78827B]">
                Track your laundry bookings and check their current status.
            </p>
        </div>

        <div class="whitespace-nowrap rounded-[7px] border border-[#DCEAE1] bg-[#E8F5EF] px-[15px] py-[10px] text-[12px] font-bold text-[#008C64]">
            <i class="fa-solid fa-receipt"></i>
            {{ $orders->count() }}
            {{ $orders->count() === 1 ? 'Order' : 'Orders' }}
        </div>

    </header>

    {{-- SUMMARY CARDS --}}
    <section class="mb-[23px] grid grid-cols-3 gap-4 max-[900px]:grid-cols-2 max-[600px]:grid-cols-1 max-[600px]:gap-[10px]">

        {{-- TOTAL ORDERS --}}
        <div class="flex min-w-0 items-center gap-[15px] rounded-[9px] border border-[#E1E6E0] bg-white p-5 max-[600px]:p-4">

            <div class="grid h-[45px] w-[45px] shrink-0 place-items-center rounded-[8px] bg-[#E8F5EF] text-[17px] text-[#008C64]">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>

            <div>
                <span class="block text-[12px] font-semibold text-[#78827B]">
                    Total Orders
                </span>

                <strong class="mt-[6px] block text-[24px] font-extrabold text-[#252A34]">
                    {{ $orders->count() }}
                </strong>
            </div>

        </div>

        {{-- IN PROGRESS --}}
        <div class="flex min-w-0 items-center gap-[15px] rounded-[9px] border border-[#E1E6E0] bg-white p-5 max-[600px]:p-4">

            <div class="grid h-[45px] w-[45px] shrink-0 place-items-center rounded-[8px] bg-[#FFF4DF] text-[17px] text-[#A86A00]">
                <i class="fa-solid fa-spinner"></i>
            </div>

            <div>
                <span class="block text-[12px] font-semibold text-[#78827B]">
                    In Progress
                </span>

                <strong class="mt-[6px] block text-[24px] font-extrabold text-[#252A34]">
                    {{ $activeCount }}
                </strong>
            </div>

        </div>

        {{-- COMPLETED --}}
        <div class="flex min-w-0 items-center gap-[15px] rounded-[9px] border border-[#E1E6E0] bg-white p-5 max-[900px]:col-span-2 max-[600px]:col-span-auto max-[600px]:p-4">

            <div class="grid h-[45px] w-[45px] shrink-0 place-items-center rounded-[8px] bg-[#E8F5EF] text-[17px] text-[#008C64]">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div>
                <span class="block text-[12px] font-semibold text-[#78827B]">
                    Completed
                </span>

                <strong class="mt-[6px] block text-[24px] font-extrabold text-[#252A34]">
                    {{ $completedCount }}
                </strong>
            </div>

        </div>

    </section>

    {{-- ORDER HISTORY --}}
    <section class="overflow-hidden rounded-[9px] border border-[#E1E6E0] bg-white">

        <div class="flex items-center justify-between gap-3 border-b border-[#E1E6E0] px-[22px] py-5 max-[600px]:flex-col max-[600px]:items-start max-[600px]:px-[17px]">

            <h2 class="m-0 text-[15px] font-extrabold text-[#252A34]">
                <i class="fa-solid fa-clock-rotate-left"></i>
                &nbsp; Order History
            </h2>

            <span class="text-[11px] text-[#78827B]">
                Your laundry activity
            </span>

        </div>

        @if($orders->isNotEmpty())

            {{-- TABLE --}}
            <div class="w-full overflow-x-auto">

                <table class="w-full border-collapse text-left whitespace-nowrap">

                    <thead>
                        <tr class="bg-[#F7F9F7]">

                            <th class="px-[17px] py-[14px] text-[10px] font-extrabold uppercase tracking-[.6px] text-[#78827B]">
                                Order ID
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-extrabold uppercase tracking-[.6px] text-[#78827B]">
                                Customer
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-extrabold uppercase tracking-[.6px] text-[#78827B]">
                                Service
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-extrabold uppercase tracking-[.6px] text-[#78827B]">
                                Weight
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-extrabold uppercase tracking-[.6px] text-[#78827B]">
                                Total
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-extrabold uppercase tracking-[.6px] text-[#78827B]">
                                Status
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-extrabold uppercase tracking-[.6px] text-[#78827B]">
                                Schedule
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-extrabold uppercase tracking-[.6px] text-[#78827B]">
                                Action
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach($orders as $order)

                            @php
                                $status = strtolower($order->status ?? 'pending');

                                $statusClass = match($status) {
                                    'pending' => 'bg-[#FFF4DF] text-[#A86A00]',
                                    'washing', 'drying', 'in progress', 'in_progress' => 'bg-[#EAF1FF] text-[#3566B8]',
                                    'ready', 'ready for pickup', 'completed' => 'bg-[#E8F5EF] text-[#008C64]',
                                    'cancelled' => 'bg-[#FDECEC] text-[#C03939]',
                                    default => 'bg-[#F0F2F0] text-[#68736B]',
                                };

                                $rowTotal = $order->total_price;

                                if (
                                    (!$rowTotal || $rowTotal <= 0)
                                    && $order->service
                                    && $order->weight_kg
                                ) {
                                    $rowTotal =
                                        ($order->service->price_per_kg
                                        ?? $order->service->price
                                        ?? 0) * $order->weight_kg;
                                }
                            @endphp

                            <tr class="transition hover:bg-[#F8FBF9]">

                                {{-- ORDER ID --}}
                                <td class="border-b border-[#EDF0EC] px-[17px] py-[17px] text-[12px] align-middle">
                                    <span class="font-extrabold text-[#008C64]">
                                        #{{ $order->id }}
                                    </span>
                                </td>

                                {{-- CUSTOMER --}}
                                <td class="border-b border-[#EDF0EC] px-[17px] py-[17px] text-[12px] align-middle">
                                    <span class="font-bold text-[#29312D]">
                                        {{ $order->customer_name ?? '—' }}
                                    </span>
                                </td>

                                {{-- SERVICE --}}
                                <td class="border-b border-[#EDF0EC] px-[17px] py-[17px] text-[12px] align-middle">
                                    <span class="font-bold text-[#29312D]">
                                        {{ $order->service->name ?? 'Laundry Service' }}
                                    </span>
                                </td>

                                {{-- WEIGHT --}}
                                <td class="border-b border-[#EDF0EC] px-[17px] py-[17px] text-[12px] text-[#56615A] align-middle">
                                    {{ $order->weight_kg !== null
                                        ? $order->weight_kg . ' kg'
                                        : '—' }}
                                </td>

                                {{-- TOTAL --}}
                                <td class="border-b border-[#EDF0EC] px-[17px] py-[17px] text-[12px] align-middle">
                                    <strong class="text-[#29312D]">
                                        ₱{{ number_format((float) ($rowTotal ?? 0), 2) }}
                                    </strong>
                                </td>

                                {{-- STATUS --}}
                                <td class="border-b border-[#EDF0EC] px-[17px] py-[17px] text-[12px] align-middle">

                                    <span class="inline-flex items-center gap-[6px] rounded-[5px] px-[9px] py-[6px] text-[10px] font-extrabold {{ $statusClass }}">

                                        <span class="h-[6px] w-[6px] shrink-0 rounded-full bg-current"></span>

                                        {{ ucwords(str_replace('_', ' ', $status)) }}

                                    </span>

                                </td>

                                {{-- SCHEDULE --}}
                                <td class="border-b border-[#EDF0EC] px-[17px] py-[17px] text-[12px] text-[#56615A] align-middle">

                                    @if($order->pickup_date)

                                        <div>
                                            {{ \Illuminate\Support\Carbon::parse($order->pickup_date)->format('M d, Y') }}
                                        </div>

                                        <div class="mt-[5px] text-[11px] text-[#929B94]">

                                            {{ $order->pickup_time
                                                ? \Illuminate\Support\Carbon::parse($order->pickup_time)->format('h:i A')
                                                : '' }}

                                        </div>

                                    @else

                                        <div>
                                            {{ $order->created_at?->format('M d, Y') ?? '—' }}
                                        </div>

                                        <div class="mt-[5px] text-[11px] text-[#929B94]">
                                            {{ $order->created_at?->format('h:i A') ?? '' }}
                                        </div>

                                    @endif

                                </td>

                                {{-- ACTION --}}
                                <td class="border-b border-[#EDF0EC] px-[17px] py-[17px] text-[12px] align-middle">

                                    @if(\Illuminate\Support\Facades\Route::has('customer.bookings.show'))

                                        <a href="{{ route('customer.bookings.show', $order->id) }}"
                                           class="inline-flex items-center justify-center gap-[7px] rounded-[6px] border border-[#CFE5D8] bg-[#E8F5EF] px-[11px] py-2 text-[11px] font-extrabold text-[#008C64] no-underline transition hover:border-[#008C64] hover:bg-[#008C64] hover:text-white">

                                            <i class="fa-regular fa-eye"></i>
                                            View

                                        </a>

                                    @else

                                        <span class="text-[#929B94]">—</span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- EMPTY STATE --}}
            <div class="px-[22px] py-[55px] text-center max-[600px]:px-4 max-[600px]:py-10">

                <div class="mx-auto mb-[18px] grid h-[65px] w-[65px] place-items-center rounded-[10px] bg-[#E8F5EF] text-[25px] text-[#008C64]">
                    <i class="fa-solid fa-shirt"></i>
                </div>

                <h3 class="mb-[9px] text-[17px] font-extrabold text-[#252A34]">
                    No orders yet
                </h3>

                <p class="mx-auto max-w-[390px] text-[12px] leading-[1.8] text-[#78827B]">
                    Your laundry bookings will appear here after you make your first reservation.
                    You can track their status and review their details on this page.
                </p>

                <a href="{{ route('customer.bookings.create') }}"
                   class="mt-5 inline-flex items-center justify-center gap-2 rounded-[6px] bg-[#008C64] px-4 py-[11px] text-[12px] font-bold text-white no-underline transition hover:bg-[#007653]">

                    <i class="fa-solid fa-plus"></i>
                    Create a Booking

                </a>

            </div>

        @endif

    </section>

    {{-- FOOTER --}}
    <footer class="mt-5 text-center text-[11px] text-[#929B94]">
        © {{ date('Y') }} LinisLaba. Laundry made simple.
    </footer>

</div>

@endsection