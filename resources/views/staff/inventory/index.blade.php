@extends('layouts.staff')

@section('title', 'Inventory')
@section('page-heading', '')

@section('content')
<div class="w-full max-w-full min-w-0 font-[inherit] text-[#29312D]">

    <div class="mb-6 flex flex-wrap items-end justify-between gap-5 max-[700px]:flex-col max-[700px]:items-start">
        <div>
            <span class="mb-[9px] inline-block text-[10px] font-bold tracking-[1.2px] text-[#008C64]">
                INVENTORY MANAGEMENT
            </span>
            <h2 class="m-0 text-[26px] font-bold tracking-[-0.5px] text-[#252A34]">
                Laundry Supplies
            </h2>
            <p class="mt-[7px] mb-0 text-[13px] leading-relaxed text-[#7A837E]">
                Manage supplies, monitor stock levels, and track inventory movements.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-[9px] max-[700px]:w-full max-[700px]:flex-col max-[700px]:items-stretch">
            <a href="{{ route('staff.inventory.history') }}"
               class="inline-flex min-h-10 items-center justify-center gap-2 whitespace-nowrap rounded-[5px] border border-[#DDE3DC] bg-white px-[15px] text-xs font-semibold text-[#46514A] no-underline transition-colors hover:border-[#BFCFC2] hover:bg-[#F5F8F5] hover:text-[#008C64] max-[700px]:flex-1">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Stock History
            </a>
            <a href="{{ route('staff.inventory.create') }}"
               class="inline-flex min-h-10 items-center justify-center gap-2 whitespace-nowrap rounded-[5px] border border-[#008C64] bg-[#008C64] px-[15px] text-xs font-semibold text-white no-underline transition-colors hover:border-[#007653] hover:bg-[#007653] max-[700px]:flex-1">
                <i class="fa-solid fa-plus"></i>
                Add New Item
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-[18px] flex items-start gap-[10px] rounded-[5px] border border-[#D5EBDD] bg-[#EAF5EF] px-[15px] py-[13px] text-xs leading-relaxed text-[#087A58]">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-[18px] flex items-start gap-[10px] rounded-[5px] border border-[#F1D8D5] bg-[#FFF3F2] px-[15px] py-[13px] text-xs leading-relaxed text-[#B6423A]">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-[18px] flex items-start gap-[10px] rounded-[5px] border border-[#F1D8D5] bg-[#FFF3F2] px-[15px] py-[13px] text-xs leading-relaxed text-[#B6423A]">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mb-[22px] grid grid-cols-4 gap-[14px] max-[1100px]:grid-cols-2 max-[480px]:gap-[10px]">

        <div class="min-w-0 rounded-[7px] border border-[#E1E6E0] bg-white p-[18px] max-[480px]:p-[13px]">
            <div class="mb-[13px] flex items-center justify-between gap-[10px]">
                <span class="text-xs font-semibold text-[#77817A]">Total Items</span>
                <div class="flex h-[35px] w-[35px] shrink-0 items-center justify-center rounded-md bg-[#EEF1F2] text-sm text-[#53616A]">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="text-[28px] font-bold leading-tight tracking-[-0.5px] text-[#252A34] max-[480px]:text-[23px]">
                {{ $totalItems }}
            </div>
            <div class="mt-[7px] text-[11px] text-[#929A94] max-[480px]:leading-relaxed">
                Registered inventory items
            </div>
        </div>

        <div class="min-w-0 rounded-[7px] border border-[#E1E6E0] bg-white p-[18px] max-[480px]:p-[13px]">
            <div class="mb-[13px] flex items-center justify-between gap-[10px]">
                <span class="text-xs font-semibold text-[#77817A]">Available</span>
                <div class="flex h-[35px] w-[35px] shrink-0 items-center justify-center rounded-md bg-[#E8F5EF] text-sm text-[#008C64]">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>
            <div class="text-[28px] font-bold leading-tight tracking-[-0.5px] text-[#008C64] max-[480px]:text-[23px]">
                {{ $availableItems }}
            </div>
            <div class="mt-[7px] text-[11px] text-[#929A94] max-[480px]:leading-relaxed">
                Stock above minimum level
            </div>
        </div>

        <div class="min-w-0 rounded-[7px] border border-[#E1E6E0] bg-white p-[18px] max-[480px]:p-[13px]">
            <div class="mb-[13px] flex items-center justify-between gap-[10px]">
                <span class="text-xs font-semibold text-[#77817A]">Low Stock</span>
                <div class="flex h-[35px] w-[35px] shrink-0 items-center justify-center rounded-md bg-[#FFF3DF] text-sm text-[#B77A20]">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="text-[28px] font-bold leading-tight tracking-[-0.5px] text-[#B77A20] max-[480px]:text-[23px]">
                {{ $lowStockItems }}
            </div>
            <div class="mt-[7px] text-[11px] text-[#929A94] max-[480px]:leading-relaxed">
                Items needing replenishment
            </div>
        </div>

        <div class="min-w-0 rounded-[7px] border border-[#E1E6E0] bg-white p-[18px] max-[480px]:p-[13px]">
            <div class="mb-[13px] flex items-center justify-between gap-[10px]">
                <span class="text-xs font-semibold text-[#77817A]">Out of Stock</span>
                <div class="flex h-[35px] w-[35px] shrink-0 items-center justify-center rounded-md bg-[#FCEAEA] text-sm text-[#C34545]">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
            <div class="text-[28px] font-bold leading-tight tracking-[-0.5px] text-[#C34545] max-[480px]:text-[23px]">
                {{ $outOfStockItems }}
            </div>
            <div class="mt-[7px] text-[11px] text-[#929A94] max-[480px]:leading-relaxed">
                Items with zero quantity
            </div>
        </div>

    </div>

    <div class="w-full min-w-0 overflow-hidden rounded-[7px] border border-[#E1E6E0] bg-white">

        <div class="flex flex-wrap items-center justify-between gap-[15px] border-b border-[#E8ECE7] px-[22px] py-5 max-[700px]:px-[15px]">
            <div>
                <h3 class="m-0 text-base font-bold text-[#252A34]">Inventory Records</h3>
                <p class="mt-[6px] mb-0 text-xs text-[#89918B]">
                    View and manage your registered laundry supplies.
                </p>
            </div>
            <span class="whitespace-nowrap rounded border border-[#E4E9E3] bg-[#FAFBF9] px-[10px] py-[6px] text-[11px] text-[#657169]">
                {{ $items->total() }} records
            </span>
        </div>

        <form action="{{ route('staff.inventory.index') }}" method="GET"
              class="flex flex-wrap items-center gap-[10px] border-b border-[#E8ECE7] bg-[#FCFDFC] px-[22px] py-4 max-[700px]:items-stretch max-[700px]:px-[15px]">

            <div class="relative min-w-0 flex-[1_1_220px] max-[700px]:basis-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#929A94]"></i>
                <input type="search" name="search" value="{{ request('search') }}"
                       placeholder="Search supplies..."
                       class="h-10 w-full rounded-[5px] border border-[#DDE3DC] bg-white py-0 pl-[34px] pr-3 text-xs text-[#39433D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]">
            </div>

            <select name="category" aria-label="Filter by category"
                    class="h-10 min-w-[140px] flex-[0_1_180px] rounded-[5px] border border-[#DDE3DC] bg-white px-3 text-xs text-[#39433D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08] max-[700px]:min-w-0 max-[700px]:flex-1">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected(request('category') == $category)>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <select name="stock" aria-label="Filter by stock status"
                    class="h-10 min-w-[140px] flex-[0_1_180px] rounded-[5px] border border-[#DDE3DC] bg-white px-3 text-xs text-[#39433D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08] max-[700px]:min-w-0 max-[700px]:flex-1">
                <option value="">All Stock Status</option>
                <option value="available" @selected(request('stock') == 'available')>Available</option>
                <option value="low" @selected(request('stock') == 'low')>Low Stock</option>
                <option value="out" @selected(request('stock') == 'out')>Out of Stock</option>
            </select>

            <button type="submit"
                    class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] whitespace-nowrap rounded-[5px] border border-[#252A34] bg-[#252A34] px-[14px] text-xs font-semibold text-white hover:bg-[#39414E] max-[700px]:flex-1">
                <i class="fa-solid fa-filter"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'category', 'stock']))
                <a href="{{ route('staff.inventory.index') }}"
                   class="inline-flex h-10 items-center justify-center gap-[7px] whitespace-nowrap rounded-[5px] border border-[#DDE3DC] bg-white px-[14px] text-xs font-semibold text-[#59645D] no-underline hover:bg-[#F5F8F5] max-[700px]:flex-1">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </form>

        @if($items->count() > 0)
            <div class="w-full max-w-full overflow-x-auto [-webkit-overflow-scrolling:touch]">
                <table class="w-full min-w-[1100px] border-collapse">

                    <thead class="bg-[#F7F9F6]">
                        <tr>
                            <th class="whitespace-nowrap border-b border-[#E5EAE4] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B]">Product</th>
                            <th class="whitespace-nowrap border-b border-[#E5EAE4] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B]">Category</th>
                            <th class="whitespace-nowrap border-b border-[#E5EAE4] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B]">Current Stock</th>
                            <th class="whitespace-nowrap border-b border-[#E5EAE4] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B]">Minimum</th>
                            <th class="whitespace-nowrap border-b border-[#E5EAE4] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B]">Status</th>
                            <th class="whitespace-nowrap border-b border-[#E5EAE4] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B]">Stock Adjustment</th>
                            <th class="whitespace-nowrap border-b border-[#E5EAE4] px-4 py-[14px] text-left text-[10px] font-bold uppercase tracking-[0.4px] text-[#78827B]">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            @php
                                $quantity = (float) $item->quantity;
                                $minimum = (float) $item->minimum_stock;

                                if ($quantity <= 0) {
                                    $status = 'Out of Stock';
                                    $badge = 'status-out';
                                } elseif ($quantity <= $minimum) {
                                    $status = 'Low Stock';
                                    $badge = 'status-low';
                                } else {
                                    $status = 'Available';
                                    $badge = 'status-available';
                                }
                            @endphp

                            <tr class="hover:bg-[#FAFCF9]">

                                <td class="border-b border-[#EDF0EC] px-4 py-[15px] align-middle text-xs text-[#4B554E]">
                                    <div class="flex min-w-[210px] items-center gap-3">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}"
                                                 alt="{{ $item->name }}"
                                                 class="h-[42px] w-[42px] shrink-0 rounded-md border border-[#E6EBE5] object-cover">
                                        @else
                                            <div class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-md border border-[#E6EBE5] bg-[#EFF5F0] text-[15px] text-[#008C64]">
                                                <i class="fa-solid fa-box"></i>
                                            </div>
                                        @endif
                                        <div class="flex min-w-0 flex-col gap-[5px]">
                                            <strong class="text-xs font-bold text-[#303A33]">{{ $item->name }}</strong>
                                            <span class="max-w-[190px] text-[11px] leading-[1.4] text-[#929A94]">
                                                {{ $item->description
                                                    ? \Illuminate\Support\Str::limit($item->description, 42)
                                                    : 'No description' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="border-b border-[#EDF0EC] px-4 py-[15px] align-middle text-xs text-[#4B554E]">
                                    <span class="whitespace-nowrap text-[#5C675F]">{{ $item->category }}</span>
                                </td>

                                <td class="border-b border-[#EDF0EC] px-4 py-[15px] align-middle text-xs text-[#4B554E]">
                                    <div class="flex items-baseline gap-[6px] whitespace-nowrap">
                                        <strong class="text-[13px] font-bold text-[#29312D]">
                                            {{ number_format($quantity, 2) }}
                                        </strong>
                                        <span class="text-[11px] text-[#89918B]">{{ $item->unit }}</span>
                                    </div>
                                </td>

                                <td class="border-b border-[#EDF0EC] px-4 py-[15px] align-middle text-xs text-[#4B554E]">
                                    <strong class="font-semibold text-[#5D675F]">
                                        {{ number_format($minimum, 2) }}
                                    </strong>
                                    <span class="text-[11px] text-[#89918B]">{{ $item->unit }}</span>
                                </td>

                                <td class="border-b border-[#EDF0EC] px-4 py-[15px] align-middle text-xs text-[#4B554E]">
                                    <span @class([
                                        'inline-flex items-center gap-[7px] whitespace-nowrap rounded px-[9px] py-[6px] text-[10px] font-bold',
                                        'bg-[#EAF5EF] text-[#087A58]' => $badge === 'status-available',
                                        'bg-[#FFF3DF] text-[#A66A13]' => $badge === 'status-low',
                                        'bg-[#FCEAEA] text-[#C34545]' => $badge === 'status-out',
                                    ])>
                                        <span class="h-[6px] w-[6px] rounded-full bg-current"></span>{{ $status }}
                                    </span>
                                </td>

                                <td class="border-b border-[#EDF0EC] px-4 py-[15px] align-middle text-xs text-[#4B554E]">
                                    <form action="{{ route('staff.inventory.stock', $item->id) }}"
                                          method="POST"
                                          class="flex min-w-[210px] items-center gap-[5px]">
                                        @csrf
                                        <select name="type" required aria-label="Stock movement type"
                                                class="h-[34px] w-[100px] rounded border border-[#DDE3DC] bg-white px-2 text-[11px] text-[#4B554E] outline-none">
                                            <option value="stock_in">Stock In</option>
                                            <option value="stock_out">Stock Out</option>
                                        </select>
                                        <input type="number" name="quantity" min="0.01" step="0.01"
                                               placeholder="Qty" required aria-label="Quantity"
                                               class="h-[34px] w-[60px] rounded border border-[#DDE3DC] bg-white px-2 text-[11px] text-[#4B554E] outline-none">
                                        <button type="submit" title="Save stock adjustment"
                                                class="flex h-[34px] w-8 shrink-0 cursor-pointer items-center justify-center rounded border-0 bg-[#008C64] text-[11px] text-white hover:bg-[#007653]">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                </td>

                                <td class="border-b border-[#EDF0EC] px-4 py-[15px] align-middle text-xs text-[#4B554E]">
                                    <div class="flex items-center gap-[6px]">
                                        <a href="{{ route('staff.inventory.edit', $item->id) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-[5px] border border-[#E1E6E0] bg-white text-[11px] text-[#008C64] no-underline transition-colors hover:border-[#B9DDC8] hover:bg-[#EAF5EF]"
                                           title="Edit Item">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <form action="{{ route('staff.inventory.destroy', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this inventory item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-[5px] border border-[#E1E6E0] bg-white text-[11px] text-[#C34545] transition-colors hover:border-[#EECBCB] hover:bg-[#FCEAEA]"
                                                    title="Delete Item">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-[15px] border-t border-[#E8ECE7] px-[22px] py-4 max-[700px]:px-[15px] max-[480px]:flex-col max-[480px]:items-start">
                <span class="text-[11px] text-[#89918B]">
                    Showing {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }}
                    of {{ $items->total() }} records
                </span>
                <div>
                    {{ $items->withQueryString()->links() }}
                </div>
            </div>

        @else
            <div class="px-5 py-[60px] text-center">
                <div class="mx-auto mb-4 flex h-[60px] w-[60px] items-center justify-center rounded-[7px] bg-[#EFF5F0] text-2xl text-[#008C64]">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h4 class="m-0 mb-[7px] text-base font-bold text-[#303A33]">
                    No inventory items found
                </h4>
                <p class="mb-[18px] mt-0 text-xs text-[#89918B]">
                    @if(request()->hasAny(['search', 'category', 'stock']))
                        Try changing your search or filter criteria.
                    @else
                        Start by adding your first laundry supply.
                    @endif
                </p>

                @if(request()->hasAny(['search', 'category', 'stock']))
                    <a href="{{ route('staff.inventory.index') }}"
                       class="inline-flex min-h-10 items-center justify-center gap-2 rounded-[5px] border border-[#DDE3DC] bg-white px-[15px] text-xs font-semibold text-[#46514A] no-underline hover:bg-[#F5F8F5] hover:text-[#008C64]">
                        Clear Filters
                    </a>
                @else
                    <a href="{{ route('staff.inventory.create') }}"
                       class="inline-flex min-h-10 items-center justify-center gap-2 rounded-[5px] border border-[#008C64] bg-[#008C64] px-[15px] text-xs font-semibold text-white no-underline hover:border-[#007653] hover:bg-[#007653]">
                        <i class="fa-solid fa-plus"></i> Add First Item
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
