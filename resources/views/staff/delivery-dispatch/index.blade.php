
@extends('layouts.staff')
@section('title', 'Delivery Assignment')
@section('content')

<div class="w-full min-w-0 space-y-6 text-[#29312D]">

   
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-[27px] font-bold text-[#252A34]">Delivery Assignment</h1>
            <p class="mt-1.5 text-sm text-[#7A837E]">
                Manage and assign completed laundry orders to delivery personnel.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 rounded-md border border-[#E1E6E0] bg-white px-4 py-2.5 text-xs font-semibold text-[#536057]">
            <i class="fas fa-truck text-[#009B70]"></i>
            Delivery Management
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-md border border-[#D1EBDD] bg-[#EAF7EF] px-4 py-3.5 text-[13px] text-[#176B4A]">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 flex items-start gap-3 rounded-md border border-[#F2D4D1] bg-[#FFF1F0] px-4 py-3.5 text-[13px] text-[#9B3030]">
            <i class="fas fa-exclamation-circle mt-0.5"></i>

            <div>
                <strong>Please check the following:</strong>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

  
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

        {{-- COMPLETED ORDERS --}}
        <div class="flex min-w-0 items-center justify-between gap-4 rounded-lg border border-[#E1E6E0] bg-white p-6">
            <div>
                <p class="mb-2 text-[13px] text-[#7A837E]">Completed Pickup Orders</p>
                <h2 class="text-[29px] font-bold text-[#29312D]">{{ $stats['total'] }}</h2>
            </div>

            <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-xl bg-[#E4F5EE] text-[21px] text-[#009B70]">
                <i class="fas fa-boxes-stacked"></i>
            </div>
        </div>

    
        <div class="flex min-w-0 items-center justify-between gap-4 rounded-lg border border-[#E1E6E0] bg-white p-6">
            <div>
                <p class="mb-2 text-[13px] text-[#7A837E]">Assigned Orders</p>
                <h2 class="text-[29px] font-bold text-[#29312D]">{{ $stats['assigned'] }}</h2>
            </div>

            <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-xl bg-[#E9F0FF] text-[21px] text-[#3478E5]">
                <i class="fas fa-user-check"></i>
            </div>
        </div>

     
        <div class="flex min-w-0 items-center justify-between gap-4 rounded-lg border border-[#E1E6E0] bg-white p-6 sm:col-span-2 xl:col-span-1">
            <div>
                <p class="mb-2 text-[13px] text-[#7A837E]">Unassigned Orders</p>
                <h2 class="text-[29px] font-bold text-[#29312D]">{{ $stats['unassigned'] }}</h2>
            </div>

            <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-xl bg-[#FCE9EA] text-[21px] text-[#D84B55]">
                <i class="fas fa-users"></i>
            </div>
        </div>

    </div>


    <div class="w-full min-w-0 overflow-hidden rounded-lg border border-[#E1E6E0] bg-white">

   
        <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-6 sm:px-[26px]">
            <div>
                <h2 class="text-lg font-bold text-[#29312D]">
                    Completed Pickup &amp; Delivery Orders
                </h2>
                <p class="mt-1.5 text-[13px] text-[#879089]">
                    Assign completed orders to available delivery personnel.
                </p>
            </div>

            <span class="whitespace-nowrap rounded-md border border-[#E1E6E0] bg-[#F8FAF8] px-3 py-2 text-xs text-[#69736C]">
                {{ $bookings->total() }} records
            </span>
        </div>
        <div class="border-y border-[#EDF0EC] bg-[#FCFDFC] px-5 py-5 sm:px-[26px]">
            <form
                method="GET"
                action="{{ route('staff.delivery-dispatch.index') }}"
                class="grid grid-cols-1 items-center gap-3 sm:grid-cols-[minmax(0,1fr)_200px_auto_auto]"
            >

             
                <div class="flex h-11 min-w-0 items-center gap-3 rounded-md border border-[#DDE3DC] bg-white px-3.5 text-[#879089]">
                    <i class="fas fa-search text-sm"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search booking ID or customer..."
                        class="h-full w-full min-w-0 border-0 bg-transparent text-[13px] text-[#29312D] outline-none placeholder:text-[#A0A8A1] focus:ring-0"
                    >
                </div>

                <select
                    name="filter"
                    class="h-11 w-full min-w-0 rounded-md border border-[#DDE3DC] bg-white px-3 text-[13px] text-[#39433D] outline-none focus:border-[#009B70] focus:ring-1 focus:ring-[#009B70]"
                >
                    <option value="all" @selected(request('filter', 'all') === 'all')>
                        All Orders
                    </option>
                    <option value="unassigned" @selected(request('filter') === 'unassigned')>
                        Unassigned
                    </option>
                    <option value="assigned" @selected(request('filter') === 'assigned')>
                        Assigned
                    </option>
                </select>

              
                <button
                    type="submit"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-[#009B70] bg-[#009B70] px-5 text-xs font-semibold text-white transition hover:bg-[#007F5B]"
                >
                    <i class="fas fa-filter"></i>
                    Filter
                </button>

                <a
                    href="{{ route('staff.delivery-dispatch.index') }}"
                    title="Reset filters"
                    class="inline-flex h-11 w-full items-center justify-center rounded-md border border-[#DDE3DC] bg-white text-[#59645C] transition hover:bg-gray-50 sm:w-11"
                >
                    <i class="fas fa-rotate-left"></i>
                </a>

            </form>
        </div>

     
        <div class="relative w-full max-w-full overflow-x-auto">
            <table class="w-full min-w-[1250px] border-separate border-spacing-0">

                <thead>
                    <tr>
                        <th class="whitespace-nowrap border-b border-[#E5EAE5] bg-[#F6F8F6] px-[18px] py-4 pl-[26px] text-left text-[11px] font-bold uppercase tracking-[0.35px] text-[#68736B]">
                            Booking ID
                        </th>

                        <th class="min-w-[200px] whitespace-nowrap border-b border-[#E5EAE5] bg-[#F6F8F6] px-[18px] py-4 text-left text-[11px] font-bold uppercase tracking-[0.35px] text-[#68736B]">
                            Customer
                        </th>

                        <th class="whitespace-nowrap border-b border-[#E5EAE5] bg-[#F6F8F6] px-[18px] py-4 text-left text-[11px] font-bold uppercase tracking-[0.35px] text-[#68736B]">
                            Service
                        </th>

                        <th class="whitespace-nowrap border-b border-[#E5EAE5] bg-[#F6F8F6] px-[18px] py-4 text-left text-[11px] font-bold uppercase tracking-[0.35px] text-[#68736B]">
                            Pickup Date
                        </th>

                        <th class="min-w-[180px] whitespace-nowrap border-b border-[#E5EAE5] bg-[#F6F8F6] px-[18px] py-4 text-left text-[11px] font-bold uppercase tracking-[0.35px] text-[#68736B]">
                            Delivery Address
                        </th>

                        <th class="min-w-[190px] whitespace-nowrap border-b border-[#E5EAE5] bg-[#F6F8F6] px-[18px] py-4 text-left text-[11px] font-bold uppercase tracking-[0.35px] text-[#68736B]">
                            Assigned To
                        </th>

                        <th class="whitespace-nowrap border-b border-[#E5EAE5] bg-[#F6F8F6] px-[18px] py-4 text-left text-[11px] font-bold uppercase tracking-[0.35px] text-[#68736B]">
                            Status
                        </th>

                        <th class="sticky right-0 z-20 w-[240px] min-w-[240px] whitespace-nowrap border-b border-[#E5EAE5] bg-[#F6F8F6] px-[18px] py-4 text-left text-[11px] font-bold uppercase tracking-[0.35px] text-[#68736B] shadow-[-5px_0_8px_rgba(0,0,0,0.05)]">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="group hover:bg-[#FAFCFA]">

                            {{-- BOOKING ID --}}
                            <td class="border-b border-[#EDF0EC] px-[18px] py-[17px] pl-[26px] text-[13px]">
                                <span class="font-bold text-[#008C64]">
                                    #{{ $booking->id }}
                                </span>
                            </td>

                            {{-- CUSTOMER --}}
                            <td class="min-w-[200px] border-b border-[#EDF0EC] px-[18px] py-[17px] text-[13px]">
                                <div class="font-semibold text-[#29312D]">
                                    {{ $booking->customer_name
                                        ?? $booking->user?->name
                                        ?? 'Unknown Customer' }}
                                </div>

                                <small class="mt-1 block text-[11px] text-[#879089]">
                                    {{ $booking->phone
                                        ?? $booking->user?->email
                                        ?? 'No contact' }}
                                </small>
                            </td>

                            {{-- SERVICE --}}
                            <td class="border-b border-[#EDF0EC] px-[18px] py-[17px] text-[13px] text-[#4C5750]">
                                {{ $booking->service?->name ?? 'Laundry Service' }}
                            </td>

                            {{-- PICKUP DATE --}}
                            <td class="whitespace-nowrap border-b border-[#EDF0EC] px-[18px] py-[17px] text-[13px] text-[#4C5750]">
                                {{ $booking->pickup_date
                                    ? \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y')
                                    : 'Not specified' }}
                            </td>

                            {{-- DELIVERY ADDRESS --}}
                            <td class="min-w-[180px] border-b border-[#EDF0EC] px-[18px] py-[17px] text-[13px]">
                                <div class="max-w-[210px] break-words leading-relaxed text-[#69736C]">
                                    {{ $booking->delivery_address ?: 'No address provided' }}
                                </div>
                            </td>

                            {{-- ASSIGNED PERSONNEL --}}
                            <td class="min-w-[190px] border-b border-[#EDF0EC] px-[18px] py-[17px] text-[13px]">
                                @if($booking->assignedDelivery)
                                    <div class="flex items-center gap-2.5 whitespace-nowrap">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#E5F5EE] text-xs text-[#009B70]">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <span class="font-medium text-[#4C5750]">
                                            {{ $booking->assignedDelivery->name }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-[#879089]">
                                        Not assigned
                                    </span>
                                @endif
                            </td>

                            {{-- DELIVERY STATUS --}}
                            <td class="border-b border-[#EDF0EC] px-[18px] py-[17px]">
                                @if($booking->delivery_status === 'delivered')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-[#E5F5EE] px-3 py-2 text-[11px] font-semibold text-[#008C64]">
                                        <i class="fas fa-circle text-[6px]"></i>
                                        Delivered
                                    </span>

                                @elseif($booking->delivery_status === 'out_for_delivery')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-[#E8F3FC] px-3 py-2 text-[11px] font-semibold text-[#1675B9]">
                                        <i class="fas fa-circle text-[6px]"></i>
                                        Out for Delivery
                                    </span>

                                @elseif($booking->assigned_delivery_id)
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-[#EAF0FF] px-3 py-2 text-[11px] font-semibold text-[#3569C8]">
                                        <i class="fas fa-circle text-[6px]"></i>
                                        Pending
                                    </span>

                                @else
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-[#FFF3D9] px-3 py-2 text-[11px] font-semibold text-[#A66A0B]">
                                        <i class="fas fa-circle text-[6px]"></i>
                                        Unassigned
                                    </span>
                                @endif
                            </td>

                            {{-- ACTION --}}
                            <td class="sticky right-0 z-10 min-w-[240px] border-b border-[#EDF0EC] bg-white px-[18px] py-[17px] shadow-[-5px_0_8px_rgba(0,0,0,0.05)] group-hover:bg-[#FAFCFA]">
                                @if($booking->delivery_status !== 'delivered')
                                    <form
                                        action="{{ route('staff.delivery-dispatch.assign', $booking->id) }}"
                                        method="POST"
                                        class="flex min-w-[220px] items-center gap-2"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <select
                                            name="assigned_delivery_id"
                                            required
                                            class="h-9 w-[140px] min-w-0 rounded-md border border-[#DDE3DC] bg-white px-2 text-[11px] text-[#39433D] outline-none focus:border-[#009B70] focus:ring-1 focus:ring-[#009B70]"
                                        >
                                            <option value="">Select personnel</option>

                                            @foreach($deliveryUsers as $deliveryUser)
                                                <option
                                                    value="{{ $deliveryUser->id }}"
                                                    @selected(
                                                        (string) $booking->assigned_delivery_id
                                                        === (string) $deliveryUser->id
                                                    )
                                                >
                                                    {{ $deliveryUser->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button
                                            type="submit"
                                            class="inline-flex h-9 shrink-0 items-center justify-center gap-2 rounded-md border border-[#009B70] bg-[#009B70] px-3 text-[11px] font-semibold text-white transition hover:bg-[#007F5B] disabled:cursor-not-allowed disabled:opacity-50"
                                            {{ $deliveryUsers->isEmpty() ? 'disabled' : '' }}
                                        >
                                            <i class="fas fa-user-check"></i>
                                            {{ $booking->assigned_delivery_id ? 'Reassign' : 'Assign' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-2 whitespace-nowrap text-[11px] font-medium text-[#008C64]">
                                        <i class="fas fa-check-circle"></i>
                                        Already delivered
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="border-b border-[#EDF0EC] px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-[38px] text-[#CBD5E1]"></i>

                                    <h3 class="mt-4 text-[15px] font-semibold text-[#29312D]">
                                        No completed pickup orders found
                                    </h3>

                                    <p class="mt-1 text-xs text-[#879089]">
                                        Completed bookings will appear here.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if($bookings->hasPages())
            <div class="border-t border-[#EDF0EC] bg-white px-5 py-5 sm:px-[26px]">
                {{ $bookings->withQueryString()->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
