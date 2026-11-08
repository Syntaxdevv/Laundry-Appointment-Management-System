
@extends('layouts.delivery')

@section('title', 'Delivery Tracking')
@section('page-heading', '')

@section('content')

<div class="w-full min-w-0 font-['DM_Sans',sans-serif] text-[#252A34]">

    {{-- PAGE HEADER --}}
    <div class="mb-7 flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="mb-2 flex items-center gap-2 text-xs font-bold uppercase tracking-[1.5px] text-[#008C64]">

            </div>

            <h2 class="mb-1 text-[26px] font-extrabold tracking-tight text-[#252A34]">
                Delivery Tracking
            </h2>

            <p class="m-0 text-sm text-[#87918B]">
                Monitor the progress and status of your assigned deliveries.
            </p>
        </div>

        <div class="flex items-center gap-3 rounded-lg border border-[#DDECE5] bg-[#F0F8F4] px-4 py-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white text-[#008C64] shadow-sm">
                <i class="fa-solid fa-truck-fast text-lg"></i>
            </div>

            <div>
                <div class="text-xl font-extrabold leading-tight text-[#008C64]">
                    {{ $deliveries->count() }}
                </div>
                <div class="text-[11px] font-semibold text-[#75857C]">
                    Active Deliveries
                </div>
            </div>
        </div>
    </div>

    {{-- DELIVERY LIST --}}
    @forelse ($deliveries as $delivery)

        @php
            $statuses = ['assigned', 'picked_up', 'on_the_way', 'delivered'];
            $currentStatus = array_search($delivery->delivery_status, $statuses);

            $statusLabel = ucfirst(str_replace('_', ' ', $delivery->delivery_status));

            $statusColor = match($delivery->delivery_status) {
                'assigned' => 'bg-[#FFF4DF] text-[#A66A00] border-[#F5E2B9]',
                'picked_up' => 'bg-[#EAF2FF] text-[#2869CA] border-[#D7E5FC]',
                'on_the_way' => 'bg-[#EAF2FF] text-[#2869CA] border-[#D7E5FC]',
                'delivered' => 'bg-[#E8F5EF] text-[#008C64] border-[#C8EBDD]',
                default => 'bg-gray-100 text-gray-600 border-gray-200',
            };

            $progressPercentage = $currentStatus !== false
                ? ($currentStatus / (count($statuses) - 1)) * 100
                : 0;
        @endphp

        <div class="mb-5 overflow-hidden rounded-xl border border-[#E5EAE7] bg-white shadow-[0_2px_10px_rgba(37,42,52,0.035)] transition hover:border-[#C9DED3]">

            {{-- CARD TOP --}}
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#EDF1EE] px-5 py-5 sm:px-6">

                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#EAF5EF] text-[#008C64]">
                        <i class="fa-solid fa-box text-lg"></i>
                    </div>

                    <div>
                        <div class="mb-1 text-[10px] font-bold uppercase tracking-[1.2px] text-[#929B95]">
                            Booking Reference
                        </div>

                        <h5 class="m-0 text-lg font-extrabold text-[#252A34]">
                            #{{ $delivery->id }}
                        </h5>
                    </div>
                </div>

                <span class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-[11px] font-bold {{ $statusColor }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                    {{ $statusLabel }}
                </span>

            </div>

            {{-- CUSTOMER DETAILS --}}
            <div class="grid grid-cols-1 gap-4 px-5 py-5 sm:grid-cols-2 sm:px-6">

                <div class="flex min-w-0 items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F3F6F4] text-[#75857C]">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div class="min-w-0">
                        <span class="mb-1 block text-[11px] font-semibold text-[#929B95]">
                            Customer Name
                        </span>

                        <p class="m-0 break-words text-sm font-bold text-[#303A34]">
                            {{ $delivery->user->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="flex min-w-0 items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F3F6F4] text-[#75857C]">
                        <i class="fa-solid fa-soap"></i>
                    </div>

                    <div class="min-w-0">
                        <span class="mb-1 block text-[11px] font-semibold text-[#929B95]">
                            Laundry Service
                        </span>

                        <p class="m-0 break-words text-sm font-bold text-[#303A34]">
                            {{ $delivery->service->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>

            </div>

            {{-- PROGRESS SECTION --}}
            <div class="border-t border-[#EDF1EE] bg-[#FAFCFB] px-5 py-6 sm:px-6">

                <div class="mb-6 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h4 class="m-0 text-sm font-extrabold text-[#303A34]">
                            Delivery Progress
                        </h4>
                        <p class="mt-1 text-xs text-[#929B95]">
                            Track each stage of the delivery process.
                        </p>
                    </div>

                    <span class="text-xs font-bold text-[#008C64]">
                        @if($delivery->delivery_status === 'delivered')
                            Completed
                        @elseif($currentStatus !== false)
                            Step {{ $currentStatus + 1 }} of {{ count($statuses) }}
                        @else
                            Waiting for update
                        @endif
                    </span>
                </div>

                {{-- PROGRESS TRACKER --}}
                <div class="relative">

                    {{-- BACKGROUND LINE --}}
                    <div class="absolute left-[12.5%] right-[12.5%] top-[17px] h-[3px] rounded-full bg-[#E3EAE5]"></div>

                    {{-- COMPLETED LINE --}}
                    <div
                        class="absolute left-[12.5%] top-[17px] h-[3px] rounded-full bg-[#00A878] transition-all duration-300"
                        style="width: calc(75% * {{ $progressPercentage }} / 100);"
                    ></div>

                    <div class="relative flex items-start justify-between">

                        @foreach ($statuses as $index => $status)

                            @php
                                $isCompleted = $currentStatus !== false && $index <= $currentStatus;
                                $isCurrent = $currentStatus !== false && $index === $currentStatus;
                            @endphp

                            <div class="flex w-1/4 min-w-0 flex-col items-center text-center">

                                {{-- STEP ICON --}}
                                <div class="relative z-10 mb-3 flex h-[36px] w-[36px] items-center justify-center rounded-full border-[3px] text-xs font-bold
                                    {{ $isCompleted
                                        ? 'border-[#00A878] bg-[#00A878] text-white'
                                        : 'border-[#E3EAE5] bg-white text-[#A3ACA6]' }}
                                    {{ $isCurrent ? 'ring-4 ring-[#DDF3E9]' : '' }}">

                                    @if($isCompleted)
                                        <i class="fa-solid fa-check"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif

                                </div>

                                {{-- STEP LABEL --}}
                                <span class="max-w-full break-words text-[9px] font-bold leading-tight sm:text-[11px]
                                    {{ $isCompleted ? 'text-[#008C64]' : 'text-[#A3ACA6]' }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>

                                @if($isCurrent)
                                    <span class="mt-1 text-[9px] font-semibold text-[#008C64]">
                                        Current
                                    </span>
                                @endif

                            </div>

                        @endforeach

                    </div>
                </div>

            </div>

        </div>

    @empty

        {{-- EMPTY STATE --}}
        <div class="rounded-xl border border-[#E5EAE7] bg-white px-5 py-16 text-center shadow-[0_2px_10px_rgba(37,42,52,0.035)]">

            <div class="mx-auto mb-5 flex h-[68px] w-[68px] items-center justify-center rounded-2xl bg-[#EAF5EF] text-[27px] text-[#00A878]">
                <i class="fa-solid fa-truck-fast"></i>
            </div>

            <h5 class="mb-2 text-lg font-extrabold text-[#303A34]">
                No Active Deliveries
            </h5>

            <p class="mx-auto max-w-sm text-[13px] leading-relaxed text-[#929B95]">
                There are currently no assigned deliveries to track. New deliveries assigned to your account will appear here.
            </p>

        </div>

    @endforelse

    {{-- FOOTER NOTE --}}
    @if($deliveries->isNotEmpty())
        <div class="mt-5 flex items-start gap-2 rounded-lg border border-[#E5EAE7] bg-[#FAFCFB] px-4 py-3 text-xs leading-relaxed text-[#87918B]">
            <i class="fa-solid fa-circle-info mt-0.5 text-[#008C64]"></i>
            <span>
                Delivery progress is updated when the delivery status changes.
            </span>
        </div>
    @endif

</div>

@endsection
