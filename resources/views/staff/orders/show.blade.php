@extends('layouts.staff')

@section('title', 'Order Details')
@section('page-heading', '')

@section('content')
@php
    $rawStatus = strtolower(str_replace(' ', '-', $booking->status ?? 'pending'));

    $knownStatuses = [
        'pending', 'washing', 'drying', 'ready', 'folding',
        'processing', 'in-progress', 'in_progress',
        'completed', 'cancelled',
    ];

    $statusClass = in_array($rawStatus, $knownStatuses, true)
        ? $rawStatus
        : 'default';

    $customerName = $booking->customer_name
        ?: ($booking->user?->name ?? 'Walk-in Customer');

    $customerEmail = $booking->user?->email ?? 'Walk-in order';
    $serviceName = $booking->service?->name ?? 'Laundry Service';

    $serviceOption = $booking->service_option
        ? ucfirst(str_replace('_', ' ', $booking->service_option))
        : 'Not specified';

    $pickupDate = $booking->pickup_date
        ? \Illuminate\Support\Carbon::parse($booking->pickup_date)->format('M d, Y')
        : 'Not specified';

    $pickupTime = $booking->pickup_time
        ? \Illuminate\Support\Carbon::parse($booking->pickup_time)->format('h:i A')
        : 'Not specified';

    $statusLabel = ucfirst(str_replace(
        ['-', '_'],
        ' ',
        $booking->status ?? 'pending'
    ));

    $statusColors = [
        'pending' => 'bg-[#FFF5E8] text-[#C47A18]',
        'washing' => 'bg-[#EAF2FF] text-[#3975C6]',
        'drying' => 'bg-[#F2ECFF] text-[#8055C7]',
        'ready' => 'bg-[#E5F7F2] text-[#16866A]',
        'folding' => 'bg-[#EAF2FF] text-[#3975C6]',
        'processing' => 'bg-[#EAF2FF] text-[#3975C6]',
        'in-progress' => 'bg-[#EAF2FF] text-[#3975C6]',
        'in_progress' => 'bg-[#EAF2FF] text-[#3975C6]',
        'completed' => 'bg-[#E6F6EC] text-[#26834C]',
        'cancelled' => 'bg-[#FDECEC] text-[#C94D4D]',
    ];

    $statusColor = $statusColors[$statusClass] ?? 'bg-[#F0F1F2] text-[#68717D]';
@endphp

<div class="w-full space-y-6 font-['DM_Sans'] text-[#29312D]">

    <div class="flex flex-wrap items-start justify-between gap-4">

        <div>
            <p class="mb-2 text-[12px] font-medium text-[#89938E]">
                Staff Workspace / Orders / Details
            </p>

            <h1 class="mb-1 text-[24px] font-bold tracking-tight text-[#26374A]">
                Order Details
            </h1>

            <p class="m-0 text-[13px] text-[#87918B]">
                Review the booking, customer information, and order total.
            </p>
        </div>

        <a href="{{ route('staff.orders.index') }}"
           class="inline-flex h-[40px] shrink-0 items-center gap-2 rounded-md
                  border border-[#DDE4DF] bg-white px-4 text-[12px]
                  font-semibold text-[#526158] no-underline
                  transition-colors hover:border-[#00A878]
                  hover:bg-[#F0FAF6] hover:text-[#008D65]">

            <i class="fas fa-arrow-left"></i>
            Back to Orders
        </a>

    </div>

   
    @if(session('success'))
        <div class="flex items-center gap-2 rounded-md border border-[#BFE8D3]
                    bg-[#ECF9F2] px-4 py-3 text-[13px] text-[#218452]">

            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="flex items-center gap-2 rounded-md border border-[#F3CACA]
                    bg-[#FFF0F0] px-4 py-3 text-[13px] text-[#C64B4B]">

            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

  
    <div class="grid grid-cols-1 items-start gap-5 xl:grid-cols-[minmax(0,1fr)_320px]">

        {{-- MAIN COLUMN --}}
        <div class="min-w-0 space-y-5">

      
            <section class="overflow-hidden rounded-[9px] border border-[#DFE6E1]
                            bg-white">

                <div class="flex flex-wrap items-center justify-between gap-3
                            border-b border-[#E9EDEA] px-5 py-4">

                    <div>
                        <span class="mb-1 block text-[9px] font-bold
                                     tracking-[1px] text-[#929C96]">
                            BOOKING REFERENCE
                        </span>

                        <span class="text-[17px] font-bold text-[#26374A]">
                            #{{ $booking->id }}
                        </span>
                    </div>

                    <span class="inline-flex items-center rounded-full px-3 py-1.5
                                 text-[10px] font-bold {{ $statusColor }}">

                        <span class="mr-1.5 h-[6px] w-[6px] rounded-full bg-current"></span>
                        {{ $statusLabel }}
                    </span>

                </div>

                <div class="p-5">

                    <div class="grid grid-cols-1 gap-x-6 gap-y-6
                                sm:grid-cols-2">

                      
                        <div>
                            <span class="mb-1.5 block text-[11px] font-medium
                                         text-[#89938E]">
                                Customer Name
                            </span>

                            <div class="break-words text-[13px] font-semibold
                                        text-[#34443B]">
                                {{ $customerName }}
                            </div>
                        </div>

                        {{-- EMAIL ADDRESS --}}
                        <div>
                            <span class="mb-1.5 block text-[11px] font-medium
                                         text-[#89938E]">
                                Email Address
                            </span>

                            <div class="break-words text-[13px] font-semibold
                                        text-[#34443B]">
                                {{ $customerEmail }}
                            </div>
                        </div>

                  
                        <div>
                            <span class="mb-1.5 block text-[11px] font-medium
                                         text-[#89938E]">
                                Laundry Service
                            </span>

                            <div class="text-[13px] font-semibold text-[#34443B]">
                                {{ $serviceName }}
                            </div>
                        </div>

                
                        <div>
                            <span class="mb-1.5 block text-[11px] font-medium
                                         text-[#89938E]">
                                Service Option
                            </span>

                            <div class="text-[13px] font-semibold text-[#34443B]">
                                {{ $serviceOption }}
                            </div>
                        </div>

                      
                        <div>
                            <span class="mb-1.5 block text-[11px] font-medium
                                         text-[#89938E]">
                                Laundry Weight
                            </span>

                            <div class="text-[13px] font-semibold text-[#34443B]">
                                {{ $booking->weight_kg !== null
                                    ? number_format((float) $booking->weight_kg, 2) . ' kg'
                                    : 'Not recorded' }}
                            </div>
                        </div>

                    
                        <div>
                            <span class="mb-1.5 block text-[11px] font-medium
                                         text-[#89938E]">
                                Pickup Date
                            </span>

                            <div class="text-[13px] font-semibold text-[#34443B]">
                                {{ $pickupDate }}
                            </div>
                        </div>

                 
                        <div>
                            <span class="mb-1.5 block text-[11px] font-medium
                                         text-[#89938E]">
                                Pickup Time
                            </span>

                            <div class="text-[13px] font-semibold text-[#34443B]">
                                {{ $pickupTime }}
                            </div>
                        </div>

                        <div>
                            <span class="mb-1.5 block text-[11px] font-medium
                                         text-[#89938E]">
                                Date Booked
                            </span>

                            <div class="text-[13px] font-semibold text-[#34443B]">
                                {{ $booking->created_at
                                    ? $booking->created_at->format('M d, Y · h:i A')
                                    : 'N/A' }}
                            </div>
                        </div>

                    </div>

                </div>
            </section>

        
            <section class="overflow-hidden rounded-[9px] border border-[#DFE6E1]
                            bg-white">

                <div class="border-b border-[#E9EDEA] px-5 py-4">
                    <h2 class="m-0 text-[14px] font-bold text-[#26374A]">
                        Customer Notes
                    </h2>
                </div>

                <div class="p-5">

                    @if(filled($booking->notes))
                        <p class="m-0 whitespace-pre-line break-words text-[13px]
                                  leading-6 text-[#56635B]">
                            {{ $booking->notes }}
                        </p>
                    @else
                        <p class="m-0 text-[12px] leading-6 text-[#929C96]">
                            No additional instructions were provided for this order.
                        </p>
                    @endif

                </div>
            </section>

        </div>

   
        <aside class="min-w-0 space-y-5">

            {{-- PRICING SUMMARY --}}
            <section class="overflow-hidden rounded-[9px] border border-[#DFE6E1]
                            bg-white">

                <div class="border-b border-[#E9EDEA] px-5 py-4">
                    <h2 class="m-0 text-[14px] font-bold text-[#26374A]">
                        Pricing Summary
                    </h2>
                </div>

                <div class="p-5">

                    <div class="mb-4 flex items-start justify-between gap-3">
                        <span class="text-[12px] text-[#89938E]">
                            Laundry Service
                        </span>

                        <span class="max-w-[150px] break-words text-right
                                     text-[12px] font-semibold text-[#34443B]">
                            {{ $serviceName }}
                        </span>
                    </div>

                    <div class="mb-4 flex items-center justify-between gap-3">
                        <span class="text-[12px] text-[#89938E]">
                            Weight
                        </span>

                        <span class="text-[12px] font-semibold text-[#34443B]">
                            {{ $booking->weight_kg !== null
                                ? number_format((float) $booking->weight_kg, 2) . ' kg'
                                : '—' }}
                        </span>
                    </div>

                    <div class="my-4 border-t border-dashed border-[#DDE5DF]"></div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="text-[12px] font-bold text-[#34443B]">
                            Total Amount
                        </span>

                        <span class="text-[19px] font-bold text-[#008F68]">
                            ₱{{ number_format((float) ($booking->total_price ?? 0), 2) }}
                        </span>
                    </div>

                </div>
            </section>

    
            <section class="overflow-hidden rounded-[9px] border border-[#DFE6E1]
                            bg-white">

                <div class="border-b border-[#E9EDEA] px-5 py-4">
                    <h2 class="m-0 text-[14px] font-bold text-[#26374A]">
                        Order Information
                    </h2>
                </div>

                <div class="space-y-5 p-5">

                    <div>
                        <span class="mb-1.5 block text-[11px] font-medium
                                     text-[#89938E]">
                            Order Number
                        </span>

                        <div class="text-[13px] font-semibold text-[#34443B]">
                            #{{ $booking->id }}
                        </div>
                    </div>

                    <div>
                        <span class="mb-1.5 block text-[11px] font-medium
                                     text-[#89938E]">
                            Current Status
                        </span>

                        <div class="text-[13px] font-semibold text-[#34443B]">
                            {{ $statusLabel }}
                        </div>
                    </div>

                    <div>
                        <span class="mb-1.5 block text-[11px] font-medium
                                     text-[#89938E]">
                            Date Created
                        </span>

                        <div class="text-[13px] font-semibold text-[#34443B]">
                            {{ $booking->created_at
                                ? $booking->created_at->format('M d, Y')
                                : 'N/A' }}
                        </div>
                    </div>

                </div>
            </section>

        </aside>

    </div>

</div>
@endsection
