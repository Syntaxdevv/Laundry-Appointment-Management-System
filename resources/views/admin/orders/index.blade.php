@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
@php
    $orders = $orders ?? collect();

    $orderItems = collect(
        $orders instanceof \Illuminate\Pagination\AbstractPaginator
            ? $orders->items()
            : $orders
    );

    $totalOrders = method_exists($orders, 'total')
        ? $orders->total()
        : $orderItems->count();

    $statusCounts = $statusCounts ?? [
        'pending' => $orderItems->where('status', 'pending')->count(),
        'washing' => $orderItems->where('status', 'washing')->count()
            + $orderItems->where('status', 'processing')->count(),
        'completed' => $orderItems->where('status', 'completed')->count(),
        'cancelled' => $orderItems->whereIn('status', ['cancelled', 'canceled'])->count(),
    ];

    $currentStatus = strtolower(request('status', 'all'));

    $statusTabs = [
        'all' => ['label' => 'All Orders', 'dot' => ''],
        'pending' => ['label' => 'Pending', 'dot' => 'pending'],
        'washing' => ['label' => 'Washing', 'dot' => 'washing'],
        'drying' => ['label' => 'Drying', 'dot' => 'drying'],
        'folding' => ['label' => 'Folding', 'dot' => 'folding'],
        'completed' => ['label' => 'Completed', 'dot' => 'completed'],
        'cancelled' => ['label' => 'Cancelled', 'dot' => 'cancelled'],
    ];

    $statusColors = [
        'pending' => 'border-[#f6e3b8] bg-[#fff5df] text-[#a36a0c]',
        'washing' => 'border-[#d5e8fa] bg-[#eaf4ff] text-[#286b9f]',
        'drying' => 'border-[#d5e8fa] bg-[#eaf4ff] text-[#286b9f]',
        'processing' => 'border-[#d5e8fa] bg-[#eaf4ff] text-[#286b9f]',
        'ready' => 'border-[#cfead9] bg-[#e8f7ee] text-[#267849]',
        'completed' => 'border-[#cfead9] bg-[#e8f7ee] text-[#267849]',
        'cancelled' => 'border-[#f3d6da] bg-[#fff0f1] text-[#b33b48]',
        'canceled' => 'border-[#f3d6da] bg-[#fff0f1] text-[#b33b48]',
    ];

    $tabDotColors = [
        'pending' => 'bg-[#e4a11b]',
        'washing' => 'bg-[#2999ed]',
        'drying' => 'bg-[#7652df]',
        'folding' => 'bg-[#0e9f9a]',
        'completed' => 'bg-[#27ae60]',
        'cancelled' => 'bg-[#e34d4d]',
    ];
@endphp

<div class="mx-auto w-full max-w-[1600px] font-['DM_Sans',sans-serif] text-[#19384e]">

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h2 class="mb-[7px] text-[27px] font-extrabold tracking-[-0.5px] text-[#173b59]">
                Orders Management
            </h2>

            <p class="m-0 text-[13px] text-[#7890a5]">
                Manage and track all laundry orders in one place.
            </p>
        </div>

        <div class="text-xs text-[#71869a]">
            <i class="fa-solid fa-receipt"></i>
            {{ number_format($totalOrders) }} total orders
        </div>
    </div>

    <div class="mb-[22px] grid grid-cols-4 gap-4">

        <div class="flex min-w-0 items-center gap-[15px] rounded-[9px] border border-[#e0eaf2] bg-white p-5 shadow-[0_3px_12px_rgba(25,56,78,0.025)]">
            <div class="flex h-12 w-12 flex-[0_0_48px] items-center justify-center rounded-[10px] bg-[#e3f1ff] text-[21px] text-[#2479b9]">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>

            <div>
                <div class="mb-[5px] text-xs text-[#758ba1]">
                    Total Orders
                </div>

                <div class="text-[25px] font-extrabold leading-[1.1] text-[#173b59]">
                    {{ number_format($totalOrders) }}
                </div>

                <div class="mt-[6px] text-[10px] text-[#8a9caf]">
                    All recorded orders
                </div>
            </div>
        </div>

        <div class="flex min-w-0 items-center gap-[15px] rounded-[9px] border border-[#e0eaf2] bg-white p-5 shadow-[0_3px_12px_rgba(25,56,78,0.025)]">
            <div class="flex h-12 w-12 flex-[0_0_48px] items-center justify-center rounded-[10px] bg-[#fff1d8] text-[21px] text-[#d58b13]">
                <i class="fa-regular fa-clock"></i>
            </div>

            <div>
                <div class="mb-[5px] text-xs text-[#758ba1]">
                    Pending Orders
                </div>

                <div class="text-[25px] font-extrabold leading-[1.1] text-[#173b59]">
                    {{ number_format($statusCounts['pending'] ?? 0) }}
                </div>

                <div class="mt-[6px] text-[10px] text-[#8a9caf]">
                    Awaiting processing
                </div>
            </div>
        </div>

        <div class="flex min-w-0 items-center gap-[15px] rounded-[9px] border border-[#e0eaf2] bg-white p-5 shadow-[0_3px_12px_rgba(25,56,78,0.025)]">
            <div class="flex h-12 w-12 flex-[0_0_48px] items-center justify-center rounded-[10px] bg-[#d9f6f0] text-[21px] text-[#0c9b8b]">
                <i class="fa-solid fa-soap"></i>
            </div>

            <div>
                <div class="mb-[5px] text-xs text-[#758ba1]">
                    In Progress
                </div>

                <div class="text-[25px] font-extrabold leading-[1.1] text-[#173b59]">
                    {{ number_format($statusCounts['washing'] ?? 0) }}
                </div>

                <div class="mt-[6px] text-[10px] text-[#8a9caf]">
                    Currently being processed
                </div>
            </div>
        </div>

        <div class="flex min-w-0 items-center gap-[15px] rounded-[9px] border border-[#e0eaf2] bg-white p-5 shadow-[0_3px_12px_rgba(25,56,78,0.025)]">
            <div class="flex h-12 w-12 flex-[0_0_48px] items-center justify-center rounded-[10px] bg-[#ffe5e3] text-[21px] text-[#dc4545]">
                <i class="fa-regular fa-circle-xmark"></i>
            </div>

            <div>
                <div class="mb-[5px] text-xs text-[#758ba1]">
                    Cancelled
                </div>

                <div class="text-[25px] font-extrabold leading-[1.1] text-[#173b59]">
                    {{ number_format($statusCounts['cancelled'] ?? 0) }}
                </div>

                <div class="mt-[6px] text-[10px] text-[#8a9caf]">
                    Cancelled orders
                </div>
            </div>
        </div>

    </div>

    <section class="w-full overflow-hidden rounded-[9px] border border-[#e0eaf2] bg-white shadow-[0_4px_16px_rgba(25,56,78,0.025)]">

        <form
            method="GET"
            action="{{ route('admin.orders.index') }}"
            class="flex items-center gap-3 border-b border-[#e8eef4] p-4"
            id="ordersSearchForm"
        >
            <label class="flex h-[42px] min-w-[180px] flex-1 items-center gap-[11px] rounded-md border border-[#dce7f0] px-[14px] text-[#6e879e] transition-colors duration-150 focus-within:border-[#3185c5]">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="search"
                    name="search"
                    id="ordersSearch"
                    value="{{ request('search') }}"
                    placeholder="Search order number, customer name, or service..."
                    autocomplete="off"
                    class="h-full w-full min-w-0 border-0 bg-transparent p-0 text-xs text-[#25445f] outline-none placeholder:text-[#91a3b4] focus:border-0 focus:ring-0"
                >
            </label>

            <select
                name="status"
                id="ordersStatus"
                class="h-[42px] w-[150px] rounded-md border border-[#dce7f0] bg-white px-3 text-xs text-[#526b81] outline-none focus:border-[#3185c5] focus:ring-0"
            >
                @foreach([
                    'all' => 'All Statuses',
                    'pending' => 'Pending',
                    'washing' => 'Washing',
                    'drying' => 'Drying',
                    'processing' => 'Processing',
                    'ready' => 'Ready',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled'
                ] as $value => $label)
                    <option value="{{ $value }}" @selected($currentStatus === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button
                type="submit"
                class="inline-flex min-h-[42px] items-center justify-center gap-2 whitespace-nowrap rounded-md border border-[#2479b9] bg-[#2479b9] px-[17px] text-xs font-bold text-white transition-colors duration-150 hover:border-[#195f94] hover:bg-[#195f94]"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
                Search
            </button>
        </form>

        <div class="flex items-center gap-[9px] overflow-x-auto px-4 pb-[15px]">
            @foreach($statusTabs as $value => $tab)
                <a
                    href="{{ route('admin.orders.index', array_filter([
                        'search' => request('search'),
                        'status' => $value === 'all' ? null : $value,
                    ])) }}"
                    class="inline-flex min-h-[34px] shrink-0 items-center gap-[7px] rounded-md border px-[14px] text-[11px] font-semibold no-underline transition-colors {{ $currentStatus === $value ? 'border-[#2479b9] bg-[#2479b9] text-white' : 'border-[#e0e9f1] bg-white text-[#59758d] hover:border-[#9fc7e7] hover:bg-[#f2f8fd] hover:text-[#2479b9]' }}"
                >
                    @if($tab['dot'] !== '')
                        <span class="h-[7px] w-[7px] rounded-full {{ $currentStatus === $value ? 'bg-white' : ($tabDotColors[$tab['dot']] ?? 'bg-[#a1afbd]') }}"></span>
                    @endif

                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        <div class="w-full overflow-x-auto px-[15px]">
            <table class="w-full min-w-[920px] border-separate border-spacing-0 whitespace-nowrap">
                <thead class="bg-[#f0f5fa]">
                    <tr>
                        <th class="rounded-tl-[7px] border-y border-[#e3ebf3] px-[14px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[0.35px] text-[#49657d]">
                            Order ID
                        </th>

                        <th class="border-y border-[#e3ebf3] px-[14px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[0.35px] text-[#49657d]">
                            Customer
                        </th>

                        <th class="border-y border-[#e3ebf3] px-[14px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[0.35px] text-[#49657d]">
                            Service
                        </th>

                        <th class="border-y border-[#e3ebf3] px-[14px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[0.35px] text-[#49657d]">
                            Weight
                        </th>

                        <th class="border-y border-[#e3ebf3] px-[14px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[0.35px] text-[#49657d]">
                            Order Date
                        </th>

                        <th class="border-y border-[#e3ebf3] px-[14px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[0.35px] text-[#49657d]">
                            Total
                        </th>

                        <th class="border-y border-[#e3ebf3] px-[14px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[0.35px] text-[#49657d]">
                            Status
                        </th>

                        <th class="rounded-tr-[7px] border-y border-[#e3ebf3] px-[14px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[0.35px] text-[#49657d]">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        @php
                            $status = strtolower(trim($order->status ?? 'pending'));
                            $statusColor = $statusColors[$status] ?? 'border-transparent bg-[#eaf4ff] text-[#286b9f]';
                        @endphp

                        <tr class="bg-white transition-colors duration-150 even:bg-[#fbfcfe] hover:bg-[#f0f7fd]">
                            <td class="border-b border-[#e9eff5] px-[14px] py-[14px] text-xs font-extrabold text-[#214d70]">
                                #{{ $order->id ?? $order->booking_id ?? '—' }}
                            </td>

                            <td class="border-b border-[#e9eff5] px-[14px] py-[14px] text-xs font-bold text-[#203f59]">
                                {{ $order->customer_name ?? $order->user?->name ?? 'Customer' }}
                            </td>

                            <td class="border-b border-[#e9eff5] px-[14px] py-[14px] text-xs text-[#617d94]">
                                {{ $order->service?->name ?? $order->service_name ?? 'Laundry Service' }}
                            </td>

                            <td class="border-b border-[#e9eff5] px-[14px] py-[14px] text-xs text-[#5c768d]">
                                {{ isset($order->weight_kg) ? number_format((float) $order->weight_kg, 1) . ' kg' : '—' }}
                            </td>

                            <td class="border-b border-[#e9eff5] px-[14px] py-[14px] text-xs text-[#5c768d]">
                                {{ $order->created_at?->format('M d, Y') ?? '—' }}
                            </td>

                            <td class="border-b border-[#e9eff5] px-[14px] py-[14px] text-xs font-extrabold text-[#234660]">
                                ₱{{ number_format((float) ($order->total_price ?? 0), 2) }}
                            </td>

                            <td class="border-b border-[#e9eff5] px-[14px] py-[14px] text-xs text-[#5c768d]">
                                <span class="inline-flex items-center gap-[6px] rounded-[5px] border px-[9px] py-[6px] text-[10px] font-bold capitalize before:h-[6px] before:w-[6px] before:shrink-0 before:rounded-full before:bg-current before:content-[''] {{ $statusColor }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="border-b border-[#e9eff5] px-[14px] py-[14px]">
                                <div class="flex items-center gap-[6px]">
                                    <a
                                        href="{{ route('admin.orders.show', ['booking' => $order->id ?? $order->booking_id]) }}"
                                        class="inline-flex min-h-8 items-center justify-center gap-[6px] rounded-[5px] border border-[#d9e6f0] bg-white px-[10px] text-[11px] font-bold text-[#2877b5] no-underline hover:border-[#9fc5e4] hover:bg-[#eff7fe]"
                                    >
                                        <i class="fa-regular fa-eye"></i>
                                        View
                                    </a>

                                    <a
                                        href="{{ route('admin.orders.edit', ['booking' => $order->id ?? $order->booking_id]) }}"
                                        class="inline-flex min-h-8 items-center justify-center gap-[6px] rounded-[5px] border border-[#2479b9] bg-[#2479b9] px-[10px] text-[11px] font-bold text-white no-underline hover:border-[#195f94] hover:bg-[#195f94]"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="whitespace-normal px-5 py-12 text-center text-[#8195a7]">
                                <i class="fa-regular fa-folder-open mb-3 block text-[28px] text-[#9fb2c2]"></i>

                                <p class="m-0 text-[13px]">
                                    No orders found. Try changing your search or status filter.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-[14px] border-t border-[#e8eef4] bg-white px-5 py-[15px] text-[11px] text-[#758ba0]">
            <span>
                @if(method_exists($orders, 'total'))
                    Showing {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }}
                    of {{ number_format($orders->total()) }} orders
                @else
                    Showing {{ $orderItems->count() }} orders
                @endif
            </span>

            @if(method_exists($orders, 'links'))
                <div class="ml-auto [&_nav>div]:flex [&_nav>div]:flex-wrap [&_nav>div]:items-center [&_nav>div]:gap-[5px] [&_nav>div:first-child]:hidden [&_nav_a]:inline-flex [&_nav_a]:min-h-[34px] [&_nav_a]:min-w-[34px] [&_nav_a]:items-center [&_nav_a]:justify-center [&_nav_a]:rounded-[5px] [&_nav_a]:border [&_nav_a]:border-[#dce6ef] [&_nav_a]:bg-white [&_nav_a]:px-[9px] [&_nav_a]:py-[5px] [&_nav_a]:text-[11px] [&_nav_a]:text-[#557189] [&_nav_a]:no-underline [&_nav_a:hover]:border-[#9fc5e4] [&_nav_a:hover]:bg-[#eff7fe] [&_nav_a:hover]:text-[#1976c9] [&_nav_span[aria-current=page]>span]:inline-flex [&_nav_span[aria-current=page]>span]:min-h-[34px] [&_nav_span[aria-current=page]>span]:min-w-[34px] [&_nav_span[aria-current=page]>span]:items-center [&_nav_span[aria-current=page]>span]:justify-center [&_nav_span[aria-current=page]>span]:rounded-[5px] [&_nav_span[aria-current=page]>span]:border [&_nav_span[aria-current=page]>span]:border-[#2479b9] [&_nav_span[aria-current=page]>span]:bg-[#2479b9] [&_nav_span[aria-current=page]>span]:px-[9px] [&_nav_span[aria-current=page]>span]:py-[5px] [&_nav_span[aria-current=page]>span]:text-[11px] [&_nav_span[aria-current=page]>span]:text-white [&_nav_svg]:h-[15px] [&_nav_svg]:w-[15px] [&_nav_p]:m-0">
                    {{ $orders->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
                </div>
            @endif
        </div>

    </section>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#ordersStatus').on('change', function () {
            $('#ordersSearchForm').trigger('submit');
        });
    });
</script>
@endpush