@extends('layouts.admin')

@section('title', 'Payments')
@section('page-heading', '')

@section('content')
@php
    $paymentRecords = $payments ?? collect();

    $totalPayments = $paymentRecords->total()
        ?? $paymentRecords->count();

    $paidAmount = $paidAmount ?? 0;
    $pendingPayments = $pendingPayments ?? 0;
@endphp

<div class="text-[13px] text-[#294358] [font-family:'DM_Sans',Arial,sans-serif]">

    {{-- Page Heading --}}
    <div class="mb-[22px] flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="m-0 text-[25px] font-bold text-[#183b55]">
                Payments
            </h2>
            <p class="mt-[6px] text-[12px] text-[#7b8fa1]">
                Review payment records and monitor customer transactions.
            </p>
        </div>
    </div>

    {{-- Payment Summary --}}
    <div class="mb-[22px] grid grid-cols-3 gap-[15px] max-[900px]:grid-cols-2 max-[550px]:grid-cols-1">

        <div class="rounded-[5px] border border-[#dfe7ee] bg-white px-5 py-[19px]">
            <div class="text-[12px] text-[#7b8fa1]">
                Total Payment Records
            </div>
            <div class="mt-[10px] text-[25px] font-bold text-[#183b55]">
                {{ number_format($totalPayments) }}
            </div>
            <div class="mt-[5px] text-[11px] text-[#8a9aa8]">
                Records matching the current filter
            </div>
        </div>

        <div class="rounded-[5px] border border-[#dfe7ee] bg-white px-5 py-[19px]">
            <div class="text-[12px] text-[#7b8fa1]">
                Total Amount Paid
            </div>
            <div class="mt-[10px] text-[25px] font-bold text-[#183b55]">
                ₱{{ number_format((float) $paidAmount, 2) }}
            </div>
            <div class="mt-[5px] text-[11px] text-[#8a9aa8]">
                Confirmed payments
            </div>
        </div>

        <div class="rounded-[5px] border border-[#dfe7ee] bg-white px-5 py-[19px]">
            <div class="text-[12px] text-[#7b8fa1]">
                Pending Payments
            </div>
            <div class="mt-[10px] text-[25px] font-bold text-[#183b55]">
                {{ number_format((int) $pendingPayments) }}
            </div>
            <div class="mt-[5px] text-[11px] text-[#8a9aa8]">
                Awaiting payment confirmation
            </div>
        </div>

    </div>

    {{-- Payment Records Panel --}}
    <section class="overflow-hidden rounded-[5px] border border-[#dfe7ee] bg-white">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#e5ebf0] px-[18px] py-[17px]">
            <div>
                <h3 class="m-0 text-[14px] font-bold text-[#21435d]">
                    Payment Records
                </h3>
                <p class="mt-[5px] text-[11px] text-[#8494a2]">
                    Payment history associated with laundry orders.
                </p>
            </div>
        </div>

        {{-- Search and Filter --}}
        <form method="GET"
              action="{{ route('admin.payments.index') }}"
              class="flex gap-[9px] border-b border-[#e8edf2] px-[18px] py-[15px] max-[550px]:flex-col">

            <label class="flex h-[38px] min-w-0 flex-1 items-center gap-[9px] rounded-[4px] border border-[#dbe4ec] px-[11px] text-[#7b8fa1] focus-within:border-[#2679b8]">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search payment, order, or customer..."
                    class="w-full border-0 bg-transparent p-0 text-[12px] text-[#294358] outline-none focus:ring-0"
                >
            </label>

            <select
                name="status"
                class="h-[38px] rounded-[4px] border border-[#dbe4ec] bg-white px-[10px] text-[12px] text-[#526c80] outline-none focus:border-[#2679b8] focus:ring-0"
            >
                <option value="all" @selected(request('status', 'all') === 'all')>
                    All Statuses
                </option>
                <option value="paid" @selected(request('status') === 'paid')>
                    Paid
                </option>
                <option value="pending" @selected(request('status') === 'pending')>
                    Pending
                </option>
                <option value="failed" @selected(request('status') === 'failed')>
                    Failed
                </option>
            </select>

            <button
                type="submit"
                class="inline-flex min-h-[38px] items-center justify-center gap-[7px] rounded-[4px] border border-[#2679b8] bg-[#2679b8] px-[14px] text-[12px] font-bold text-white transition-colors hover:bg-[#1e689f] max-[550px]:w-full"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
                Search
            </button>
        </form>

        {{-- Payments Table --}}
        <div class="overflow-x-auto px-4">
            <table class="w-full min-w-[750px] border-collapse">

                <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-[#f1f5f9] px-3 py-[13px] text-left text-[10px] font-bold uppercase text-[#526b80]">
                            Payment ID
                        </th>
                        <th class="whitespace-nowrap bg-[#f1f5f9] px-3 py-[13px] text-left text-[10px] font-bold uppercase text-[#526b80]">
                            Order ID
                        </th>
                        <th class="whitespace-nowrap bg-[#f1f5f9] px-3 py-[13px] text-left text-[10px] font-bold uppercase text-[#526b80]">
                            Customer
                        </th>
                        <th class="whitespace-nowrap bg-[#f1f5f9] px-3 py-[13px] text-left text-[10px] font-bold uppercase text-[#526b80]">
                            Amount
                        </th>
                        <th class="whitespace-nowrap bg-[#f1f5f9] px-3 py-[13px] text-left text-[10px] font-bold uppercase text-[#526b80]">
                            Method
                        </th>
                        <th class="whitespace-nowrap bg-[#f1f5f9] px-3 py-[13px] text-left text-[10px] font-bold uppercase text-[#526b80]">
                            Status
                        </th>
                        <th class="whitespace-nowrap bg-[#f1f5f9] px-3 py-[13px] text-left text-[10px] font-bold uppercase text-[#526b80]">
                            Date
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($paymentRecords as $payment)
                        @php
                            $paymentStatus = strtolower(
                                (string) ($payment->status ?? 'pending')
                            );

                            $paymentStatusClass = match ($paymentStatus) {
                                'paid', 'completed', 'success', 'successful' => 'paid',
                                'failed' => 'failed',
                                'cancelled', 'canceled' => 'cancelled',
                                default => '',
                            };

                            $paymentStatusLabel = match ($paymentStatus) {
                                'paid', 'completed', 'success', 'successful' => 'Paid',
                                'failed' => 'Failed',
                                'cancelled', 'canceled' => 'Cancelled',
                                default => ucfirst($paymentStatus),
                            };

                            $orderId = $payment->booking_id
                                ?? $payment->order_id
                                ?? null;

                            $customerName = $payment->customer_name
                                ?? $payment->booking?->customer_name
                                ?? $payment->booking?->user?->name
                                ?? $payment->user?->name
                                ?? 'N/A';

                            $paymentDate = $payment->created_at
                                ? $payment->created_at->format('M d, Y')
                                : '—';
                        @endphp

                        <tr class="hover:bg-[#f8fbfd]">
                            <td class="whitespace-nowrap border-b border-[#e9eef3] px-3 py-[15px] text-[12px] font-bold text-[#214761]">
                                #PAY-{{ str_pad((string) ($payment->id ?? 0), 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="border-b border-[#e9eef3] px-3 py-[15px] text-[12px] text-[#61798c]">
                                {{ $orderId
                                    ? '#LL-' . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT)
                                    : '—' }}
                            </td>

                            <td class="border-b border-[#e9eef3] px-3 py-[15px] text-[12px] text-[#61798c]">
                                {{ $customerName }}
                            </td>

                            <td class="whitespace-nowrap border-b border-[#e9eef3] px-3 py-[15px] text-[12px] font-bold text-[#1f4c6b]">
                                ₱{{ number_format((float) ($payment->amount ?? $payment->total_amount ?? 0), 2) }}
                            </td>

                            <td class="border-b border-[#e9eef3] px-3 py-[15px] text-[12px] text-[#526c80]">
                                {{ $payment->payment_method
                                    ?? $payment->method
                                    ?? 'Not specified' }}
                            </td>

                            <td class="border-b border-[#e9eef3] px-3 py-[15px] text-[12px]">
                                <span @class([
                                    'inline-block whitespace-nowrap rounded-[3px] px-2 py-[5px] text-[10px] font-bold',
                                    'bg-[#e6f4e9] text-[#287447]' => $paymentStatusClass === 'paid',
                                    'bg-[#fae9e9] text-[#a13f3f]' => in_array($paymentStatusClass, ['failed', 'cancelled']),
                                    'bg-[#fff2d5] text-[#936b16]' => $paymentStatusClass === '',
                                ])>
                                    {{ $paymentStatusLabel }}
                                </span>
                            </td>

                            <td class="border-b border-[#e9eef3] px-3 py-[15px] text-[12px] text-[#61798c]">
                                {{ $paymentDate }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-[42px] text-center text-[12px] leading-[1.8] text-[#7b8fa1]">
                                <div>No payment records found.</div>
                                <div>Payment records will appear here when available in the database.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#e8edf2] px-[18px] py-[15px] text-[11px] text-[#7b8fa1]">
            <span>
                @if(method_exists($paymentRecords, 'firstItem'))
                    Showing {{ $paymentRecords->firstItem() ?? 0 }}–{{ $paymentRecords->lastItem() ?? 0 }}
                    of {{ $paymentRecords->total() }} records
                @else
                    Showing {{ $paymentRecords->count() }} records
                @endif
            </span>

            @if(method_exists($paymentRecords, 'links'))
                {{ $paymentRecords->withQueryString()->links() }}
            @endif
        </div>

    </section>
</div>
@endsection
