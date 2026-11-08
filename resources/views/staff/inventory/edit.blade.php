@extends('layouts.staff')

@section('title', 'Edit Inventory Item')
@section('page-heading', '')

@section('content')
<div class="inventory-edit-page w-full max-w-[1100px] text-[#29312D]">

    <div class="edit-page-header mb-6 flex flex-wrap items-end justify-between gap-5 max-[600px]:flex-col max-[600px]:items-start">
        <div>
            <span class="edit-eyebrow mb-[9px] inline-block text-[10px] font-bold tracking-[1.2px] text-[#008C64]">INVENTORY MANAGEMENT</span>
            <h2 class="m-0 text-[26px] font-bold tracking-[-0.5px] text-[#252A34]">Edit Supply</h2>
            <p class="mt-[7px] text-[13px] leading-[1.6] text-[#7A837E]">
                Update the information for <strong class="font-semibold text-[#46514A]">{{ $item->name }}</strong>.
            </p>
        </div>

        <a href="{{ route('staff.inventory.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#59645D] no-underline transition-colors hover:text-[#008C64]">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Inventory
        </a>
    </div>

    @if(session('success'))
        <div class="mb-[18px] flex items-start gap-[10px] rounded-[5px] border border-[#D5EBDD] bg-[#EAF5EF] px-[15px] py-[13px] text-xs leading-[1.6] text-[#087A58]">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-[18px] flex items-start gap-[10px] rounded-[5px] border border-[#F1D8D5] bg-[#FFF3F2] px-[15px] py-[13px] text-xs leading-[1.6] text-[#B6423A]">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('staff.inventory.update', $item) }}"
          method="POST"
          enctype="multipart/form-data"
          class="edit-inventory-form">

        @csrf
        @method('PUT')

        <div class="edit-form-layout grid grid-cols-[minmax(0,1.6fr)_minmax(270px,1fr)] items-start gap-5 max-[850px]:grid-cols-1">

            <div class="edit-form-main grid min-w-0 gap-5">

                <section class="edit-section rounded-[7px] border border-[#E1E6E0] bg-white p-[22px] max-[600px]:p-[17px]">
                    <div class="edit-section-heading mb-[22px] flex items-center gap-3">
                        <div class="edit-section-icon flex h-[38px] w-[38px] flex-[0_0_38px] items-center justify-center rounded-md bg-[#EAF5EF] text-[15px] text-[#008C64]">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <h3 class="m-0 text-[15px] font-bold text-[#303A33]">Basic Information</h3>
                            <p class="mt-[5px] text-[11px] leading-[1.5] text-[#89918B]">Update the supply details and classification.</p>
                        </div>
                    </div>

                    <div class="edit-field mb-[18px] flex min-w-0 flex-col gap-2 last:mb-0">
                        <label for="name" class="text-xs font-semibold text-[#46514A]">Product Name <span class="text-[#C34545">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $item->name) }}"
                            placeholder="Enter product name"
                            required
                            class="min-h-[42px] w-full rounded-[5px] border border-[#DDE3DC] bg-white px-3 py-[10px] font-[inherit] text-xs text-[#39433D] outline-none transition focus:border-[#00A878] focus:shadow-[0_0_0_3px_rgba(0,168,120,0.08)]"
                        >
                    </div>

                    <div class="edit-field mb-[18px] flex min-w-0 flex-col gap-2 last:mb-0">
                        <label for="category" class="text-xs font-semibold text-[#46514A]">Category <span class="text-[#C34545">*</span></label>
                        <select id="category" name="category" required class="min-h-[42px] w-full rounded-[5px] border border-[#DDE3DC] bg-white px-3 py-[10px] font-[inherit] text-xs text-[#39433D] outline-none transition focus:border-[#00A878] focus:shadow-[0_0_0_3px_rgba(0,168,120,0.08)]">
                            @foreach(['Cleaning Supplies', 'Laundry Supplies', 'Packaging Supplies', 'Other'] as $category)
                                <option value="{{ $category }}"
                                    @selected(old('category', $item->category) === $category)>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="edit-field mb-[18px] flex min-w-0 flex-col gap-2 last:mb-0">
                        <label for="description" class="text-xs font-semibold text-[#46514A]">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Add a short description of this supply..."
                            class="min-h-[105px] w-full resize-y rounded-[5px] border border-[#DDE3DC] bg-white px-3 py-[10px] font-[inherit] text-xs leading-[1.6] text-[#39433D] outline-none transition focus:border-[#00A878] focus:shadow-[0_0_0_3px_rgba(0,168,120,0.08)]"
                        >{{ old('description', $item->description) }}</textarea>
                    </div>
                </section>

                <section class="edit-section rounded-[7px] border border-[#E1E6E0] bg-white p-[22px] max-[600px]:p-[17px]">
                    <div class="edit-section-heading mb-[22px] flex items-center gap-3">
                        <div class="edit-section-icon flex h-[38px] w-[38px] flex-[0_0_38px] items-center justify-center rounded-md bg-[#EAF5EF] text-[15px] text-[#008C64]">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <h3 class="m-0 text-[15px] font-bold text-[#303A33]">Stock Information</h3>
                            <p class="mt-[5px] text-[11px] leading-[1.5] text-[#89918B]">Manage the unit and minimum stock alert level.</p>
                        </div>
                    </div>

                    <div class="edit-stock-grid mb-[18px] grid grid-cols-2 gap-[15px] max-[600px]:grid-cols-1">
                        <div class="edit-field mb-0 flex min-w-0 flex-col gap-2">
                            <label for="quantity" class="text-xs font-semibold text-[#46514A]">Current Quantity</label>
                            <input
                                type="text"
                                id="quantity"
                                value="{{ $item->quantity }} {{ $item->unit }}"
                                disabled
                                class="min-h-[42px] w-full cursor-not-allowed rounded-[5px] border border-[#DDE3DC] bg-[#F5F7F4] px-3 py-[10px] font-[inherit] text-xs text-[#68736B] outline-none"
                            >
                            <small class="text-[11px] leading-[1.5] text-[#89918B]">Use Stock In / Stock Out to adjust quantity.</small>
                        </div>

                        <div class="edit-field mb-0 flex min-w-0 flex-col gap-2">
                            <label for="unit" class="text-xs font-semibold text-[#46514A]">Unit <span class="text-[#C34545">*</span></label>
                            <select id="unit" name="unit" required class="min-h-[42px] w-full rounded-[5px] border border-[#DDE3DC] bg-white px-3 py-[10px] font-[inherit] text-xs text-[#39433D] outline-none transition focus:border-[#00A878] focus:shadow-[0_0_0_3px_rgba(0,168,120,0.08)]">
                                @foreach(['pieces', 'liters', 'bottles', 'packs', 'kilograms', 'boxes', 'rolls'] as $unit)
                                    <option value="{{ $unit }}"
                                        @selected(old('unit', $item->unit) === $unit)>
                                        {{ ucfirst($unit) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="edit-field mb-[18px] flex min-w-0 flex-col gap-2 last:mb-0">
                        <label for="minimum_stock" class="text-xs font-semibold text-[#46514A]">Minimum Stock Alert <span class="text-[#C34545">*</span></label>
                        <input
                            type="number"
                            id="minimum_stock"
                            name="minimum_stock"
                            min="0"
                            step="0.01"
                            value="{{ old('minimum_stock', $item->minimum_stock) }}"
                            required
                            class="min-h-[42px] w-full rounded-[5px] border border-[#DDE3DC] bg-white px-3 py-[10px] font-[inherit] text-xs text-[#39433D] outline-none transition focus:border-[#00A878] focus:shadow-[0_0_0_3px_rgba(0,168,120,0.08)]"
                        >
                        <small class="text-[11px] leading-[1.5] text-[#89918B]">You'll see a low-stock warning when the quantity reaches this level.</small>
                    </div>
                </section>

            </div>

            <aside class="edit-form-sidebar grid min-w-0 gap-5 max-[850px]:grid-cols-2 max-[850px]:items-start max-[600px]:grid-cols-1">
                <section class="edit-section image-section rounded-[7px] border border-[#E1E6E0] bg-white p-[22px] max-[600px]:p-[17px]">
                    <div class="edit-section-heading mb-[22px] flex items-center gap-3">
                        <div class="edit-section-icon flex h-[38px] w-[38px] flex-[0_0_38px] items-center justify-center rounded-md bg-[#EAF5EF] text-[15px] text-[#008C64]">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <div>
                            <h3 class="m-0 text-[15px] font-bold text-[#303A33]">Product Image</h3>
                            <p class="mt-[5px] text-[11px] leading-[1.5] text-[#89918B]">Update the supply photo.</p>
                        </div>
                    </div>

                    <div class="current-image mb-5 flex min-h-[190px] w-full items-center justify-center overflow-hidden rounded-md border border-[#E5EAE4] bg-[#F8FAF7]">
                        @if($item->image)
                            <img
                                src="{{ asset('storage/' . $item->image) }}"
                                alt="{{ $item->name }}"
                                class="block max-h-[280px] w-full object-contain"
                            >
                        @else
                            <div class="image-placeholder flex flex-col items-center gap-[10px] p-[25px] text-xs text-[#929A94]">
                                <i class="fa-solid fa-box-open text-[30px] text-[#B5C2B8]"></i>
                                <span>No image uploaded</span>
                            </div>
                        @endif
                    </div>

                    <div class="edit-field upload-field mb-[18px] flex min-w-0 flex-col gap-2 last:mb-0">
                        <label for="image" class="text-xs font-semibold text-[#46514A]">Upload New Image</label>
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/png,image/jpeg,image/webp"
                            class="w-full rounded-[5px] border border-dashed border-[#CBD5CC] bg-[#FAFCF9] p-[10px] text-[11px] text-[#68736B]"
                        >
                        <small class="text-[11px] leading-[1.5] text-[#89918B]">Optional. PNG, JPG, or WEBP.</small>
                    </div>
                </section>

                <section class="edit-item-meta rounded-[7px] border border-[#E1E6E0] bg-[#FAFCF9] px-[22px] py-[18px]">
                    <div class="meta-row flex justify-between gap-[15px] border-b border-[#E8ECE7] py-[10px] text-[11px] first:pt-0 last:border-0 last:pb-0">
                        <span class="text-[#89918B]">Item ID</span>
                        <strong class="text-right font-semibold text-[#46514A]">#{{ $item->id }}</strong>
                    </div>
                    <div class="meta-row flex justify-between gap-[15px] border-b border-[#E8ECE7] py-[10px] text-[11px] last:border-0 last:pb-0">
                        <span class="text-[#89918B]">Created</span>
                        <strong class="text-right font-semibold text-[#46514A]">{{ $item->created_at?->format('M d, Y') ?? '—' }}</strong>
                    </div>
                    <div class="meta-row flex justify-between gap-[15px] border-b border-[#E8ECE7] py-[10px] text-[11px] last:border-0 last:pb-0">
                        <span class="text-[#89918B]">Last Updated</span>
                        <strong class="text-right font-semibold text-[#46514A]">{{ $item->updated_at?->format('M d, Y') ?? '—' }}</strong>
                    </div>
                </section>
            </aside>

        </div>

        <div class="edit-form-actions mt-5 flex items-center justify-end gap-[10px] border-t border-[#E1E6E0] pt-[18px] max-[600px]:flex-col-reverse max-[600px]:items-stretch">
            <a href="{{ route('staff.inventory.index') }}" class="inline-flex min-h-[42px] items-center justify-center gap-2 rounded-[5px] border border-[#DDE3DC] bg-white px-[17px] text-xs font-semibold text-[#59645D] no-underline transition-colors hover:bg-[#F5F8F5] max-[600px]:w-full">
                Cancel
            </a>

            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center gap-2 rounded-[5px] border border-[#008C64] bg-[#008C64] px-[17px] text-xs font-semibold text-white transition-colors hover:border-[#007653] hover:bg-[#007653] max-[600px]:w-full">
                <i class="fa-solid fa-floppy-disk"></i>
                Update Item
            </button>
        </div>

    </form>
</div>
@endsection
