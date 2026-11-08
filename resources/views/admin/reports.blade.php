@extends('layouts.admin')

@section('content')

<div class="text-[#17365d] [font-family:'DM_Sans',Arial,sans-serif]">

    {{-- Reports Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="mb-[5px] text-[27px] font-bold text-[#17365d]">
                Reports
            </h1>
            <p class="text-[13px] text-[#718096]">
                View business insights and performance.
            </p>
        </div>

        <input
            type="date"
            class="rounded-[7px] border border-[#e2e8f0] bg-white px-[13px] py-[10px] text-[12px] text-[#475569] outline-none focus:border-[#3288e8]"
            id="reportDate"
            aria-label="Select report date"
        >
    </div>

    {{-- Report Tabs --}}
    <div class="mb-5 flex gap-[27px] overflow-x-auto border-b border-[#e2e8f0]">
        <button
            class="report-tab active whitespace-nowrap border-0 border-b-[3px] border-solid border-transparent bg-transparent px-[3px] py-3 text-[12px] font-semibold text-[#1673d1] cursor-pointer"
            type="button"
            data-report-tab="sales"
        >
            Sales Overview
        </button>

        <button
            class="report-tab whitespace-nowrap border-0 border-b-[3px] border-solid border-transparent bg-transparent px-[3px] py-3 text-[12px] font-semibold text-[#64748b] cursor-pointer"
            type="button"
            data-report-tab="orders"
        >
            Orders
        </button>

        <button
            class="report-tab whitespace-nowrap border-0 border-b-[3px] border-solid border-transparent bg-transparent px-[3px] py-3 text-[12px] font-semibold text-[#64748b] cursor-pointer"
            type="button"
            data-report-tab="customers"
        >
            Customers
        </button>

        <button
            class="report-tab whitespace-nowrap border-0 border-b-[3px] border-solid border-transparent bg-transparent px-[3px] py-3 text-[12px] font-semibold text-[#64748b] cursor-pointer"
            type="button"
            data-report-tab="services"
        >
            Services
        </button>
    </div>

    {{-- Report Statistics --}}
    <div class="mb-5 grid grid-cols-4 gap-4 max-[1100px]:grid-cols-2">

        <div class="min-w-0 rounded-[9px] border border-[#e9eef5] bg-white p-5 shadow-[0_2px_7px_rgba(23,54,93,0.035)] max-[600px]:p-[14px]">
            <div class="mb-[13px] text-[12px] text-[#718096]">
                Total Sales
            </div>

            <div class="break-words text-[clamp(19px,2vw,27px)] font-bold tracking-[-0.5px] text-[#17365d]">
                ₱{{ number_format($totalSales ?? 0, 2) }}
            </div>

            <div class="mt-[9px] text-[11px] text-[#718096]">
                Based on available report data
            </div>
        </div>

        <div class="min-w-0 rounded-[9px] border border-[#e9eef5] bg-white p-5 shadow-[0_2px_7px_rgba(23,54,93,0.035)] max-[600px]:p-[14px]">
            <div class="mb-[13px] text-[12px] text-[#718096]">
                Total Orders
            </div>

            <div class="break-words text-[clamp(19px,2vw,27px)] font-bold tracking-[-0.5px] text-[#17365d]">
                {{ number_format($totalOrders ?? 0) }}
            </div>

            <div class="mt-[9px] text-[11px] text-[#718096]">
                All recorded bookings
            </div>
        </div>

        <div class="min-w-0 rounded-[9px] border border-[#e9eef5] bg-white p-5 shadow-[0_2px_7px_rgba(23,54,93,0.035)] max-[600px]:p-[14px]">
            <div class="mb-[13px] text-[12px] text-[#718096]">
                Avg. Order Value
            </div>

            <div class="break-words text-[clamp(19px,2vw,27px)] font-bold tracking-[-0.5px] text-[#17365d]">
                ₱{{ number_format($averageOrderValue ?? 0, 2) }}
            </div>

            <div class="mt-[9px] text-[11px] text-[#718096]">
                Average per order
            </div>
        </div>

        <div class="min-w-0 rounded-[9px] border border-[#e9eef5] bg-white p-5 shadow-[0_2px_7px_rgba(23,54,93,0.035)] max-[600px]:p-[14px]">
            <div class="mb-[13px] text-[12px] text-[#718096]">
                Total Profit
            </div>

            <div class="break-words text-[clamp(19px,2vw,27px)] font-bold tracking-[-0.5px] text-[#17365d]">
                ₱{{ number_format($totalProfit ?? 0, 2) }}
            </div>

            <div class="mt-[9px] text-[11px] text-[#718096]">
                Profit data not configured
            </div>
        </div>

    </div>

    {{-- Report Panels --}}
    <div class="grid grid-cols-[minmax(0,1.7fr)_minmax(260px,1fr)] items-stretch gap-[18px] max-[1100px]:grid-cols-1">

        {{-- Sales Overview --}}
        <section
            class="min-w-0 rounded-[9px] border border-[#e9eef5] bg-white p-[22px] shadow-[0_2px_7px_rgba(23,54,93,0.035)] max-[600px]:p-4"
            data-report-panel="sales"
        >
            <h2 class="mb-[5px] text-[15px] font-bold text-[#17365d]">
                Sales Overview
            </h2>

            <div class="mb-[22px] text-[11px] text-[#94a3b8]">
                Recorded sales by day
            </div>

            <div class="grid h-[220px] grid-cols-[30px_minmax(0,1fr)] gap-[9px]">

                <div class="flex flex-col items-end justify-between pb-6 text-[10px] text-[#94a3b8]">
                    <span>100%</span>
                    <span>75%</span>
                    <span>50%</span>
                    <span>25%</span>
                    <span>0%</span>
                </div>

                <div class="flex min-w-0 flex-col">

                    @php
                        $salesChart = $salesByDay ?? [];
                        $salesValues = collect($salesChart)->pluck('total')->map(fn ($value) => (float) $value);
                        $maxSales = max(1, (float) $salesValues->max());
                        $chartLabels = collect($salesChart)->pluck('label')->all();
                    @endphp

                    <div class="grid h-[190px] grid-cols-7 items-end gap-3 border-b border-[#dfe7f0] bg-[repeating-linear-gradient(to_bottom,transparent_0,transparent_46px,#edf2f7_47px,transparent_48px)] px-[5px] max-[600px]:gap-[6px]">

                        @for ($i = 0; $i < 7; $i++)
                            @php
                                $daySales = (float) ($salesChart[$i]['total'] ?? 0);
                                $barHeight = $daySales > 0
                                    ? max(4, ($daySales / $maxSales) * 100)
                                    : 0;
                            @endphp

                            <div class="flex h-full items-end justify-center">
                                <div
                                    class="w-[min(100%,28px)] rounded-t-[4px] bg-[#3288e8] transition-colors hover:bg-[#17365d]"
                                    style="height: {{ $barHeight }}%;"
                                    title="₱{{ number_format($daySales, 2) }}"
                                ></div>
                            </div>
                        @endfor

                    </div>

                    <div class="grid grid-cols-7 gap-3 pt-[10px] text-center text-[10px] text-[#718096] max-[600px]:gap-[6px]">
                        @for ($i = 0; $i < 7; $i++)
                            <span>
                                {{ $chartLabels[$i] ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'][$i] }}
                            </span>
                        @endfor
                    </div>

                </div>
            </div>

            <p class="mt-[18px] text-[11px] leading-[1.6] text-[#94a3b8]">
                The chart uses the sales data supplied by the reports controller.
                If no data is supplied, the chart will remain empty.
            </p>
        </section>

        {{-- Top Services --}}
        <section
            class="min-w-0 rounded-[9px] border border-[#e9eef5] bg-white p-[22px] shadow-[0_2px_7px_rgba(23,54,93,0.035)] max-[600px]:p-4"
            data-report-panel="services"
        >
            <h2 class="mb-[5px] text-[15px] font-bold text-[#17365d]">
                Top Services
            </h2>

            <div class="mb-[22px] text-[11px] text-[#94a3b8]">
                Share of recorded bookings by service
            </div>

            @php
                $serviceReports = $topServices ?? collect();
                $serviceColors = ['', 'teal', 'navy', 'gold'];
            @endphp

            <div class="mt-[25px] flex flex-col gap-[23px]">

                @forelse ($serviceReports as $index => $service)
                    <div class="grid grid-cols-[1fr_auto] items-center gap-x-3 gap-y-2">

                        <span class="text-[12px] font-semibold text-[#475569]">
                            {{ $service->service_name ?? $service->name ?? 'Laundry Service' }}
                        </span>

                        <span class="text-[11px] font-bold text-[#64748b]">
                            {{ (int) ($service->percentage ?? 0) }}%
                        </span>

                        <div class="col-span-full h-[7px] overflow-hidden rounded-full bg-[#edf2f7]">
                            <div
                                class="h-full rounded-full
                                    @if ($serviceColors[$index % 4] === 'teal') bg-[#12a89d]
                                    @elseif ($serviceColors[$index % 4] === 'navy') bg-[#17365d]
                                    @elseif ($serviceColors[$index % 4] === 'gold') bg-[#f4b740]
                                    @else bg-[#3288e8]
                                    @endif"
                                style="width: {{ min(100, max(0, (float) ($service->percentage ?? 0))) }}%;"
                            ></div>
                        </div>

                    </div>
                @empty
                    <p class="mt-[18px] text-[11px] leading-[1.6] text-[#94a3b8]">
                        No service report data is available yet.
                    </p>
                @endforelse

            </div>
        </section>

        {{-- Orders Report --}}
        <section
            class="report-panel report-hidden min-w-0 rounded-[9px] border border-[#e9eef5] bg-white p-[22px] shadow-[0_2px_7px_rgba(23,54,93,0.035)] max-[600px]:p-4"
            data-report-panel="orders"
        >
            <h2 class="mb-[5px] text-[15px] font-bold text-[#17365d]">
                Orders Report
            </h2>

            <div class="mb-[22px] text-[11px] text-[#94a3b8]">
                Overview of recorded laundry orders
            </div>

            <p class="text-[12px] text-[#718096]">
                Total recorded orders:
                <strong>{{ number_format($totalOrders ?? 0) }}</strong>
            </p>

            <p class="mt-[18px] text-[11px] leading-[1.6] text-[#94a3b8]">
                Order status breakdown can be added when the controller supplies the status totals.
            </p>
        </section>

        {{-- Customers Report --}}
        <section
            class="report-panel report-hidden min-w-0 rounded-[9px] border border-[#e9eef5] bg-white p-[22px] shadow-[0_2px_7px_rgba(23,54,93,0.035)] max-[600px]:p-4"
            data-report-panel="customers"
        >
            <h2 class="mb-[5px] text-[15px] font-bold text-[#17365d]">
                Customers Report
            </h2>

            <div class="mb-[22px] text-[11px] text-[#94a3b8]">
                Customer activity summary
            </div>

            <p class="text-[12px] text-[#718096]">
                Total customers:
                <strong>{{ number_format($totalCustomers ?? 0) }}</strong>
            </p>

            <p class="mt-[18px] text-[11px] leading-[1.6] text-[#94a3b8]">
                Customer statistics will use the customer count supplied by the controller.
            </p>
        </section>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('[data-report-tab]');
    const panels = document.querySelectorAll('[data-report-panel]');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const selected = tab.dataset.reportTab;

            tabs.forEach(function (item) {
                item.classList.toggle('active', item === tab);
            });

            panels.forEach(function (panel) {
                panel.classList.toggle(
                    'report-hidden',
                    panel.dataset.reportPanel !== selected
                );
            });
        });
    });
});
</script>

<style>
    .report-tab.active {
        color: #1673d1;
        border-bottom-color: #1673d1;
    }

    .report-hidden {
        display: none !important;
    }
</style>

@endsection
