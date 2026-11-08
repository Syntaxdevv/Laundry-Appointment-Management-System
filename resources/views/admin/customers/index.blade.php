@extends('layouts.admin')

@section('title', 'Customers')
@section('page-heading', '')

@section('content')
<div class="customers-page w-full min-w-0 font-['DM_Sans',sans-serif] text-[#19384e]">

    {{-- Page Heading --}}
    <div class="mb-[25px]">
        <h2 class="mb-[6px] text-[27px] font-extrabold tracking-[-.6px] text-[#12364f]">
            Customers Management
        </h2>

        <p class="m-0 text-sm text-[#71869a]">
            Manage customer information and laundry activity.
        </p>
    </div>

    {{-- Statistics --}}
    <div class="mb-6 grid grid-cols-2 gap-[18px] max-[520px]:grid-cols-1 max-[760px]:gap-3">

        {{-- Total Customers --}}
        <div class="flex items-start justify-between gap-[15px] rounded-[9px] border border-[#e5ebf1] bg-white px-[25px] py-[23px] max-[760px]:p-[18px]">
            <div>
                <div class="text-[13px] font-semibold text-[#71869a]">
                    Total Customers
                </div>

                <div class="mt-[13px] text-[32px] font-extrabold leading-[1.2] text-[#12364f] max-[760px]:text-[27px]">
                    {{ number_format($totalCustomers) }}
                </div>

                <div class="mt-[7px] text-xs text-[#91a0ae]">
                    Registered customer accounts
                </div>
            </div>

            <div class="flex h-[43px] w-[43px] shrink-0 items-center justify-center rounded-lg bg-[#eaf4fc] text-[17px] text-[#2479b9]">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        {{-- Customers With Orders --}}
        <div class="flex items-start justify-between gap-[15px] rounded-[9px] border border-[#e5ebf1] bg-white px-[25px] py-[23px] max-[760px]:p-[18px]">
            <div>
                <div class="text-[13px] font-semibold text-[#71869a]">
                    Customers With Orders
                </div>

                <div class="mt-[13px] text-[32px] font-extrabold leading-[1.2] text-[#12364f] max-[760px]:text-[27px]">
                    {{ number_format($customersWithOrders) }}
                </div>

                <div class="mt-[7px] text-xs text-[#91a0ae]">
                    Customers who placed laundry orders
                </div>
            </div>

            <div class="flex h-[43px] w-[43px] shrink-0 items-center justify-center rounded-lg bg-[#eaf4fc] text-[17px] text-[#2479b9]">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
        </div>

    </div>

    {{-- Customer Table --}}
    <section class="overflow-hidden rounded-[9px] border border-[#e5ebf1] bg-white">

        {{-- Search Toolbar --}}
        <form method="GET"
              action="{{ route('admin.customers.index') }}"
              class="flex items-center gap-3 border-b border-[#e5ebf1] px-5 py-[19px] max-[520px]:flex-col max-[520px]:items-stretch max-[520px]:p-[14px]">

            <label class="flex h-[45px] min-w-0 flex-1 items-center gap-[11px] rounded-[7px] border border-[#dce6ef] px-[14px] text-[#8296a8] transition-all focus-within:border-[#2479b9] focus-within:ring-[3px] focus-within:ring-[#2479b9]/10">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search customer name or email..."
                    aria-label="Search customer name or email"
                    class="h-full w-full min-w-0 border-0 bg-transparent text-[13px] text-[#12364f] outline-none placeholder:text-[#94a3b2]"
                >
            </label>

            <button type="submit"
                    class="inline-flex h-[45px] items-center justify-center gap-[9px] rounded-[7px] border border-[#2479b9] bg-[#2479b9] px-5 text-[13px] font-bold text-white transition-colors hover:border-[#1b659e] hover:bg-[#1b659e] max-[520px]:w-full">
                <i class="fa-solid fa-magnifying-glass"></i>
                Search
            </button>

        </form>

        {{-- Table --}}
        <div class="w-full overflow-x-auto px-[18px]">
            <table class="w-full min-w-[650px] border-separate border-spacing-0">

                <thead>
                    <tr>
                        <th class="rounded-l-[5px] bg-[#f1f6fa] px-[17px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[.5px] text-[#536d82] whitespace-nowrap">
                            Customer
                        </th>

                        <th class="bg-[#f1f6fa] px-[17px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[.5px] text-[#536d82] whitespace-nowrap">
                            Email
                        </th>

                        <th class="bg-[#f1f6fa] px-[17px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[.5px] text-[#536d82] whitespace-nowrap">
                            Total Orders
                        </th>

                        <th class="rounded-r-[5px] bg-[#f1f6fa] px-[17px] py-[15px] text-left text-[10px] font-extrabold uppercase tracking-[.5px] text-[#536d82] whitespace-nowrap">
                            Latest Order
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $customer)

                        @php
                            $customerName = $customer->user?->name ?? 'Unknown Customer';
                            $customerEmail = $customer->user?->email ?? '—';
                        @endphp

                        <tr class="transition-colors hover:bg-[#f8fbfd]">

                            {{-- Customer --}}
                            <td class="border-b border-[#edf1f5] px-[17px] py-[17px] align-middle">
                                <div class="flex min-w-[190px] items-center gap-3">

                                    <span class="flex h-10 w-10 flex-[0_0_40px] items-center justify-center rounded-full border border-[#d9eafd] bg-[#e8f3ff] text-sm font-extrabold text-[#2479b9]">
                                        {{ mb_strtoupper(mb_substr($customerName, 0, 1)) }}
                                    </span>

                                    <div class="min-w-0">
                                        <span class="block break-words text-[13px] font-bold text-[#1c3c56]">
                                            {{ $customerName }}
                                        </span>

                                        <span class="mt-1 block text-[11px] text-[#91a0ae]">
                                            Customer account
                                        </span>
                                    </div>

                                </div>
                            </td>

                            {{-- Email --}}
                            <td class="border-b border-[#edf1f5] px-[17px] py-[17px] align-middle text-[13px] text-[#60788e]">
                                <span class="break-words">
                                    {{ $customerEmail }}
                                </span>
                            </td>

                            {{-- Total Orders --}}
                            <td class="border-b border-[#edf1f5] px-[17px] py-[17px] align-middle">
                                <span class="inline-flex min-h-[29px] min-w-[34px] items-center justify-center rounded-md border border-[#dceaf6] bg-[#eff7fd] px-[9px] py-1 text-xs font-extrabold text-[#216da6]">
                                    {{ number_format($customer->total_orders ?? 0) }}
                                </span>
                            </td>

                            {{-- Latest Order --}}
                            <td class="border-b border-[#edf1f5] px-[17px] py-[17px] align-middle text-[13px] text-[#60788e] whitespace-nowrap">
                                @if($customer->latest_order_at)
                                    {{ \Illuminate\Support\Carbon::parse($customer->latest_order_at)->format('M d, Y') }}
                                @else
                                    —
                                @endif
                            </td>

                        </tr>

                    @empty

                        {{-- Empty State --}}
                        <tr>
                            <td colspan="4" class="!border-b-0 px-5 py-[55px] text-center">
                                <i class="fa-solid fa-users mb-3 block text-[29px] text-[#a0b2c1]"></i>

                                <strong class="mb-[5px] block text-sm text-[#38566e]">
                                    No customers found
                                </strong>

                                <span class="text-xs text-[#8397a8]">
                                    Try searching with a different name or email.
                                </span>
                            </td>
                        </tr>

                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Footer and Pagination --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#e5ebf1] px-5 py-4 text-xs text-[#788da1] max-[520px]:flex-col max-[520px]:items-start">

            <span>
                Showing {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }}
                of {{ $customers->total() }} customers
            </span>

            <div class="ml-auto max-[520px]:ml-0">
                {{ $customers->links('pagination::simple-bootstrap-5') }}
            </div>

        </div>

    </section>
</div>
@endsection
