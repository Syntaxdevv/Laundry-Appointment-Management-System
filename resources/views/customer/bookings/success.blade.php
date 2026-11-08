
@extends('layouts.customer-dashboard')

@section('title', 'Payment Successful | LinisLaba')

@section('content')

@php
    $orderNumber = '#LL-' .
        ($booking->created_at?->format('Y') ?? date('Y')) .
        '-' . str_pad((string) $booking->id, 3, '0', STR_PAD_LEFT);

    $total = number_format((float) $booking->total_price, 2, '.', ',');
    $serviceName = $booking->service?->name ?? 'Laundry Service';

    $captureId = $booking->paypal_capture_id ?? null;
    $paidAt = $booking->paid_at
        ? \Carbon\Carbon::parse($booking->paid_at)
        : null;
@endphp

<div class="mx-auto max-w-[1050px] px-5 pb-[55px] pt-[35px] font-['DM_Sans',sans-serif] text-[#252A34]">

    <div id="paymentReceipt"
         class="mx-auto my-[25px] max-w-[760px] rounded-xl border border-[#E1E6E0] bg-white px-[55px] py-[45px] text-center max-[600px]:px-[19px] max-[600px]:py-8">

        {{-- Success Icon --}}
        <div class="mx-auto mb-[22px] flex h-[82px] w-[82px] items-center justify-center rounded-full bg-[#E8F5EF] text-[39px] text-[#00A878]">
            <i class="fas fa-check"></i>
        </div>

        {{-- Heading --}}
        <h1 class="mb-[10px] text-[29px] font-extrabold max-[600px]:text-2xl">
            Payment Successful!
        </h1>

        <p class="mb-[30px] text-sm leading-[1.7] text-[#78827B]">
            Thank you for choosing LinisLaba. Your PayPal payment
            has been successfully verified and your laundry booking
            is now confirmed.
        </p>

        {{-- Payment Status --}}
        <span class="mb-[30px] inline-block rounded-full bg-[#E8F5EF] px-[19px] py-2 text-xs font-extrabold text-[#008C64]">
            <i class="fas fa-check-circle"></i>
            PAYMENT PAID
        </span>

        {{-- Payment Details --}}
        <div class="mb-[27px] overflow-hidden rounded-[9px] border border-[#E1E6E0] text-left">

            <div class="border-b border-[#E1E6E0] bg-[#F7FAF8] px-[21px] py-[17px] text-sm font-extrabold">
                <i class="fas fa-receipt"></i>
                Payment Summary
            </div>

            {{-- Booking Number --}}
            <div class="flex justify-between gap-[15px] border-b border-[#EDF0ED] px-[21px] py-[15px] text-[13px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Booking Number</span>
                <span class="break-all text-right font-bold">{{ $orderNumber }}</span>
            </div>

            {{-- Customer Name --}}
            <div class="flex justify-between gap-[15px] border-b border-[#EDF0ED] px-[21px] py-[15px] text-[13px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Customer Name</span>
                <span class="break-words text-right font-bold">
                    {{ $booking->customer_name ?? auth()->user()->name }}
                </span>
            </div>

            {{-- Service --}}
            <div class="flex justify-between gap-[15px] border-b border-[#EDF0ED] px-[21px] py-[15px] text-[13px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Service</span>
                <span class="break-words text-right font-bold">{{ $serviceName }}</span>
            </div>

            {{-- Weight --}}
            <div class="flex justify-between gap-[15px] border-b border-[#EDF0ED] px-[21px] py-[15px] text-[13px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Weight</span>
                <span class="text-right font-bold">
                    {{ number_format((float) $booking->weight_kg, 2) }} kg
                </span>
            </div>

            {{-- Payment Method --}}
            <div class="flex justify-between gap-[15px] border-b border-[#EDF0ED] px-[21px] py-[15px] text-[13px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Payment Method</span>
                <span class="text-right font-bold">PayPal</span>
            </div>

            {{-- Payment Status --}}
            <div class="flex justify-between gap-[15px] border-b border-[#EDF0ED] px-[21px] py-[15px] text-[13px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Payment Status</span>
                <span class="text-right font-bold text-[#008C64]">Paid</span>
            </div>

            {{-- Transaction ID --}}
            <div class="flex justify-between gap-[15px] border-b border-[#EDF0ED] px-[21px] py-[15px] text-[13px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Transaction ID</span>
                <span class="break-all text-right font-bold">
                    {{ $captureId ?: 'Verified by PayPal' }}
                </span>
            </div>

            {{-- Payment Date --}}
            <div class="flex justify-between gap-[15px] border-b border-[#EDF0ED] px-[21px] py-[15px] text-[13px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Payment Date</span>
                <span class="text-right font-bold">
                    {{ $paidAt?->format('M d, Y h:i A') ?? 'Verified' }}
                </span>
            </div>

            {{-- Total Paid --}}
            <div class="flex justify-between gap-[15px] bg-[#F7FAF8] px-[21px] py-[15px] text-[15px] max-[600px]:gap-[10px] max-[600px]:px-[13px] max-[600px]:py-[13px]">
                <span class="shrink-0 text-[#78827B]">Total Paid</span>
                <span class="text-right text-[19px] font-extrabold text-[#008C64]">
                    ₱{{ $total }}
                </span>
            </div>

        </div>

        {{-- Success Note --}}
        <div class="mb-[27px] border-l-4 border-[#00A878] bg-[#F0F8F3] px-[17px] py-[15px] text-left text-xs leading-[1.7] text-[#53645A] print:hidden">
            <i class="fas fa-info-circle"></i>
            Your payment has been verified by PayPal. You can track
            your laundry booking through your customer dashboard.
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap justify-center gap-3 print:hidden">

            <button type="button"
                    class="inline-flex min-h-[46px] cursor-pointer items-center justify-center gap-[9px] rounded-md border border-[#DCE4DD] bg-white px-[23px] text-[13px] font-bold text-[#37443B] transition hover:bg-[#F5F8F5]"
                    onclick="window.print()">

                <i class="fas fa-print"></i>
                Print Receipt

            </button>

            <a href="{{ route('customer.dashboard') }}"
               class="inline-flex min-h-[46px] items-center justify-center gap-[9px] rounded-md border border-[#00A878] bg-[#00A878] px-[23px] text-[13px] font-bold text-white no-underline transition hover:border-[#008C64] hover:bg-[#008C64]">

                <i class="fas fa-home"></i>
                Back to Dashboard

            </a>

            <a href="{{ route('customer.bookings.show', $booking->id) }}"
               class="inline-flex min-h-[46px] items-center justify-center gap-[9px] rounded-md border border-[#DCE4DD] bg-white px-[23px] text-[13px] font-bold text-[#37443B] no-underline transition hover:bg-[#F5F8F5]">

                <i class="fas fa-box"></i>
                View Booking

            </a>

        </div>

    </div>

</div>

{{-- Print Styling --}}
<style>
    @media print {
        @page {
            margin: 12mm;
        }

        body * {
            visibility: hidden !important;
        }

        #paymentReceipt,
        #paymentReceipt * {
            visibility: visible !important;
        }

        #paymentReceipt {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 25px;
            border: 0;
            border-radius: 0;
        }

        #paymentReceipt .print\:hidden {
            display: none !important;
        }

        #paymentReceipt > div:nth-of-type(3),
        #paymentReceipt > div:nth-of-type(2) {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

@endsection
