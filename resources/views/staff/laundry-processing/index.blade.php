@extends('layouts.staff')

@section('title', 'Laundry Processing')

@section('content')
<div class="processing-page box-border px-1 pt-3 pb-7 font-['Inter'] text-[#304536]">
    <div class="mb-6">
        <h2 class="m-0 text-[27px] font-bold tracking-[-0.6px] text-[#263e32]">Laundry Processing</h2>
        <p class="mt-[7px] mb-0 text-xs text-[#829087]">Monitor laundry orders and update their processing status.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md border border-[#d9e9da] bg-[#edf6ee] px-[15px] py-3 text-xs text-[#356d46]">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-md border border-[#f0d8d5] bg-[#fff1ef] px-[15px] py-3 text-xs text-[#a34440]">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-md border border-[#f0d8d5] bg-[#fff1ef] px-[15px] py-3 text-xs text-[#a34440]">{{ $errors->first() }}</div>
    @endif

    <div class="mb-[22px] grid grid-cols-2 gap-[14px] min-[901px]:grid-cols-4 max-[500px]:gap-[9px]">
        <div class="rounded-lg border border-[#e5ebe4] bg-white p-[19px] max-[500px]:p-[14px]">
            <span class="block text-[11px] text-[#819087]">To Process</span>
            <strong class="mt-3 mb-[5px] block text-[28px] font-bold text-[#304536]">{{ $counts['pending'] }}</strong>
            <small class="block text-[11px] text-[#819087]">Waiting to be started</small>
        </div>
        <div class="rounded-lg border border-[#e5ebe4] bg-white p-[19px] max-[500px]:p-[14px]">
            <span class="block text-[11px] text-[#819087]">Washing</span>
            <strong class="mt-3 mb-[5px] block text-[28px] font-bold text-[#304536]">{{ $counts['washing'] }}</strong>
            <small class="block text-[11px] text-[#819087]">Currently being washed</small>
        </div>
        <div class="rounded-lg border border-[#e5ebe4] bg-white p-[19px] max-[500px]:p-[14px]">
            <span class="block text-[11px] text-[#819087]">Drying</span>
            <strong class="mt-3 mb-[5px] block text-[28px] font-bold text-[#304536]">{{ $counts['drying'] }}</strong>
            <small class="block text-[11px] text-[#819087]">Currently drying</small>
        </div>
        <div class="rounded-lg border border-[#e5ebe4] bg-white p-[19px] max-[500px]:p-[14px]">
            <span class="block text-[11px] text-[#819087]">Ready</span>
            <strong class="mt-3 mb-[5px] block text-[28px] font-bold text-[#304536]">{{ $counts['ready'] }}</strong>
            <small class="block text-[11px] text-[#819087]">Ready for customer</small>
        </div>
    </div>

    <section class="overflow-hidden rounded-lg border border-[#e5ebe4] bg-white">
        <div class="flex items-center justify-between gap-[15px] border-b border-[#e5ebe4] px-[23px] py-[21px] max-[500px]:flex-col max-[500px]:items-start">
            <div>
                <h3 class="m-0 text-base font-bold">Processing Orders</h3>
                <p class="mt-[6px] mb-0 text-[11px] text-[#829087]">Update each order as it moves through the laundry process.</p>
            </div>
            <span class="whitespace-nowrap text-[11px] text-[#65806a]">{{ $orders->total() }} Orders</span>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left">
                <thead>
                    <tr>
                        <th class="border-b border-[#e5ebe4] bg-[#f5f7f4] px-[17px] py-[13px] text-[10px] font-bold tracking-[0.35px] text-[#7c897e]">ORDER ID</th>
                        <th class="border-b border-[#e5ebe4] bg-[#f5f7f4] px-[17px] py-[13px] text-[10px] font-bold tracking-[0.35px] text-[#7c897e]">CUSTOMER</th>
                        <th class="border-b border-[#e5ebe4] bg-[#f5f7f4] px-[17px] py-[13px] text-[10px] font-bold tracking-[0.35px] text-[#7c897e]">SERVICE</th>
                        <th class="border-b border-[#e5ebe4] bg-[#f5f7f4] px-[17px] py-[13px] text-[10px] font-bold tracking-[0.35px] text-[#7c897e]">WEIGHT</th>
                        <th class="border-b border-[#e5ebe4] bg-[#f5f7f4] px-[17px] py-[13px] text-[10px] font-bold tracking-[0.35px] text-[#7c897e]">STATUS</th>
                        <th class="border-b border-[#e5ebe4] bg-[#f5f7f4] px-[17px] py-[13px] text-[10px] font-bold tracking-[0.35px] text-[#7c897e]">NEXT STEP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $status = strtolower($order->status ?? 'pending');
                            $nextSteps = [
                                'pending' => 'processing',
                                'processing' => 'washing',
                                'washing' => 'drying',
                                'drying' => 'ready',
                                'ready' => 'completed',
                            ];
                            $nextStatus = $nextSteps[$status] ?? null;
                            $customerName = $order->customer_name ?: ($order->user->name ?? 'Unknown Customer');
                        @endphp
                        <tr class="hover:bg-[#fafbf9]">
                            <td class="border-b border-[#edf0eb] px-[17px] py-4 align-middle text-[11px] text-[#596b5e]">
                                <strong class="text-xs text-[#416c4c]">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td class="border-b border-[#edf0eb] px-[17px] py-4 align-middle text-[11px] text-[#596b5e]">
                                <div class="font-semibold text-[#35483a]">{{ $customerName }}</div>
                                <small class="mt-1 block text-[10px] text-[#929c93]">{{ $order->user->email ?? 'No email available' }}</small>
                            </td>
                            <td class="border-b border-[#edf0eb] px-[17px] py-4 align-middle text-[11px] text-[#596b5e]">{{ $order->service->name ?? 'Laundry Service' }}</td>
                            <td class="border-b border-[#edf0eb] px-[17px] py-4 align-middle text-[11px] text-[#596b5e]">{{ $order->weight_kg }} kg</td>
                            <td class="border-b border-[#edf0eb] px-[17px] py-4 align-middle text-[11px] text-[#596b5e]">
                                <span @class([
                                    'inline-block rounded-[5px] px-[9px] py-[6px] text-[10px] font-semibold',
                                    'bg-[#fbf2df] text-[#94702c]' => $status === 'pending',
                                    'bg-[#edf3f9] text-[#52769a]' => in_array($status, ['processing', 'washing']),
                                    'bg-[#f2eef8] text-[#79639b]' => $status === 'drying',
                                    'bg-[#eaf3e9] text-[#4e7855]' => $status === 'ready',
                                ])>{{ ucfirst($status) }}</span>
                            </td>
                            <td class="border-b border-[#edf0eb] px-[17px] py-4 align-middle text-[11px] text-[#596b5e]">
                                @if($nextStatus)
                                    <form action="{{ route('staff.processing.status', $order->id) }}" method="POST" onsubmit="return confirm('Update this order to {{ ucfirst($nextStatus) }}?')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                                        <button type="submit" class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-[5px] border border-[#527b60] bg-[#527b60] px-3 py-[9px] text-[10px] font-semibold text-white transition-colors duration-150 hover:border-[#365842] hover:bg-[#365842]">
                                            {{ $status === 'pending' ? 'Start Processing' : 'Update Status' }}
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] font-semibold text-[#4e7855]">Completed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="px-5 py-[55px] text-center">
                                    <i class="fa-solid fa-shirt text-[28px] text-[#78947c]"></i>
                                    <h4 class="mt-[13px] mb-[6px] text-sm text-[#3d5141]">No orders to process</h4>
                                    <p class="m-0 text-[11px] text-[#89948b]">New customer bookings will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="border-t border-[#e5ebe4] px-[22px] py-[15px]">
                {{ $orders->links() }}
            </div>
        @endif
    </section>
</div>
@endsection