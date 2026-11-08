
@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
@php
    $adminName = auth()->user()->name ?? 'Admin';
@endphp

<div class="flex min-h-screen bg-[#f4f7fa] font-['DM_Sans',sans-serif] text-[#243b50]">

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-[100] flex w-[230px] flex-col overflow-y-auto bg-[#102f49] px-[15px] py-[22px] text-white">

        <a href="{{ route('admin.dashboard') }}"
           class="flex shrink-0 items-center gap-[11px] px-2 pb-7 pt-1 text-white no-underline">

            <span class="flex h-[38px] w-[38px] flex-[0_0_38px] items-center justify-center rounded-md bg-[#287bb9] text-lg">
                <i class="fa-solid fa-shirt"></i>
            </span>

            <span>
                <span class="block text-[17px] font-extrabold tracking-[-0.4px]">
                    Linis<span class="text-[#7ac8f4]">Laba</span>
                </span>

                <small class="mt-1 block text-[9px] text-[#a8bdce]">
                    Laundry Management System
                </small>
            </span>
        </a>

        <div class="mb-3 mx-[10px] text-[10px] font-bold tracking-[1px] text-[#91aabd]">
            ADMIN MENU
        </div>

        <nav class="flex flex-col gap-1">

            <a href="{{ route('admin.dashboard') }}"
               class="flex w-full items-center gap-3 rounded-[5px] px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] no-underline transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                <i class="fa-solid fa-chart-line flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Dashboard</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="flex w-full items-center gap-3 rounded-[5px] bg-[#287bb9] px-3 py-[11px] text-left text-xs font-bold text-white no-underline">
                <i class="fa-solid fa-receipt flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Orders</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}"
               class="flex w-full items-center gap-3 rounded-[5px] px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] no-underline transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                <i class="fa-solid fa-calendar-check flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Bookings</span>
            </a>

            <a href="{{ route('admin.customers.index') }}"
               class="flex w-full items-center gap-3 rounded-[5px] px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] no-underline transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                <i class="fa-solid fa-users flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Customers</span>
            </a>

            <a href="{{ route('admin.services.index') }}"
               class="flex w-full items-center gap-3 rounded-[5px] px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] no-underline transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                <i class="fa-solid fa-tags flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Services &amp; Pricing</span>
            </a>

            <a href="{{ route('admin.payments.index') }}"
               class="flex w-full items-center gap-3 rounded-[5px] px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] no-underline transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                <i class="fa-solid fa-wallet flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Payments</span>
            </a>

            <a href="{{ route('admin.reports.index') }}"
               class="flex w-full items-center gap-3 rounded-[5px] px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] no-underline transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                <i class="fa-solid fa-chart-pie flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Reports</span>
            </a>

            <a href="{{ route('admin.staff.index') }}"
               class="flex w-full items-center gap-3 rounded-[5px] px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] no-underline transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                <i class="fa-solid fa-user-gear flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Staff Management</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="flex w-full items-center gap-3 rounded-[5px] px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] no-underline transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                <i class="fa-solid fa-gear flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                <span class="min-w-0">Settings</span>
            </a>

        </nav>

        <div class="mt-auto border-t border-white/10 pt-[14px]">
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf

                <button type="submit"
                        class="flex w-full cursor-pointer items-center gap-3 rounded-[5px] border-0 bg-transparent px-3 py-[11px] text-left text-xs font-medium text-[#c4d2de] transition-colors duration-150 hover:bg-[#287bb9] hover:text-white">
                    <i class="fa-solid fa-right-from-bracket flex w-[18px] flex-[0_0_18px] items-center justify-center text-sm"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- Main Content --}}
    <div class="ml-[230px] min-h-screen w-[calc(100%-230px)] min-w-0">

        {{-- Topbar --}}
        <header class="flex min-h-16 items-center justify-between gap-[15px] border-b border-[#e4eaf0] bg-white px-7 py-3">

            <div class="text-xs text-[#8493a2]">
                Admin Portal&nbsp; / &nbsp;<strong class="font-semibold text-[#263e52]">Orders</strong>
            </div>

            <div class="flex items-center gap-[11px] text-xs text-[#526579]">

                <span class="flex h-[30px] w-[30px] items-center justify-center text-[#718397]"
                      aria-label="Notifications">
                    <i class="fa-regular fa-bell"></i>
                </span>

                <span class="flex h-[34px] w-[34px] flex-[0_0_34px] items-center justify-center rounded-full bg-[#e6f2fc] text-[13px] font-bold text-[#2477b5]">
                    {{ strtoupper(substr($adminName, 0, 1)) }}
                </span>

                <span>{{ $adminName }}</span>

            </div>
        </header>

        <main class="mx-auto max-w-[1500px] p-7">

            {{-- Page Heading --}}
            <div class="mb-[23px] flex items-center justify-between gap-4">

                <div>
                    <h1 class="mb-[6px] text-[25px] font-bold tracking-[-0.5px] text-[#20394e]">
                        Orders
                    </h1>

                    <p class="m-0 text-[13px] text-[#7b8b9a]">
                        Manage and review laundry orders.
                    </p>
                </div>

                <a href="{{ route('admin.bookings.index') }}"
                   class="inline-flex min-h-[25px] items-center justify-center gap-2 rounded-full border border-[#2479b9] bg-[#2479b9] px-5 py-[10px] text-xs font-semibold text-white no-underline transition-colors duration-150 hover:border-[#1b6297] hover:bg-[#1b6297]">
                    <i class="fa-solid fa-plus"></i>
                    New Order
                </a>

            </div>

            {{-- Orders Table Panel --}}
            <section class="overflow-hidden rounded-md border border-[#e2e8ee] bg-white">

                {{-- Toolbar --}}
                <form method="GET"
                      action="{{ route('admin.orders.index') }}"
                      class="flex items-center gap-[10px] border-b border-[#e8edf2] p-4">

                    <label class="flex min-h-[38px] min-w-0 flex-1 items-center gap-[10px] rounded-[5px] border border-[#dce4eb] bg-white px-3 text-[#8494a3] focus-within:border-[#70a9d1]">

                        <i class="fa-solid fa-magnifying-glass shrink-0 text-xs"></i>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search orders, customers, or services..."
                            class="w-full min-w-0 border-0 bg-transparent text-xs text-[#263e52] outline-none placeholder:text-[#98a5b1] focus:ring-0"
                        >

                    </label>

                    <select
                        name="status"
                        class="min-h-[38px] rounded-[5px] border border-[#dce4eb] bg-white px-[11px] text-xs text-[#526579] outline-none"
                        onchange="this.form.submit()"
                    >
                        <option value="all" @selected(request('status', 'all') === 'all')>
                            All Statuses
                        </option>

                        @foreach([
                            'pending',
                            'washing',
                            'drying',
                            'processing',
                            'ready',
                            'completed',
                            'cancelled'
                        ] as $option)
                            <option
                                value="{{ $option }}"
                                @selected(request('status') === $option)
                            >
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="inline-flex min-h-[38px] items-center justify-center gap-2 rounded-full border border-[#2479b9] bg-[#2479b9] px-5 py-[10px] text-xs font-semibold text-white transition-colors duration-150 hover:border-[#1b6297] hover:bg-[#1b6297]">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Search
                    </button>

                </form>

                {{-- Table --}}
                <div class="w-full overflow-x-auto">

                    <table class="w-full border-collapse whitespace-nowrap">

                        <thead class="bg-[#f7f9fb]">
                            <tr>
                                <th class="border-b border-[#e7edf2] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.45px] text-[#718397]">
                                    Order ID
                                </th>

                                <th class="border-b border-[#e7edf2] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.45px] text-[#718397]">
                                    Customer
                                </th>

                                <th class="border-b border-[#e7edf2] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.45px] text-[#718397]">
                                    Service
                                </th>

                                <th class="border-b border-[#e7edf2] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.45px] text-[#718397]">
                                    Weight
                                </th>

                                <th class="border-b border-[#e7edf2] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.45px] text-[#718397]">
                                    Order Date
                                </th>

                                <th class="border-b border-[#e7edf2] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.45px] text-[#718397]">
                                    Total
                                </th>

                                <th class="border-b border-[#e7edf2] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.45px] text-[#718397]">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($orders as $order)
                                @php
                                    $status = strtolower(trim($order->status ?? 'pending'));
                                    $statusClass = preg_replace('/[^a-z0-9-]/', '-', $status);

                                    $statusColors = [
                                        'pending' => 'bg-[#fff4df] text-[#a16b14]',
                                        'washing' => 'bg-[#e8f2fc] text-[#286b9f]',
                                        'drying' => 'bg-[#e8f2fc] text-[#286b9f]',
                                        'processing' => 'bg-[#e8f2fc] text-[#286b9f]',
                                        'ready' => 'bg-[#e6f5ec] text-[#267849]',
                                        'completed' => 'bg-[#e6f5ec] text-[#267849]',
                                        'cancelled' => 'bg-[#fbeaec] text-[#b33b48]',
                                    ];

                                    $statusColor = $statusColors[$status] ?? 'bg-[#e9f2fa] text-[#286b9f]';
                                @endphp

                                <tr class="transition-colors duration-150 hover:bg-[#f8fbfd]">

                                    <td class="border-b border-[#edf1f5] px-4 py-[15px] text-xs font-bold text-[#25445e]">
                                        #{{ $order->id }}
                                    </td>

                                    <td class="border-b border-[#edf1f5] px-4 py-[15px] text-xs font-semibold text-[#31495e]">
                                        {{ $order->customer_name ?? 'Customer' }}
                                    </td>

                                    <td class="border-b border-[#edf1f5] px-4 py-[15px] text-xs text-[#607487]">
                                        {{ $order->service?->name ?? 'Laundry Service' }}
                                    </td>

                                    <td class="border-b border-[#edf1f5] px-4 py-[15px] text-xs text-[#53677a]">
                                        {{ isset($order->weight_kg)
                                            ? number_format((float) $order->weight_kg, 1) . ' kg'
                                            : '—' }}
                                    </td>

                                    <td class="border-b border-[#edf1f5] px-4 py-[15px] text-xs text-[#53677a]">
                                        {{ $order->created_at?->format('M d, Y') ?? '—' }}
                                    </td>

                                    <td class="border-b border-[#edf1f5] px-4 py-[15px] text-xs text-[#53677a]">
                                        ₱{{ number_format((float) ($order->total_price ?? 0), 2) }}
                                    </td>

                                    <td class="border-b border-[#edf1f5] px-4 py-[15px] text-xs text-[#53677a]">

                                        <span class="inline-flex items-center gap-[6px] rounded-[4px] px-[9px] py-[5px] text-[10px] font-semibold capitalize before:h-[6px] before:w-[6px] before:rounded-full before:bg-current before:content-[''] {{ $statusColor }}">
                                            {{ ucfirst($status) }}
                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7"
                                        class="px-4 py-[45px] text-center text-[#8392a0]">

                                        <i class="fa-regular fa-folder-open mb-[10px] block text-[25px] text-[#a7b5c1]"></i>

                                        No orders found.

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                <div class="flex flex-wrap items-center justify-between gap-[15px] border-t border-[#e8edf2] px-4 py-[15px] text-[11px] text-[#78899a]">

                    <span>
                        Showing {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }}
                        of {{ $orders->total() }} orders
                    </span>

                    <div class="ml-auto [&_nav]:ml-auto [&_nav>div]:flex [&_nav>div]:flex-wrap [&_nav>div]:items-center [&_nav>div]:gap-[5px] [&_nav_a]:inline-flex [&_nav_a]:min-h-8 [&_nav_a]:min-w-8 [&_nav_a]:items-center [&_nav_a]:justify-center [&_nav_a]:rounded [&_nav_a]:border [&_nav_a]:border-[#dfe7ef] [&_nav_a]:bg-white [&_nav_a]:px-[9px] [&_nav_a]:py-[5px] [&_nav_a]:text-xs [&_nav_a]:text-[#526579] [&_nav_a]:no-underline [&_nav_a:hover]:bg-[#edf5fd] [&_nav_a:hover]:text-[#1976c9] [&_nav_span[aria-current=page]>span]:inline-flex [&_nav_span[aria-current=page]>span]:min-h-8 [&_nav_span[aria-current=page]>span]:min-w-8 [&_nav_span[aria-current=page]>span]:items-center [&_nav_span[aria-current=page]>span]:justify-center [&_nav_span[aria-current=page]>span]:rounded [&_nav_span[aria-current=page]>span]:border [&_nav_span[aria-current=page]>span]:border-[#2479b9] [&_nav_span[aria-current=page]>span]:bg-[#2479b9] [&_nav_span[aria-current=page]>span]:px-[9px] [&_nav_span[aria-current=page]>span]:py-[5px] [&_nav_span[aria-current=page]>span]:text-xs [&_nav_span[aria-current=page]>span]:text-white [&_nav_span[aria-disabled=true]>span]:inline-flex [&_nav_span[aria-disabled=true]>span]:min-h-8 [&_nav_span[aria-disabled=true]>span]:min-w-8 [&_nav_span[aria-disabled=true]>span]:items-center [&_nav_span[aria-disabled=true]>span]:justify-center [&_nav_span[aria-disabled=true]>span]:rounded [&_nav_span[aria-disabled=true]>span]:border [&_nav_span[aria-disabled=true]>span]:border-[#dfe7ef] [&_nav_span[aria-disabled=true]>span]:bg-white [&_nav_span[aria-disabled=true]>span]:px-[9px] [&_nav_span[aria-disabled=true]>span]:py-[5px] [&_nav_span[aria-disabled=true]>span]:text-xs [&_nav_span[aria-disabled=true]>span]:text-[#526579] [&_nav_svg]:h-[15px] [&_nav_svg]:w-[15px]">

                        {{ $orders->links() }}

                    </div>

                </div>

            </section>

        </main>

    </div>

</div>
@endsection
