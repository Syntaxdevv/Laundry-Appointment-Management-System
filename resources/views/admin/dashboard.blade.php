
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $orders = $recentOrders ?? collect();

    $totalOrders = $totalOrders ?? $orders->count();
    $pendingOrders = $pendingOrders ?? 0;
    $inProgressOrders = $inProgressOrders ?? 0;
    $monthlyRevenue = $monthlyRevenue ?? 0;

    $chartLabels = $chartLabels ?? [
        'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
    ];

    $chartValues = $chartValues ?? [0, 0, 0, 0, 0, 0, 0];

    $statusLabels = $statusLabels ?? [
        'Pending',
        'Washing',
        'Drying',
        'Processing',
        'Ready',
        'Completed',
        'Cancelled'
    ];

    $statusValues = $statusValues ?? [0, 0, 0, 0, 0, 0, 0];
@endphp

<div class="w-full min-w-0 font-['DM_Sans'] text-[#20384d]">

    {{-- PAGE HEADING --}}
    <section class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="mb-[5px] text-[26px] font-bold tracking-[-0.5px] text-[#17364d]">
                Admin Dashboard
            </h1>

            <p class="text-[13px] text-[#7a8998]">
                Summary of your laundry business.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 whitespace-nowrap border border-[#e1e8ef] bg-white px-[13px] py-[10px] text-xs text-[#536b7f]">
            <i class="fa-regular fa-calendar"></i>
            {{ now()->format('M d, Y') }}
        </div>
    </section>

    {{-- SUMMARY CARDS --}}
    <section class="mb-[22px] grid grid-cols-4 gap-4 max-[1250px]:grid-cols-2">

        {{-- TOTAL ORDERS --}}
        <article class="flex min-w-0 items-center gap-[13px] border border-[#e2e9ef] bg-white px-4 py-[19px]">
            <div class="flex h-[46px] w-[46px] shrink-0 items-center justify-center bg-[#eaf3fb] text-lg text-[#2479ba]">
                <i class="fa-solid fa-shirt"></i>
            </div>

            <div class="min-w-0">
                <div class="mb-[5px] text-xs text-[#78899a]">
                    Total Orders
                </div>

                <div class="break-words text-[25px] font-bold leading-[1.2] text-[#20384d]">
                    {{ $totalOrders }}
                </div>

                <div class="mt-1.5 text-[11px] leading-[1.4] text-[#198754]">
                    All recorded orders
                </div>
            </div>
        </article>

        {{-- PENDING ORDERS --}}
        <article class="flex min-w-0 items-center gap-[13px] border border-[#e2e9ef] bg-white px-4 py-[19px]">
            <div class="flex h-[46px] w-[46px] shrink-0 items-center justify-center bg-[#fff4df] text-lg text-[#b77b20]">
                <i class="fa-regular fa-clock"></i>
            </div>

            <div class="min-w-0">
                <div class="mb-[5px] text-xs text-[#78899a]">
                    Pending Orders
                </div>

                <div class="break-words text-[25px] font-bold leading-[1.2] text-[#20384d]">
                    {{ $pendingOrders }}
                </div>

                <div class="mt-1.5 text-[11px] leading-[1.4] text-[#198754]">
                    Awaiting processing
                </div>
            </div>
        </article>

        {{-- IN PROGRESS --}}
        <article class="flex min-w-0 items-center gap-[13px] border border-[#e2e9ef] bg-white px-4 py-[19px]">
            <div class="flex h-[46px] w-[46px] shrink-0 items-center justify-center bg-[#eee9fa] text-lg text-[#7557a8]">
                <i class="fa-solid fa-soap"></i>
            </div>

            <div class="min-w-0">
                <div class="mb-[5px] text-xs text-[#78899a]">
                    In Progress
                </div>

                <div class="break-words text-[25px] font-bold leading-[1.2] text-[#20384d]">
                    {{ $inProgressOrders }}
                </div>

                <div class="mt-1.5 text-[11px] leading-[1.4] text-[#198754]">
                    Currently being processed
                </div>
            </div>
        </article>

        {{-- MONTHLY REVENUE --}}
        <article class="flex min-w-0 items-center gap-[13px] border border-[#e2e9ef] bg-white px-4 py-[19px]">
            <div class="flex h-[46px] w-[46px] shrink-0 items-center justify-center bg-[#e6f5ed] text-lg text-[#198754]">
                <i class="fa-solid fa-peso-sign"></i>
            </div>

            <div class="min-w-0">
                <div class="mb-[5px] text-xs text-[#78899a]">
                    Revenue This Month
                </div>

                <div class="break-words text-[25px] font-bold leading-[1.2] text-[#20384d]">
                    ₱{{ number_format((float) $monthlyRevenue, 2) }}
                </div>

                <div class="mt-1.5 text-[11px] leading-[1.4] text-[#198754]">
                    Based on recorded data
                </div>
            </div>
        </article>

    </section>

    {{-- CHARTS --}}
    <section class="mb-[22px] grid grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)] gap-[17px] max-[1250px]:grid-cols-1">

        {{-- ORDERS OVERVIEW --}}
        <article class="min-w-0 border border-[#e2e9ef] bg-white">

            <div class="flex items-center justify-between gap-3 border-b border-[#e8edf2] px-5 py-[17px]">
                <div>
                    <h2 class="text-sm font-bold text-[#20384d]">
                        Orders Overview
                    </h2>

                    <p class="mt-[5px] text-xs text-[#7a8998]">
                        Recent order activity
                    </p>
                </div>
            </div>

            <div class-[dashboard-chart-body] class="p-[18px]">
                <div class="relative h-[275px] w-full">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

        </article>

        {{-- ORDER STATUS --}}
        <article class="min-w-0 border border-[#e2e9ef] bg-white">

            <div class="flex items-center justify-between gap-3 border-b border-[#e8edf2] px-5 py-[17px]">
                <div>
                    <h2 class="text-sm font-bold text-[#20384d]">
                        Order Status
                    </h2>

                    <p class="mt-[5px] text-xs text-[#7a8998]">
                        Current booking distribution
                    </p>
                </div>
            </div>

            <div class="p-[18px]">
                <div class="relative h-[275px] w-full">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

        </article>

    </section>

    {{-- RECENT ORDERS --}}
    <section class="min-w-0 border border-[#e2e9ef] bg-white">

        <div class="flex items-center justify-between gap-3 border-b border-[#e8edf2] px-5 py-[17px]">
            <div>
                <h2 class="text-sm font-bold text-[#20384d]">
                    Recent Orders
                </h2>

                <p class="mt-[5px] text-xs text-[#7a8998]">
                    Latest laundry bookings in your system
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
               class="whitespace-nowrap text-xs font-semibold text-[#2479ba] no-underline hover:underline">
                View All Orders &rarr;
            </a>
        </div>

        @if($orders->isNotEmpty())

            <div class="w-full overflow-x-auto">
                <table class="w-full border-collapse whitespace-nowrap">

                    <thead>
                        <tr>
                            <th class="bg-[#f7f9fb] px-[17px] py-[13px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#718396]">
                                Order ID
                            </th>

                            <th class="bg-[#f7f9fb] px-[17px] py-[13px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#718396]">
                                Customer
                            </th>

                            <th class="bg-[#f7f9fb] px-[17px] py-[13px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#718396]">
                                Service
                            </th>

                            <th class="bg-[#f7f9fb] px-[17px] py-[13px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#718396]">
                                Weight
                            </th>

                            <th class="bg-[#f7f9fb] px-[17px] py-[13px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#718396]">
                                Total
                            </th>

                            <th class="bg-[#f7f9fb] px-[17px] py-[13px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#718396]">
                                Status
                            </th>

                            <th class="bg-[#f7f9fb] px-[17px] py-[13px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#718396]">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $status = strtolower(trim($order->status ?? 'pending'));
                                $statusClass = str_replace(' ', '-', $status);
                            @endphp

                            <tr class="hover:bg-[#f9fbfd]">

                                <td class="border-b border-[#edf1f5] px-[17px] py-[14px] text-xs font-bold text-[#20384d]">
                                    #{{ $order->id }}
                                </td>

                                <td class="border-b border-[#edf1f5] px-[17px] py-[14px] text-xs text-[#53687a]">
                                    {{ $order->user?->name ?? $order->customer_name ?? 'Customer' }}
                                </td>

                                <td class="border-b border-[#edf1f5] px-[17px] py-[14px] text-xs text-[#53687a]">
                                    {{ $order->service?->name ?? 'Laundry Service' }}
                                </td>

                                <td class="border-b border-[#edf1f5] px-[17px] py-[14px] text-xs text-[#53687a]">
                                    {{ $order->weight_kg !== null
                                        ? number_format((float) $order->weight_kg, 1) . ' kg'
                                        : '—' }}
                                </td>

                                <td class="border-b border-[#edf1f5] px-[17px] py-[14px] text-xs text-[#53687a]">
                                    ₱{{ number_format((float) ($order->total_price ?? 0), 2) }}
                                </td>

                                <td class="border-b border-[#edf1f5] px-[17px] py-[14px] text-xs">
                                    <span class="inline-block px-[9px] py-[5px] text-[11px] font-semibold capitalize
                                        @if ($statusClass === 'pending')
                                            bg-[#fff4df] text-[#996515]
                                        @elseif (in_array($statusClass, ['washing', 'drying', 'processing', 'in-progress']))
                                            bg-[#eee9fa] text-[#7050a8]
                                        @elseif (in_array($statusClass, ['completed', 'ready']))
                                            bg-[#e6f5ed] text-[#187447]
                                        @elseif (in_array($statusClass, ['cancelled', 'canceled']))
                                            bg-[#fbeaec] text-[#b02a37]
                                        @else
                                            bg-[#eaf3fb] text-[#2479ba]
                                        @endif
                                    ">
                                        {{ $order->status ?? 'Pending' }}
                                    </span>
                                </td>

                                <td class="border-b border-[#edf1f5] px-[17px] py-[14px] text-xs">
                                    <a href="{{ route('admin.orders.index') }}"
                                       class="text-xs font-semibold text-[#2479ba] no-underline hover:underline">
                                        View
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        @else

            <div class="px-5 py-[38px] text-center text-[13px] text-[#7a8998]">
                <i class="fa-solid fa-inbox mb-3 block text-[25px] text-[#9aabba]"></i>
                <p>No orders available yet.</p>
            </div>

        @endif

    </section>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function () {
    const chartLabels = @json($chartLabels);
    const chartValues = @json($chartValues);
    const statusLabels = @json($statusLabels);
    const statusValues = @json($statusValues);

    const ordersCanvas = document.getElementById('ordersChart');
    const statusCanvas = document.getElementById('statusChart');

    if (ordersCanvas && window.Chart) {
        new Chart(ordersCanvas, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Orders',
                    data: chartValues,
                    borderColor: '#2378b9',
                    backgroundColor: 'rgba(35, 120, 185, 0.10)',
                    fill: true,
                    tension: 0.25,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    if (statusCanvas && window.Chart) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        '#e9b44c',
                        '#2378b9',
                        '#55b9e8',
                        '#6c8ebf',
                        '#25a875',
                        '#8ab6d9',
                        '#d95d63'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 12,
                            boxWidth: 8
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
