
@extends('layouts.delivery')

@section('title', 'My Deliveries')
@section('page-heading', '')

@section('content')

<div class="w-full min-w-0 py-4 font-['DM_Sans',sans-serif] text-[#252A34]">

    {{-- Page Header --}}
    <div class="mb-7 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="mb-1 text-[25px] font-bold text-[#252A34]">
                My Deliveries
            </h3>
            <p class="m-0 text-sm text-[#7B8491]">
                Manage your assigned pickup and delivery orders.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 rounded-md bg-[#E6F5EF] px-4 py-3 text-[13px] font-semibold text-[#008C64]">
            <i class="fas fa-truck"></i>
            <span>{{ $deliveries->total() }} Active Orders</span>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-2 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-[13px] text-green-700">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Error Message --}}
    @if($errors->any())
        <div class="mb-5 flex items-center gap-2 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-[13px] text-red-700">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- Deliveries Card --}}
    <div class="overflow-hidden rounded-lg border border-[#E9ECEF] bg-white">

        {{-- Card Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#EDF0F2] px-6 py-5">
            <div>
                <h5 class="m-0 text-base font-bold text-[#252A34]">
                    Assigned Deliveries
                </h5>
                <p class="mt-1 text-xs text-[#89919C]">
                    Completed laundry orders assigned to your account
                </p>
            </div>

            <span class="text-xs text-[#89919C]">
                Showing {{ $deliveries->count() }}
                of {{ $deliveries->total() }} orders
            </span>
        </div>

        {{-- Table --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[850px] border-collapse text-left">
                <thead>
                    <tr class="bg-[#F7F9F8]">
                        <th class="whitespace-nowrap border-b border-[#E9ECEF] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#77818C]">
                            Booking ID
                        </th>
                        <th class="whitespace-nowrap border-b border-[#E9ECEF] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#77818C]">
                            Customer
                        </th>
                        <th class="whitespace-nowrap border-b border-[#E9ECEF] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#77818C]">
                            Service
                        </th>
                        <th class="whitespace-nowrap border-b border-[#E9ECEF] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#77818C]">
                            Pickup Date
                        </th>
                        <th class="whitespace-nowrap border-b border-[#E9ECEF] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#77818C]">
                            Pickup Time
                        </th>
                        <th class="whitespace-nowrap border-b border-[#E9ECEF] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#77818C]">
                            Status
                        </th>
                        <th class="whitespace-nowrap border-b border-[#E9ECEF] px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-[#77818C]">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($deliveries as $delivery)

                        @php
                            $status = $delivery->delivery_status;

                            $statusClass = match($status) {
                                'pending' => 'bg-[#FFF3D6] text-[#936300]',
                                'out_for_delivery' => 'bg-[#E5EFFD] text-[#2869CA]',
                                'delivered' => 'bg-[#E1F4EB] text-[#008C64]',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp

                        <tr class="transition-colors hover:bg-[#FAFCFB]">

                            {{-- Booking ID --}}
                            <td class="border-b border-[#F0F2F3] px-5 py-[17px]">
                                <span class="font-bold text-[#252A34]">
                                    #{{ $delivery->id }}
                                </span>
                            </td>

                            {{-- Customer --}}
                            <td class="border-b border-[#F0F2F3] px-5 py-[17px]">
                                <div class="font-semibold text-[#303641]">
                                    {{ $delivery->customer_name ?? $delivery->user?->name ?? 'N/A' }}
                                </div>

                                <small class="mt-1 block text-[11px] text-[#929AA4]">
                                    {{ $delivery->user?->email ?? '' }}
                                </small>
                            </td>

                            {{-- Service --}}
                            <td class="border-b border-[#F0F2F3] px-5 py-[17px] text-[13px] text-[#515B67]">
                                {{ $delivery->service?->name ?? 'N/A' }}
                            </td>

                            {{-- Pickup Date --}}
                            <td class="whitespace-nowrap border-b border-[#F0F2F3] px-5 py-[17px] text-[13px] text-[#515B67]">
                                {{ $delivery->pickup_date ?? 'N/A' }}
                            </td>

                            {{-- Pickup Time --}}
                            <td class="whitespace-nowrap border-b border-[#F0F2F3] px-5 py-[17px] text-[13px] text-[#515B67]">
                                {{ $delivery->pickup_time ?? 'N/A' }}
                            </td>

                            {{-- Status --}}
                            <td class="border-b border-[#F0F2F3] px-5 py-[17px]">
                                <span class="inline-flex whitespace-nowrap rounded px-3 py-1.5 text-[11px] font-bold {{ $statusClass }}">
                                    {{ ucwords(str_replace('_', ' ', $status ?? 'Unknown')) }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="border-b border-[#F0F2F3] px-5 py-[17px]">
                                <a href="{{ route('delivery.deliveries.show', $delivery->id) }}"
                                   class="inline-flex items-center whitespace-nowrap rounded-md border border-[#00A878] bg-white px-3 py-2 text-xs font-semibold text-[#008C64] no-underline transition-colors hover:bg-[#00A878] hover:text-white">
                                    View Details
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-14 text-center">
                                <div class="mb-3 text-3xl text-[#B7C5BE]">
                                    <i class="fas fa-truck"></i>
                                </div>

                                <strong class="mb-1 block text-sm font-bold text-[#515B67]">
                                    No deliveries found
                                </strong>

                                <span class="text-xs text-[#929AA4]">
                                    You currently have no active assigned deliveries.
                                </span>
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($deliveries->hasPages())
            <div class="border-t border-[#EDF0F2] px-6 py-4">
                {{ $deliveries->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
