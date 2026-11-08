@extends('layouts.staff')

@section('title', 'Staff Dashboard')
@section('page-heading', '')

@section('content')
<div class="staff-dashboard font-['DM_Sans'] text-[#26374a]">

    <div class="mb-[28px] flex items-center justify-between gap-5 max-[700px]:flex-col max-[700px]:items-start max-[700px]:gap-3">
        <div>
            <h1 class="mb-[7px] text-[28px] font-bold tracking-[-0.7px] text-[#173451] max-[700px]:text-2xl">
                Staff Dashboard
            </h1>

            <p class="text-[13px] leading-[1.6] text-[#78889b]">
                Good day! Here's the current status of your laundry orders.
            </p>
        </div>
    </div>

    <h2 class="mb-[14px] text-base font-bold text-[#173451]">
        Order Overview
    </h2>

    <div class="mb-6 grid grid-cols-4 gap-4 max-[1100px]:grid-cols-2 max-[700px]:gap-[10px] max-[380px]:grid-cols-1">

        <article class="min-w-0 rounded-md border border-[#dfe6ee] bg-white p-5 max-[700px]:p-[15px]">
            <div class="mb-[15px] flex items-center gap-3 max-[700px]:gap-[9px]">
                <div class="grid h-[43px] w-[43px] shrink-0 place-items-center rounded-lg bg-[#fff2df] text-[17px] text-[#b77920] max-[700px]:h-9 max-[700px]:w-9">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>

                <span class="text-[13px] font-semibold text-[#52677e]">
                    To Wash
                </span>
            </div>

            <div class="ml-[55px] text-[30px] font-bold leading-[1.1] text-[#173451] max-[700px]:ml-0 max-[700px]:text-[26px]">
                {{ $statusCounts['pending'] ?? 0 }}
            </div>

            <div class="ml-[55px] mt-[9px] text-[11px] leading-[1.5] text-[#8795a6] max-[700px]:ml-0">
                Orders waiting to be processed
            </div>
        </article>

        <article class="min-w-0 rounded-md border border-[#dfe6ee] bg-white p-5 max-[700px]:p-[15px]">
            <div class="mb-[15px] flex items-center gap-3 max-[700px]:gap-[9px]">
                <div class="grid h-[43px] w-[43px] shrink-0 place-items-center rounded-lg bg-[#e7f1ff] text-[17px] text-[#2674ce] max-[700px]:h-9 max-[700px]:w-9">
                    <i class="fa-solid fa-soap"></i>
                </div>

                <span class="text-[13px] font-semibold text-[#52677e]">
                    Washing
                </span>
            </div>

            <div class="ml-[55px] text-[30px] font-bold leading-[1.1] text-[#173451] max-[700px]:ml-0 max-[700px]:text-[26px]">
                {{ $statusCounts['washing'] ?? 0 }}
            </div>

            <div class="ml-[55px] mt-[9px] text-[11px] leading-[1.5] text-[#8795a6] max-[700px]:ml-0">
                Orders currently being washed
            </div>
        </article>

        <article class="min-w-0 rounded-md border border-[#dfe6ee] bg-white p-5 max-[700px]:p-[15px]">
            <div class="mb-[15px] flex items-center gap-3 max-[700px]:gap-[9px]">
                <div class="grid h-[43px] w-[43px] shrink-0 place-items-center rounded-lg bg-[#f0eaff] text-[17px] text-[#8054bd] max-[700px]:h-9 max-[700px]:w-9">
                    <i class="fa-solid fa-wind"></i>
                </div>

                <span class="text-[13px] font-semibold text-[#52677e]">
                    Drying
                </span>
            </div>

            <div class="ml-[55px] text-[30px] font-bold leading-[1.1] text-[#173451] max-[700px]:ml-0 max-[700px]:text-[26px]">
                {{ $statusCounts['drying'] ?? 0 }}
            </div>

            <div class="ml-[55px] mt-[9px] text-[11px] leading-[1.5] text-[#8795a6] max-[700px]:ml-0">
                Orders currently in drying
            </div>
        </article>

        <article class="min-w-0 rounded-md border border-[#dfe6ee] bg-white p-5 max-[700px]:p-[15px]">
            <div class="mb-[15px] flex items-center gap-3 max-[700px]:gap-[9px]">
                <div class="grid h-[43px] w-[43px] shrink-0 place-items-center rounded-lg bg-[#e3f4eb] text-[17px] text-[#21855c] max-[700px]:h-9 max-[700px]:w-9">
                    <i class="fa-solid fa-box-open"></i>
                </div>

                <span class="text-[13px] font-semibold text-[#52677e]">
                    Ready / Folding
                </span>
            </div>

            <div class="ml-[55px] text-[30px] font-bold leading-[1.1] text-[#173451] max-[700px]:ml-0 max-[700px]:text-[26px]">
                {{ $statusCounts['ready'] ?? 0 }}
            </div>

            <div class="ml-[55px] mt-[9px] text-[11px] leading-[1.5] text-[#8795a6] max-[700px]:ml-0">
                Orders ready for customer pickup
            </div>
        </article>

    </div>
    <section class="overflow-hidden rounded-md border border-[#dfe6ee] bg-white">


        <div class="flex items-center justify-between gap-[15px] border-b border-[#e5ebf1] px-5 py-[19px] max-[700px]:flex-wrap max-[700px]:items-start max-[700px]:p-4">
            <div>
                <h2 class="text-base font-bold text-[#173451]">
                    Recent Orders
                </h2>

                <p class="mt-[5px] text-xs text-[#8291a3]">
                    Latest laundry bookings received by the system.
                </p>
            </div>

            <span class="whitespace-nowrap text-xs text-[#60748a]">
                {{ $recentOrders->count() }}
                {{ $recentOrders->count() === 1 ? 'recent order' : 'recent orders' }}
            </span>
        </div>

        @if ($recentOrders->isNotEmpty())


            <div class="w-full overflow-x-auto">
                <table class="w-full border-collapse text-left whitespace-nowrap">

                    <thead class="bg-[#f5f8fb]">
                        <tr>
                            <th class="px-[17px] py-[14px] text-[10px] font-bold tracking-[0.35px] text-[#718399] uppercase max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                Order ID
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-bold tracking-[0.35px] text-[#718399] uppercase max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                Customer
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-bold tracking-[0.35px] text-[#718399] uppercase max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                Service
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-bold tracking-[0.35px] text-[#718399] uppercase max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                Weight
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-bold tracking-[0.35px] text-[#718399] uppercase max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                Pickup Date
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-bold tracking-[0.35px] text-[#718399] uppercase max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                Total
                            </th>

                            <th class="px-[17px] py-[14px] text-[10px] font-bold tracking-[0.35px] text-[#718399] uppercase max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($recentOrders as $order)

                            @php
                                $rawStatus = strtolower(
                                    str_replace(' ', '-', $order->status ?? 'pending')
                                );

                                $knownStatuses = [
                                    'pending',
                                    'washing',
                                    'drying',
                                    'ready',
                                    'folding',
                                    'completed',
                                    'cancelled',
                                    'in-progress',
                                    'in_progress',
                                ];

                                $statusClass = in_array($rawStatus, $knownStatuses, true)
                                    ? $rawStatus
                                    : 'default';

                                $customer = $order->customer_name
                                    ?: ($order->user->name ?? 'Walk-in Customer');

                                $serviceName = $order->service->name
                                    ?? 'Laundry Service';
                            @endphp

                            <tr class="hover:bg-[#f9fbfd]">

                   
                                <td class="border-t border-[#e9eef3] px-[17px] py-[15px] text-xs text-[#50647b] max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                    <span class="font-bold text-[#176fc2]">
                                        #{{ $order->id }}
                                    </span>
                                </td>

                                <td class="border-t border-[#e9eef3] px-[17px] py-[15px] text-xs text-[#50647b] max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                    <div class="text-xs font-semibold text-[#24384e]">
                                        {{ $customer }}
                                    </div>

                                    <div class="mt-1 text-[10px] text-[#8493a5]">
                                        {{ $order->user->email ?? 'Walk-in order' }}
                                    </div>
                                </td>

                        
                                <td class="border-t border-[#e9eef3] px-[17px] py-[15px] text-xs text-[#50647b] max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                    {{ $serviceName }}

                                    @if ($order->service_option)
                                        <div class="mt-1 text-[10px] text-[#8493a5]">
                                            {{ ucfirst(str_replace('_', ' ', $order->service_option)) }}
                                        </div>
                                    @endif
                                </td>

                    
                                <td class="border-t border-[#e9eef3] px-[17px] py-[15px] text-xs text-[#50647b] max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                    {{ $order->weight_kg !== null
                                        ? number_format((float) $order->weight_kg, 1) . ' kg'
                                        : '—' }}
                                </td>

                                <td class="border-t border-[#e9eef3] px-[17px] py-[15px] text-xs text-[#50647b] max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                    {{ $order->pickup_date
                                        ? \Illuminate\Support\Carbon::parse($order->pickup_date)->format('M d, Y')
                                        : '—' }}

                                    @if ($order->pickup_time)
                                        <div class="mt-1 text-[10px] text-[#8493a5]">
                                            {{ $order->pickup_time }}
                                        </div>
                                    @endif
                                </td>

                                <td class="border-t border-[#e9eef3] px-[17px] py-[15px] text-xs text-[#50647b] max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                    ₱{{ number_format((float) ($order->total_price ?? 0), 2) }}
                                </td>

                                {{-- STATUS --}}
                                <td class="border-t border-[#e9eef3] px-[17px] py-[15px] text-xs text-[#50647b] max-[700px]:px-[14px] max-[700px]:py-[13px]">
                                    <span class="inline-flex items-center gap-[6px] rounded-[5px] px-[9px] py-[6px] text-[10px] font-semibold capitalize
                                        @if ($statusClass === 'pending')
                                            bg-[#fff2df] text-[#a86b15]
                                        @elseif (in_array($statusClass, ['washing', 'in-progress', 'in_progress']))
                                            bg-[#e7f1ff] text-[#2469b6]
                                        @elseif ($statusClass === 'drying')
                                            bg-[#f0eaff] text-[#8054bd]
                                        @elseif (in_array($statusClass, ['ready', 'completed', 'folding']))
                                            bg-[#e3f4eb] text-[#21855c]
                                        @elseif ($statusClass === 'cancelled')
                                            bg-[#fbe9e9] text-[#b34242]
                                        @else
                                            bg-[#edf1f5] text-[#526176]
                                        @endif
                                    ">
                                        <span class="h-[6px] w-[6px] shrink-0 rounded-full bg-current"></span>

                                        {{ ucfirst(str_replace(
                                            ['-', '_'],
                                            ' ',
                                            $order->status ?? 'pending'
                                        )) }}
                                    </span>
                                </td>

                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

        @else

       
            <div class="px-5 py-[50px] text-center text-[#7b8999]">
                <i class="fa-regular fa-clipboard mb-3 text-[28px] text-[#a4b2c1]"></i>

                <h3 class="mb-[7px] text-[15px] text-[#263e54]">
                    No orders yet
                </h3>

                <p class="text-xs leading-[1.6]">
                    New laundry bookings will appear here when customers place orders.
                </p>
            </div>

        @endif

    </section>

</div>
@endsection
