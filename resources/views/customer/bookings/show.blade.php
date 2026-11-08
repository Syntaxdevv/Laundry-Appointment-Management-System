
@extends('layouts.customer-dashboard')

@section('title', 'Track My Order | LinisLaba')

@section('content')

@php
    $status = strtolower($booking->status ?? 'pending');

    $statusClass = match($status) {
        'washing', 'drying' => 'bg-[#DFF0FF] text-[#1768A5]',
        'ready' => 'bg-[#EAE6FF] text-[#5A4CB0]',
        'completed' => 'bg-[#DDF5E9] text-[#08784F]',
        'cancelled' => 'bg-[#FCE7E7] text-[#B42318]',
        default => 'bg-[#FFF1D4] text-[#916000]',
    };

    $steps = [
        ['title' => 'Booking Received', 'description' => 'Your booking has been submitted.', 'icon' => 'fa-clipboard-check'],
        ['title' => 'Washing', 'description' => 'Your laundry is being washed.', 'icon' => 'fa-soap'],
        ['title' => 'Drying', 'description' => 'Your laundry is being dried.', 'icon' => 'fa-wind'],
        ['title' => 'Folding & Packaging', 'description' => 'Your laundry is being prepared.', 'icon' => 'fa-shirt'],
        ['title' => 'Ready for Release', 'description' => 'Your laundry is ready.', 'icon' => 'fa-box-open'],
    ];

    $statusIndex = match($status) {
        'pending' => 0,
        'washing' => 1,
        'drying' => 2,
        'ready', 'completed' => 4,
        default => -1,
    };

    $pricePerKg = $booking->service->price_per_kg
        ?? $booking->service->price
        ?? null;

    $total = $booking->total_price;

    if ((!$total || $total <= 0) && $pricePerKg && $booking->weight_kg) {
        $total = $pricePerKg * $booking->weight_kg;
    }

    $isDelivery = ($booking->service_option ?? 'drop_off') === 'pickup_delivery';

    $scheduleDate = $booking->pickup_date
        ? \Illuminate\Support\Carbon::parse($booking->pickup_date)->format('F d, Y')
        : 'Not scheduled';

    $scheduleTime = $booking->pickup_time
        ? \Illuminate\Support\Carbon::parse($booking->pickup_time)->format('h:i A')
        : 'Not scheduled';

    $orderNumber = '#LL-' .
        ($booking->created_at?->format('Y') ?? date('Y')) .
        '-' .
        str_pad((string) $booking->id, 3, '0', STR_PAD_LEFT);
@endphp

<div class="min-h-screen bg-[#F3F5F2] p-[34px] font-['DM_Sans',sans-serif] text-[#252A34] max-[950px]:px-[18px] max-[950px]:py-[25px] max-[430px]:px-3 max-[430px]:py-5">

    <div class="mx-auto max-w-[1150px]">

        {{-- Page Heading --}}
        <div class="mb-[27px] flex items-end justify-between gap-[15px] max-[700px]:flex-col max-[700px]:items-start">

            <div>
                <h1 class="mb-1.5 text-[25px] font-extrabold text-[#252A34]">
                    Track Your Order
                </h1>

                <p class="m-0 text-[13px] text-[#849088]">
                    View your laundry status and booking information.
                </p>
            </div>

            <a href="{{ route('customer.bookings.index') }}"
               class="mb-[5px] inline-flex items-center gap-2 text-xs font-bold text-[#008F68] no-underline transition hover:text-[#006F50]">

                <i class="fa-solid fa-arrow-left"></i>
                Back to My Orders
            </a>

        </div>

        {{-- Order Summary --}}
        <div class="mb-[22px] flex items-center gap-5 rounded-xl bg-[#252A34] px-7 py-[25px] text-white max-[700px]:flex-wrap max-[700px]:p-5">

            <div class="grid h-[58px] w-[58px] shrink-0 place-items-center rounded-[11px] bg-[#34423E] text-[23px] text-[#35C99A]">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>

            <div>
                <div class="mb-[5px] text-[11px] text-[#AEBBB5]">
                    ORDER REFERENCE
                </div>

                <div class="text-2xl font-extrabold max-[430px]:text-xl">
                    {{ $orderNumber }}
                </div>

                <div class="mt-[5px] text-[11px] text-[#AEBBB5]">
                    Created {{ $booking->created_at?->format('F d, Y · h:i A') ?? '—' }}
                </div>
            </div>

            <span class="ml-auto whitespace-nowrap rounded-[5px] px-[15px] py-[9px] text-[10px] font-extrabold uppercase tracking-[.5px] max-[700px]:ml-0 {{ $statusClass }}">
                {{ ucwords(str_replace('_', ' ', $status)) }}
            </span>

        </div>

        {{-- TOP SECTION: Appointment + Payment --}}
        <div class="grid grid-cols-2 items-stretch gap-[22px] max-[700px]:grid-cols-1 max-[700px]:gap-0">

            {{-- Appointment --}}
            <section class="mb-[22px] h-[calc(100%-22px)] rounded-[11px] border border-[#E5EAE6] bg-white p-[22px] max-[700px]:h-auto">

                <h2 class="mb-5 flex items-center gap-[10px] text-sm font-extrabold">
                    <i class="fa-solid fa-calendar-days text-[#00A878]"></i>
                    Appointment
                </h2>

                <div class="mb-[10px] rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">

                    <div class="mb-[7px] text-[10px] text-[#87938B]">
                        {{ $isDelivery ? 'PICKUP DATE' : 'DROP-OFF DATE' }}
                    </div>

                    <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                        <i class="fa-regular fa-calendar mr-1.5 text-[#00A878]"></i>
                        {{ $scheduleDate }}
                    </div>

                </div>

                <div class="rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">

                    <div class="mb-[7px] text-[10px] text-[#87938B]">
                        {{ $isDelivery ? 'PICKUP TIME' : 'DROP-OFF TIME' }}
                    </div>

                    <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                        <i class="fa-regular fa-clock mr-1.5 text-[#00A878]"></i>
                        {{ $scheduleTime }}
                    </div>

                </div>

            </section>

            {{-- Payment Overview --}}
            <section class="mb-[22px] h-[calc(100%-22px)] rounded-[11px] border border-[#E5EAE6] bg-white p-[22px] max-[700px]:h-auto">

                <h2 class="mb-5 flex items-center gap-[10px] text-sm font-extrabold">
                    <i class="fa-solid fa-wallet text-[#00A878]"></i>
                    Payment Overview
                </h2>

                <div class="rounded-[9px] border border-[#D6EDE0] bg-[#EAF6F0] p-[19px]">

                    <div class="flex items-center justify-between gap-[10px]">

                        <span class="text-xs font-bold text-[#34483D]">
                            Estimated Total
                        </span>

                        <strong class="whitespace-nowrap text-[25px] font-extrabold text-[#008F68] max-[430px]:text-[21px]">
                            ₱{{ number_format((float) ($total ?? 0), 2) }}
                        </strong>

                    </div>

                    <div class="mt-[7px] text-[10px] leading-relaxed text-[#77847C]">
                        Final cost will be based on the actual laundry weight recorded at the shop.
                    </div>

                </div>

                <div class="flex items-center justify-between gap-[10px] border-b border-[#EDF0ED] py-[13px] text-[11px]">
                    <span class="text-[#849088]">Payment Method</span>
                    <span class="font-bold text-[#303A34]">Pay in Store</span>
                </div>

                <div class="flex items-center justify-between gap-[10px] py-[13px] text-[11px]">
                    <span class="text-[#849088]">Payment Status</span>
                    <span class="font-bold text-[#303A34]">Pay on Visit</span>
                </div>

            </section>

        </div>

        {{-- BOTTOM SECTION --}}
        <div class="grid grid-cols-[310px_minmax(0,1fr)] items-start gap-[22px] max-[950px]:grid-cols-[260px_minmax(0,1fr)] max-[700px]:grid-cols-1">

            {{-- LEFT: Order Journey --}}
            <aside>

                <section class="mb-[18px] rounded-[11px] border border-[#E5EAE6] bg-white p-[22px]">

                    <h2 class="mb-5 flex items-center gap-[10px] text-sm font-extrabold">
                        <i class="fa-solid fa-route text-[#00A878]"></i>
                        Order Journey
                    </h2>

                    @if($status === 'cancelled')

                        <div class="rounded-lg bg-[#FFF1F0] p-[14px] text-xs leading-relaxed text-[#A33A32]">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            This booking has been cancelled.
                        </div>

                    @else

                        <div class="relative pl-1">

                            @foreach($steps as $i => $step)

                                @php
                                    $stepClass = $statusIndex > $i
                                        ? 'done'
                                        : ($statusIndex === $i ? 'current' : 'pending');

                                    if ($status === 'completed') {
                                        $stepClass = 'done';
                                    }

                                    $isDone = $stepClass === 'done';
                                    $isCurrent = $stepClass === 'current';
                                @endphp

                                <div class="relative flex min-h-[76px] gap-[13px] last:min-h-0">

                                    @if(!$loop->last)
                                        <div class="absolute left-[15px] top-[33px] h-[calc(100%-33px)] w-0.5 {{ $isDone ? 'bg-[#00A878]' : 'bg-[#E5EBE7]' }}"></div>
                                    @endif

                                    <div class="relative z-[1] grid h-8 w-8 shrink-0 place-items-center rounded-full text-xs
                                        {{ $isDone || $isCurrent ? 'bg-[#00A878] text-white' : 'bg-[#EDF1EE] text-[#A0AAA4]' }}
                                        {{ $isCurrent ? 'ring-4 ring-[#DDF3E9]' : '' }}">

                                        @if($isDone)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            <i class="fa-solid {{ $step['icon'] }}"></i>
                                        @endif

                                    </div>

                                    <div class="pt-0.5">

                                        <strong class="mb-1 block text-[11px] font-extrabold {{ $stepClass === 'pending' ? 'text-[#9AA59E]' : 'text-[#36443B]' }}">
                                            {{ $step['title'] }}
                                        </strong>

                                        <small class="block text-[10px] leading-relaxed text-[#9AA59E]">
                                            {{ $step['description'] }}
                                        </small>

                                        @if($isCurrent)
                                            <span class="mt-[5px] inline-block rounded bg-[#E2F5EC] px-[7px] py-[3px] text-[9px] font-extrabold text-[#008F68]">
                                                CURRENT STATUS
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endif

                </section>

            </aside>

            {{-- RIGHT: Booking Information --}}
            <main>

                <section class="mb-[18px] rounded-[11px] border border-[#E5EAE6] bg-white p-[22px]">

                    <h2 class="mb-5 flex items-center gap-[10px] text-sm font-extrabold">
                        <i class="fa-solid fa-receipt text-[#00A878]"></i>
                        Booking Information
                    </h2>

                    <div class="grid grid-cols-2 gap-3 max-[950px]:grid-cols-1 max-[700px]:grid-cols-2 max-[430px]:grid-cols-1">

                        <div class="rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">

                            <div class="mb-[7px] text-[10px] text-[#87938B]">
                                CUSTOMER NAME
                            </div>

                            <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                                {{ $booking->customer_name ?? $booking->user->name ?? '—' }}
                            </div>

                        </div>

                        <div class="rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">

                            <div class="mb-[7px] text-[10px] text-[#87938B]">
                                SERVICE TYPE
                            </div>

                            <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                                {{ $booking->service->name ?? 'Laundry Service' }}
                            </div>

                        </div>

                        <div class="rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">

                            <div class="mb-[7px] text-[10px] text-[#87938B]">
                                PRICE RATE
                            </div>

                            <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                                {{ $pricePerKg !== null ? '₱' . number_format((float) $pricePerKg, 2) . ' / kg' : '—' }}
                            </div>

                        </div>

                        <div class="rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">

                            <div class="mb-[7px] text-[10px] text-[#87938B]">
                                ESTIMATED WEIGHT
                            </div>

                            <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                                {{ $booking->weight_kg !== null ? $booking->weight_kg . ' kg' : 'Not recorded' }}
                            </div>

                        </div>

                        <div class="col-span-full rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">

                            <div class="mb-[7px] text-[10px] text-[#87938B]">
                                SERVICE METHOD
                            </div>

                            <div class="break-words text-[13px] font-extrabold text-[#303A34]">

                                <i class="fa-solid {{ $isDelivery ? 'fa-truck' : 'fa-store' }} mr-1.5 text-[#00A878]"></i>

                                {{ $isDelivery ? 'Pick-up & Delivery' : 'Drop-off (Self Pickup)' }}

                            </div>

                        </div>

                    </div>

                </section>

                {{-- ACTIONS --}}
                <div class="mt-[5px] flex flex-wrap gap-[10px]">

                    <a href="{{ route('customer.bookings.index') }}"
                       class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-[7px] border border-[#DCE4DE] bg-white px-[17px] py-3 text-[11px] font-extrabold text-[#34483D] no-underline transition hover:bg-[#F1F5F2] max-[430px]:flex-[1_1_100%]">

                        <i class="fa-solid fa-list"></i>
                        All Orders

                    </a>

                    @if($status === 'pending')

                        <button type="button"
                                id="cancelBookingBtn"
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-[7px] border border-[#F0CECA] bg-white px-[17px] py-3 text-[11px] font-extrabold text-[#B42318] transition hover:bg-[#FFF1F0] max-[430px]:flex-[1_1_100%]">

                            <i class="fa-solid fa-xmark"></i>
                            Cancel Booking

                        </button>

                    @endif

                </div>

            </main>

        </div>

    </div>

</div>

@if($status === 'pending')
<script>
    document.getElementById('cancelBookingBtn').addEventListener('click', function () {
        if (!confirm('Are you sure you want to cancel this booking?')) return;

        fetch("{{ route('customer.bookings.cancel', $booking->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            }
        })
        .then(function(response) {
            if (!response.ok) throw new Error();
            return response.json();
        })
        .then(function() {
            window.location.reload();
        })
        .catch(function() {
            alert('Unable to cancel this booking. Please try again.');
        });
    });
</script>
@endif

@endsection
