@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
<div class="container-fluid inventory-page box-border m-0 w-full max-w-none px-5 py-6 font-['DM_Sans',sans-serif] text-[#263238]">

    <div class="mb-7 flex flex-wrap items-center justify-between gap-5">
        <div>
            <h2 class="mb-[7px] text-[28px] font-bold tracking-[-0.5px]">Inventory</h2>
            <p class="m-0 text-sm text-[#7b8491]">Manage laundry supplies and monitor stock levels.</p>
        </div>

        <a href="{{ route('admin.inventory.create') }}"
           class="inline-flex min-h-[42px] items-center justify-center gap-[9px] rounded-md border border-[#009b70] bg-[#00a878] px-[18px] text-[13px] font-semibold text-white no-underline transition-colors duration-150 hover:bg-[#008c64] hover:text-white">
            <i class="fas fa-plus"></i>
            Add Supply
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-md bg-[#d1e7dd] px-4 py-3 text-[13px] text-[#0f5132]">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-md bg-[#f8d7da] px-4 py-3 text-[13px] text-[#842029]">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- INVENTORY STATISTICS --}}
    <div class="mb-7 grid grid-cols-4 gap-[18px]">

        <div class="flex min-h-28 items-center justify-between gap-3.5 rounded-lg border border-[#e5e9e7] bg-white p-[22px]">
            <div>
                <small class="mb-[10px] block text-[13px] text-[#7b8491]">Total Supplies</small>
                <strong class="text-[28px] font-bold leading-none">{{ $stats['total'] }}</strong>
            </div>
            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-[7px] bg-[#e4f5ed] text-lg text-[#008c64]">
                <i class="fas fa-boxes-stacked"></i>
            </div>
        </div>

        <div class="flex min-h-28 items-center justify-between gap-3.5 rounded-lg border border-[#e5e9e7] bg-white p-[22px]">
            <div>
                <small class="mb-[10px] block text-[13px] text-[#7b8491]">In Stock</small>
                <strong class="text-[28px] font-bold leading-none">{{ $stats['in_stock'] }}</strong>
            </div>
            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-[7px] bg-[#e8f0fd] text-lg text-[#2869ca]">
                <i class="fas fa-box"></i>
            </div>
        </div>

        <div class="flex min-h-28 items-center justify-between gap-3.5 rounded-lg border border-[#e5e9e7] bg-white p-[22px]">
            <div>
                <small class="mb-[10px] block text-[13px] text-[#7b8491]">Low Stock</small>
                <strong class="text-[28px] font-bold leading-none">{{ $stats['low_stock'] }}</strong>
            </div>
            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-[7px] bg-[#fff1df] text-lg text-[#c47a19]">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
        </div>

        <div class="flex min-h-28 items-center justify-between gap-3.5 rounded-lg border border-[#e5e9e7] bg-white p-[22px]">
            <div>
                <small class="mb-[10px] block text-[13px] text-[#7b8491]">Out of Stock</small>
                <strong class="text-[28px] font-bold leading-none">{{ $stats['out_of_stock'] }}</strong>
            </div>
            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-[7px] bg-[#fce9e9] text-lg text-[#c74747]">
                <i class="fas fa-circle-exclamation"></i>
            </div>
        </div>

    </div>

    {{-- SUPPLY DIRECTORY --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e9e7] bg-white">

        <div class="flex flex-wrap items-center justify-between gap-5 border-b border-[#edf0f2] px-6 py-[22px]">
            <div>
                <h5 class="mb-1.5 text-[17px] font-bold">Supply Directory</h5>
                <p class="m-0 text-[13px] text-[#89919c]">Supplies currently recorded in your inventory</p>
            </div>

            <form method="GET" action="{{ route('admin.inventory.index') }}">
                <input
                    type="search"
                    name="search"
                    class="min-h-[42px] w-[280px] max-w-full rounded-md border border-[#dce3e0] bg-white px-[13px] py-[10px] text-[13px] outline-none focus:border-[#00a878] focus:ring-[3px] focus:ring-[#00a878]/10"
                    placeholder="Search supplies..."
                    value="{{ request('search') }}"
                >
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="m-0 w-full min-w-[760px] border-collapse align-middle">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap border-b border-[#e9ecef] bg-[#f7f9f8] px-[22px] py-[15px] text-left text-[11px] font-bold uppercase tracking-[0.4px] text-[#77818c]">Supply</th>
                        <th class="whitespace-nowrap border-b border-[#e9ecef] bg-[#f7f9f8] px-[22px] py-[15px] text-left text-[11px] font-bold uppercase tracking-[0.4px] text-[#77818c]">Category</th>
                        <th class="whitespace-nowrap border-b border-[#e9ecef] bg-[#f7f9f8] px-[22px] py-[15px] text-left text-[11px] font-bold uppercase tracking-[0.4px] text-[#77818c]">Quantity</th>
                        <th class="whitespace-nowrap border-b border-[#e9ecef] bg-[#f7f9f8] px-[22px] py-[15px] text-left text-[11px] font-bold uppercase tracking-[0.4px] text-[#77818c]">Minimum Stock</th>
                        <th class="whitespace-nowrap border-b border-[#e9ecef] bg-[#f7f9f8] px-[22px] py-[15px] text-left text-[11px] font-bold uppercase tracking-[0.4px] text-[#77818c]">Status</th>
                        <th class="whitespace-nowrap border-b border-[#e9ecef] bg-[#f7f9f8] px-[22px] py-[15px] text-left text-[11px] font-bold uppercase tracking-[0.4px] text-[#77818c]">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($items as $item)
                        @php
                            $statusClass = match($item->stock_status) {
                                'Available' => 'available',
                                'Low Stock' => 'low',
                                default => 'out',
                            };
                        @endphp

                        <tr class="hover:bg-[#fbfcfc]">
                            <td class="border-b border-[#f0f2f3] px-[22px] py-[19px] align-middle text-[13px] text-[#515b67]">
                                <span class="font-semibold text-[#303641]">
                                    {{ $item->name }}
                                </span>

                                @if($item->description)
                                    <small class="mt-1 block text-[#6c757d]">
                                        {{ $item->description }}
                                    </small>
                                @endif
                            </td>

                            <td class="border-b border-[#f0f2f3] px-[22px] py-[19px] align-middle text-[13px] text-[#515b67]">
                                {{ $item->category }}
                            </td>

                            <td class="border-b border-[#f0f2f3] px-[22px] py-[19px] align-middle text-[13px] text-[#515b67]">
                                <strong>
                                    {{ number_format((float) $item->quantity, 2) }}
                                </strong>
                                {{ $item->unit }}
                            </td>

                            <td class="border-b border-[#f0f2f3] px-[22px] py-[19px] align-middle text-[13px] text-[#515b67]">
                                {{ number_format((float) $item->minimum_stock, 2) }}
                                {{ $item->unit }}
                            </td>

                            <td class="border-b border-[#f0f2f3] px-[22px] py-[19px] align-middle text-[13px] text-[#515b67]">
                                <span @class([
                                    'inline-block whitespace-nowrap rounded-[5px] px-[10px] py-[7px] text-[11px] font-bold',
                                    'bg-[#e4f5ed] text-[#008c64]' => $statusClass === 'available',
                                    'bg-[#fff1d7] text-[#a66b0c]' => $statusClass === 'low',
                                    'bg-[#fce9e9] text-[#c74747]' => $statusClass === 'out',
                                ])>
                                    {{ $item->stock_status }}
                                </span>
                            </td>

                            <td class="border-b border-[#f0f2f3] px-[22px] py-[19px] align-middle text-[13px] text-[#515b67]">
                                <a
                                    href="{{ route('admin.inventory.edit', $item) }}"
                                    class="inline-flex min-h-[34px] items-center justify-center whitespace-nowrap rounded-[5px] border border-[#dce3e0] bg-white px-[13px] text-xs font-semibold text-[#008c64] no-underline transition-colors duration-150 hover:bg-[#e4f5ed] hover:text-[#008c64]"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="!border-0 px-5 py-12 text-center text-[#929aa4]">
                                <i class="fas fa-box-open mb-3 block text-2xl"></i>
                                <strong>No supplies found</strong>
                                <div class="mt-1 text-xs">
                                    Add your first supply to get started.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="border-t border-[#edf0f2] px-6 py-[18px]">
                {{ $items->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
