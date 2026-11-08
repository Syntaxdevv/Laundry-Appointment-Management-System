@extends('layouts.delivery')

@section('title', 'Delivery History')
@section('page-heading', '')

@section('content')
<div class="min-h-screen bg-[#f4f7f6] px-4 py-6 text-[#252a34] sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="mb-2 flex items-center gap-2">
    
            </div>

            <h2 class="text-2xl font-bold tracking-tight text-[#252a34]">
                Delivery History
            </h2>

            <p class="mt-1 text-sm text-[#89928d]">
                View all successfully completed deliveries.
            </p>
        </div>

        <div class="flex items-center gap-3 rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-emerald-600">
                <i class="fas fa-box-check"></i>
            </span>

            <div>
                <p class="text-xl font-bold leading-none text-emerald-700">
                    {{ $deliveries->count() }}
                </p>
                <p class="mt-1 text-xs font-semibold text-emerald-700">
                    Completed Deliveries
                </p>
            </div>
        </div>
    </div>

    {{-- History Table --}}
    <div class="overflow-hidden rounded-xl border border-[#e8ecea] bg-white shadow-sm">

        @if($deliveries->count())
            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] border-collapse text-left">
                    <thead>
                        <tr class="border-b border-[#edf0ee] bg-[#fafcfb]">
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-[#89928d]">
                                Order ID
                            </th>

                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-[#89928d]">
                                Customer
                            </th>

                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-[#89928d]">
                                Service
                            </th>

                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-[#89928d]">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#edf0ee]">
                        @foreach($deliveries as $delivery)
                            <tr class="transition-colors hover:bg-[#fafcfb]">

                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="font-bold text-[#252a34]">
                                        #{{ $delivery->id }}
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-sm font-bold text-emerald-700">
                                            {{ strtoupper(substr($delivery->user->name ?? 'N', 0, 1)) }}
                                        </span>

                                        <span class="text-sm font-semibold text-[#252a34]">
                                            {{ $delivery->user->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="text-sm text-[#737d78]">
                                        {{ $delivery->service->name ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="inline-flex items-center gap-2 rounded-md bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">
                                        <i class="fas fa-check-circle"></i>
                                        Delivered
                                    </span>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <i class="fas fa-box-open text-2xl"></i>
                </div>

                <h3 class="text-lg font-bold text-[#252a34]">
                    No Delivery History
                </h3>

                <p class="mt-2 max-w-sm text-sm leading-6 text-[#89928d]">
                    Completed deliveries will appear here once you finish your assigned deliveries.
                </p>
            </div>
        @endif

    </div>

</div>
@endsection
