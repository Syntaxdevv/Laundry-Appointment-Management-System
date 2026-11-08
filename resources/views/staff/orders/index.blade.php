@extends('layouts.staff')

@section('title', 'Orders Management')
@section('page-heading', '')

@section('content')

@php
    $currentStatus = request('status', 'all');

    $pageOrders = $orders->getCollection();

    $totalOrders = $orders->total();

    $pendingCount = $pageOrders->filter(function ($order) {
        return strtolower($order->status ?? 'pending') === 'pending';
    })->count();

    $processingCount = $pageOrders->filter(function ($order) {
        return in_array(
            strtolower($order->status ?? ''),
            ['washing', 'processing', 'drying']
        );
    })->count();

    $completedCount = $pageOrders->filter(function ($order) {
        return strtolower($order->status ?? '') === 'completed';
    })->count();
@endphp

<div class="font-['DM_Sans'] text-[#26364a]">

  
    <div class="mb-6 flex items-center justify-between gap-4 max-[768px]:items-start">

        <div>
            <h2 class="text-[22px] font-bold text-[#173653]">
                Manage Your Orders
            </h2>

            <p class="mt-1 text-[13px] text-[#8492a3]">
                Track, organize, and manage your laundry orders.
            </p>
        </div>

        <div class="grid h-[42px] w-[42px] shrink-0 place-items-center rounded-lg bg-[#e4efff] text-[18px] text-[#1678db]">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>

    </div>

    <div class="mb-6 grid grid-cols-4 gap-3 max-[1200px]:grid-cols-2 max-[480px]:grid-cols-1">


        <div class="flex min-h-[105px] items-center justify-between gap-3 rounded-[9px] border border-[#dfe7ef] bg-white p-4">

            <div>
                <span class="text-xs font-medium text-[#78899c]">
                    Total Orders
                </span>

                <h3 class="mt-1 text-[26px] font-bold text-[#173653]">
                    {{ $totalOrders }}
                </h3>

                <small class="text-[11px] text-[#8997a7]">
                    All recorded orders
                </small>
            </div>

            <div class="grid h-[42px] w-[42px] shrink-0 place-items-center rounded-lg bg-[#e4efff] text-[#2868bd]">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>

        </div>


        <div class="flex min-h-[105px] items-center justify-between gap-3 rounded-[9px] border border-[#dfe7ef] bg-white p-4">

            <div>
                <span class="text-xs font-medium text-[#78899c]">
                    Pending
                </span>

                <h3 class="mt-1 text-[26px] font-bold text-[#173653]">
                    {{ $pendingCount }}
                </h3>

                <small class="text-[11px] text-[#8997a7]">
                    On this page
                </small>
            </div>

            <div class="grid h-[42px] w-[42px] shrink-0 place-items-center rounded-lg bg-[#fff1d8] text-[#a67119]">
                <i class="fa-solid fa-clock"></i>
            </div>

        </div>

    
        <div class="flex min-h-[105px] items-center justify-between gap-3 rounded-[9px] border border-[#dfe7ef] bg-white p-4">

            <div>
                <span class="text-xs font-medium text-[#78899c]">
                    In Progress
                </span>

                <h3 class="mt-1 text-[26px] font-bold text-[#173653]">
                    {{ $processingCount }}
                </h3>

                <small class="text-[11px] text-[#8997a7]">
                    On this page
                </small>
            </div>

            <div class="grid h-[42px] w-[42px] shrink-0 place-items-center rounded-lg bg-[#eee8ff] text-[#7550b2]">
                <i class="fa-solid fa-soap"></i>
            </div>

        </div>

    
        <div class="flex min-h-[105px] items-center justify-between gap-3 rounded-[9px] border border-[#dfe7ef] bg-white p-4">

            <div>
                <span class="text-xs font-medium text-[#78899c]">
                    Completed
                </span>

                <h3 class="mt-1 text-[26px] font-bold text-[#173653]">
                    {{ $completedCount }}
                </h3>

                <small class="text-[11px] text-[#8997a7]">
                    On this page
                </small>
            </div>

            <div class="grid h-[42px] w-[42px] shrink-0 place-items-center rounded-lg bg-[#dcf5e5] text-[#23834a]">
                <i class="fa-solid fa-circle-check"></i>
            </div>

        </div>

    </div>

    {{-- ORDERS PANEL --}}
    <section class="overflow-hidden rounded-[9px] border border-[#dfe7ef] bg-white">

        {{-- SEARCH TOOLBAR --}}
        <form
            action="{{ route('staff.orders.index') }}"
            method="GET"
            class="flex flex-wrap items-center gap-3 px-[18px] py-4"
        >

            <div class="relative min-w-[220px] flex-1 max-[480px]:w-full">

                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#8998a9]"></i>

                <input
                    type="search"
                    name="search"
                    placeholder="Search order or customer..."
                    value="{{ request('search') }}"
                    aria-label="Search orders"
                    class="h-10 w-full rounded-md border border-[#d9e2ec] bg-white pl-9 pr-3 text-xs text-[#26364a] outline-none transition focus:border-[#3985d5] focus:ring-2 focus:ring-[#3985d5]/10"
                >

            </div>

            @if($currentStatus !== 'all')
                <input
                    type="hidden"
                    name="status"
                    value="{{ $currentStatus }}"
                >
            @endif

            <button
                type="submit"
                class="h-10 rounded-md bg-[#1678db] px-5 text-xs font-semibold text-white transition hover:bg-[#1268bf]"
            >
                Search
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a
                    href="{{ route('staff.orders.index') }}"
                    class="flex h-10 items-center gap-2 rounded-md border border-[#d9e2ec] px-4 text-xs font-medium text-[#64758a] transition hover:bg-[#f5f7fa]"
                >
                    <i class="fa-solid fa-xmark"></i>
                    Clear
                </a>
            @endif

        </form>

        {{-- STATUS TABS --}}
        <div class="flex items-center gap-6 overflow-x-auto border-b border-[#e5ebf1] px-[18px] max-[480px]:gap-5">

            <a
                href="{{ route('staff.orders.index', array_filter([
                    'search' => request('search'),
                ])) }}"
                class="relative flex h-[43px] shrink-0 items-center text-xs font-semibold transition
                {{ $currentStatus === 'all'
                    ? 'text-[#1678db] after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-[#1678db]'
                    : 'text-[#78899c] hover:text-[#1678db]' }}"
            >
                All Orders
            </a>

            <a
                href="{{ route('staff.orders.index', array_filter([
                    'status' => 'pending',
                    'search' => request('search'),
                ])) }}"
                class="relative flex h-[43px] shrink-0 items-center text-xs font-semibold transition
                {{ $currentStatus === 'pending'
                    ? 'text-[#1678db] after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-[#1678db]'
                    : 'text-[#78899c] hover:text-[#1678db]' }}"
            >
                Pending
            </a>

            <a
                href="{{ route('staff.orders.index', array_filter([
                    'status' => 'processing',
                    'search' => request('search'),
                ])) }}"
                class="relative flex h-[43px] shrink-0 items-center text-xs font-semibold transition
                {{ $currentStatus === 'processing'
                    ? 'text-[#1678db] after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-[#1678db]'
                    : 'text-[#78899c] hover:text-[#1678db]' }}"
            >
                Processing
            </a>

            <a
                href="{{ route('staff.orders.index', array_filter([
                    'status' => 'completed',
                    'search' => request('search'),
                ])) }}"
                class="relative flex h-[43px] shrink-0 items-center text-xs font-semibold transition
                {{ $currentStatus === 'completed'
                    ? 'text-[#1678db] after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-[#1678db]'
                    : 'text-[#78899c] hover:text-[#1678db]' }}"
            >
                Completed
            </a>

        </div>

        {{-- TABLE HEADING --}}
        <div class="flex items-center justify-between gap-4 px-[18px] pb-4 pt-5">

            <div>
                <h3 class="text-[18px] font-bold text-[#173653]">
                    Order List
                </h3>

                <p class="mt-1 text-xs text-[#8997a7]">
                    View customer details and order progress.
                </p>
            </div>

            <span class="whitespace-nowrap text-xs font-medium text-[#718399]">
                {{ $totalOrders }} total
            </span>

        </div>

        {{-- ORDERS TABLE --}}
        <div class="mx-[18px] mb-[18px] overflow-x-auto rounded-lg border border-[#e1e7ee]">

            <table class="w-full min-w-[850px] border-collapse text-left">

                <thead class="bg-[#f5f7fa]">

                    <tr>
                        <th class="h-11 px-4 text-[10px] font-bold tracking-[0.3px] text-[#718399] uppercase">
                            Order Details
                        </th>

                        <th class="h-11 px-4 text-[10px] font-bold tracking-[0.3px] text-[#718399] uppercase">
                            Customer
                        </th>

                        <th class="h-11 px-4 text-[10px] font-bold tracking-[0.3px] text-[#718399] uppercase">
                            Service
                        </th>

                        <th class="h-11 px-4 text-[10px] font-bold tracking-[0.3px] text-[#718399] uppercase">
                            Weight
                        </th>

                        <th class="h-11 px-4 text-[10px] font-bold tracking-[0.3px] text-[#718399] uppercase">
                            Date Created
                        </th>

                        <th class="h-11 px-4 text-[10px] font-bold tracking-[0.3px] text-[#718399] uppercase">
                            Status
                        </th>

                        <th class="h-11 px-4 text-[10px] font-bold tracking-[0.3px] text-[#718399] uppercase">
                            Action
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($orders as $order)

                        @php
                            $status = strtolower($order->status ?? 'pending');

                            $customerName = $order->customer_name
                                ?: ($order->user?->name ?? 'Walk-in Customer');

                            $statusClass = preg_replace(
                                '/[^a-z0-9_-]/',
                                '',
                                $status
                            );
                        @endphp

                        <tr class="border-t border-[#e9eef3] transition hover:bg-[#f9fbfd]">

                            {{-- ORDER DETAILS --}}
                            <td class="px-4 py-[15px]">

                                <div class="flex items-center gap-3">

                                    <div class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-md bg-[#e4efff] text-sm text-[#2868bd]">
                                        <i class="fa-solid fa-shirt"></i>
                                    </div>

                                    <div>
                                        <strong class="block text-xs font-bold text-[#263b52]">
                                            LL-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                        </strong>

                                        <small class="mt-1 block text-[10px] text-[#8997a7]">
                                            Order #{{ $order->id }}
                                        </small>
                                    </div>

                                </div>

                            </td>

                            {{-- CUSTOMER --}}
                            <td class="px-4 py-[15px]">

                                <div class="flex items-center gap-[10px]">

                                    <div class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-full bg-[#e4efff] text-xs font-bold text-[#2868bd]">
                                        {{ strtoupper(substr($customerName, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">

                                        <strong class="block text-xs font-semibold text-[#263b52]">
                                            {{ $customerName }}
                                        </strong>

                                        <small class="mt-1 block text-[10px] text-[#8997a7]">
                                            {{ $order->user?->email ?? 'Walk-in order' }}
                                        </small>

                                    </div>

                                </div>

                            </td>

                            {{-- SERVICE --}}
                            <td class="px-4 py-[15px]">

                                <span class="text-xs font-medium text-[#52677e]">
                                    {{ $order->service?->name ?? 'Service unavailable' }}
                                </span>

                            </td>

                            {{-- WEIGHT --}}
                            <td class="px-4 py-[15px]">

                                <span class="text-xs text-[#52677e]">
                                    {{ $order->weight_kg !== null
                                        ? number_format((float) $order->weight_kg, 1).' kg'
                                        : '—' }}
                                </span>

                            </td>

                            {{-- DATE CREATED --}}
                            <td class="px-4 py-[15px]">

                                <div>
                                    <strong class="block text-xs font-medium text-[#52677e]">
                                        {{ $order->created_at?->format('M d, Y') ?? '—' }}
                                    </strong>

                                    <small class="mt-1 block text-[10px] text-[#8997a7]">
                                        {{ $order->created_at?->format('h:i A') ?? '' }}
                                    </small>
                                </div>

                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-[15px]">

                                <span class="inline-flex items-center gap-[6px] whitespace-nowrap rounded-md px-[9px] py-[6px] text-[10px] font-semibold capitalize

                                    @if($statusClass === 'pending')
                                        bg-[#fff1d8] text-[#a67119]

                                    @elseif(in_array($statusClass, ['washing', 'processing', 'in-progress', 'in_progress']))
                                        bg-[#e4efff] text-[#2868bd]

                                    @elseif($statusClass === 'drying')
                                        bg-[#eee8ff] text-[#7550b2]

                                    @elseif($statusClass === 'ready')
                                        bg-[#d9f4ef] text-[#138779]

                                    @elseif($statusClass === 'completed')
                                        bg-[#dcf5e5] text-[#23834a]

                                    @elseif($statusClass === 'cancelled')
                                        bg-[#fbe5e5] text-[#b54747]

                                    @else
                                        bg-[#edf1f5] text-[#64748b]
                                    @endif
                                ">

                                    <span class="h-[6px] w-[6px] shrink-0 rounded-full bg-current"></span>

                                    {{ ucfirst(str_replace('_', ' ', $status)) }}

                                </span>

                            </td>

                            {{-- ACTION --}}
                            <td class="px-4 py-[15px]">

                                <a
                                    href="{{ route('staff.orders.show', $order->id) }}"
                                    class="inline-flex items-center gap-2 rounded-md border border-[#d9e2ec] px-3 py-[7px] text-[11px] font-semibold text-[#2868bd] transition hover:border-[#1678db] hover:bg-[#e4efff]"
                                    aria-label="View order {{ $order->id }}"
                                >
                                    View

                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>

                            </td>

                        </tr>

                    @empty

                        {{-- EMPTY STATE --}}
                        <tr>
                            <td colspan="7">

                                <div class="px-5 py-[50px] text-center">

                                    <div class="mx-auto mb-4 grid h-[52px] w-[52px] place-items-center rounded-full bg-[#f1f5f9] text-xl text-[#8998a9]">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>

                                    <h3 class="text-[15px] font-bold text-[#263b52]">
                                        No orders found
                                    </h3>

                                    <p class="mt-2 text-xs text-[#8997a7]">
                                        There are no orders to display right now.
                                    </p>

                                    @if(request()->hasAny(['search', 'status']))
                                        <a
                                            href="{{ route('staff.orders.index') }}"
                                            class="mt-4 inline-block text-xs font-semibold text-[#1678db] hover:underline"
                                        >
                                            Clear search and filters
                                        </a>
                                    @endif

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="flex flex-wrap items-center justify-between gap-4 border-t border-[#e5ebf1] px-[18px] py-4">

            <div class="text-xs text-[#8997a7]">
                Showing {{ $orders->firstItem() ?? 0 }}
                to {{ $orders->lastItem() ?? 0 }}
                of {{ $totalOrders }} orders
            </div>

            <div class="pagination-links">
                {{ $orders->withQueryString()->links() }}
            </div>

        </div>

    </section>

</div>

@endsection
