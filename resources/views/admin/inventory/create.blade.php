@extends('layouts.admin')

@section('title', 'Add Inventory Item')

@section('content')
<div class="text-[#29312D]">

    {{-- PAGE HEADER --}}
    <div class="mb-[25px] flex items-center justify-between gap-5 max-[600px]:flex-col max-[600px]:items-start">
        <div>
            <div class="mb-[9px] flex items-center gap-[9px] text-xs text-[#89918B]">
                <a href="{{ route('admin.inventory.index') }}" class="text-[#008C64] no-underline">Inventory</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <span>Add New Item</span>
            </div>

            <h1 class="m-0 text-[25px] font-bold text-[#252A34]">Add Inventory Item</h1>
            <p class="mt-1.5 text-[13px] text-[#78827B]">Add a new supply or material to your inventory.</p>
        </div>

        <a href="{{ route('admin.inventory.index') }}"
           class="inline-flex min-h-10 items-center justify-center gap-[9px] rounded-[5px] border border-[#E1E6E0] bg-white px-[15px] text-xs font-semibold text-[#39433D] no-underline hover:bg-[#F3F5F2]">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Inventory
        </a>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="mb-5 flex gap-3 rounded-[5px] border border-[#F2D4D1] bg-[#FFF1F0] px-[15px] py-[13px] text-[13px] text-[#9B3030]">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <strong>Please check the form.</strong>
                <ul class="mt-1.5 list-disc pl-[18px]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.inventory.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="grid grid-cols-[minmax(0,1fr)_265px] items-start gap-5 max-[950px]:grid-cols-1">

            {{-- MAIN FORM --}}
            <div class="min-w-0">

                {{-- PRODUCT INFORMATION --}}
                <section class="mb-[18px] rounded-md border border-[#E1E6E0] bg-white p-[23px] max-[600px]:p-[17px]">

                    <div class="mb-[19px] border-b border-[#EDF0EC] pb-[17px]">
                        <h2 class="m-0 text-base font-bold text-[#252A34]">Product Information</h2>
                        <p class="mt-[5px] text-xs text-[#879089]">Enter the basic details of your inventory item.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-[18px] max-[600px]:grid-cols-1">

                        <div class="col-span-2 min-w-0 max-[600px]:col-span-1">
                            <label for="name" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Item Name <span class="text-[#D64C4C]">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="e.g. Liquid Detergent"
                                required
                                class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                            >

                            @error('name')
                                <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="min-w-0">
                            <label for="category" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Category <span class="text-[#D64C4C]">*</span>
                            </label>

                            <select
                                id="category"
                                name="category"
                                required
                                class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                            >
                                <option value="">Select category</option>

                                @foreach(['Detergent', 'Fabric Conditioner', 'Bleach', 'Cleaning Supplies', 'Packaging', 'Other'] as $category)
                                    <option value="{{ $category }}" @selected(old('category') === $category)>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category')
                                <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="min-w-0">
                            <label for="unit" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Unit <span class="text-[#D64C4C]">*</span>
                            </label>

                            <select
                                id="unit"
                                name="unit"
                                required
                                class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                            >
                                <option value="">Select unit</option>

                                @foreach(['pcs', 'bottle', 'liter', 'ml', 'kg', 'gram', 'pack', 'box', 'roll'] as $unit)
                                    <option value="{{ $unit }}" @selected(old('unit') === $unit)>
                                        {{ ucfirst($unit) }}
                                    </option>
                                @endforeach
                            </select>

                            @error('unit')
                                <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-span-2 min-w-0 max-[600px]:col-span-1">
                            <label for="description" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Description
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="Optional description about this item..."
                                class="min-h-[95px] w-full resize-y rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </section>

                {{-- STOCK CONFIGURATION --}}
                <section class="mb-[18px] rounded-md border border-[#E1E6E0] bg-white p-[23px] max-[600px]:p-[17px]">

                    <div class="mb-[19px] border-b border-[#EDF0EC] pb-[17px]">
                        <h2 class="m-0 text-base font-bold text-[#252A34]">Stock Configuration</h2>
                        <p class="mt-[5px] text-xs text-[#879089]">Set the starting quantity and minimum stock level.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-[18px] max-[600px]:grid-cols-1">

                        <div class="min-w-0">
                            <label for="quantity" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Initial Quantity <span class="text-[#D64C4C]">*</span>
                            </label>

                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                value="{{ old('quantity', 0) }}"
                                min="0"
                                step="0.01"
                                required
                                class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                            >

                            @error('quantity')
                                <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="min-w-0">
                            <label for="minimum_stock" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Minimum Stock Level <span class="text-[#D64C4C]">*</span>
                            </label>

                            <input
                                type="number"
                                id="minimum_stock"
                                name="minimum_stock"
                                value="{{ old('minimum_stock', 5) }}"
                                min="0"
                                step="0.01"
                                required
                                class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                            >

                            @error('minimum_stock')
                                <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-[18px] flex items-start gap-[10px] rounded border border-[#DDEDE4] bg-[#F5FAF6] px-[13px] py-3 text-[#47745D]">
                        <i class="fa-solid fa-circle-info mt-0.5"></i>
                        <p class="m-0 text-xs leading-[1.6]">
                            The item will be marked as low stock when its quantity reaches or falls below the minimum stock level.
                        </p>
                    </div>
                </section>

                {{-- PRODUCT IMAGE --}}
                <section class="mb-[18px] rounded-md border border-[#E1E6E0] bg-white p-[23px] max-[600px]:p-[17px]">

                    <div class="mb-[19px] border-b border-[#EDF0EC] pb-[17px]">
                        <h2 class="m-0 text-base font-bold text-[#252A34]">Product Image</h2>
                        <p class="mt-[5px] text-xs text-[#879089]">Upload an image to help identify this inventory item.</p>
                    </div>

                    <label
                        for="image"
                        class="flex min-h-[165px] cursor-pointer flex-col items-center justify-center rounded-[5px] border border-dashed border-[#C8D2C9] bg-[#FAFBF9] p-[22px] text-center"
                    >
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="hidden"
                        >

                        <div class="mb-3 grid h-[42px] w-[42px] place-items-center rounded-[5px] bg-[#E8F5EF] text-[17px] text-[#008C64]">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>

                        <strong class="text-[13px] text-[#354039]">Choose an image to upload</strong>
                        <span class="mt-[5px] text-[11px] text-[#89918B]">JPG, JPEG, PNG or WEBP · Maximum 2MB</span>

                        <div id="image-preview" class="mt-[13px] hidden">
                        </div>
                    </label>

                    @error('image')
                        <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                    @enderror
                </section>

                {{-- FORM ACTIONS --}}
                <div class="flex justify-end gap-[10px] pt-1 max-[600px]:flex-col-reverse">

                    <a
                        href="{{ route('admin.inventory.index') }}"
                        class="inline-flex min-h-10 items-center justify-center gap-[9px] rounded-[5px] border border-[#E1E6E0] bg-white px-[15px] text-xs font-semibold text-[#39433D] no-underline hover:bg-[#F3F5F2] max-[600px]:w-full"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex min-h-10 items-center justify-center gap-[9px] rounded-[5px] border border-[#008C64] bg-[#008C64] px-[15px] text-xs font-semibold text-white hover:bg-[#007653] max-[600px]:w-full"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Add Inventory Item
                    </button>

                </div>

            </div>

            {{-- SIDEBAR --}}
            <aside class="flex flex-col gap-[15px] max-[950px]:grid max-[950px]:grid-cols-2 max-[600px]:grid-cols-1">

                <div class="rounded-md border border-[#E1E6E0] bg-white p-[18px]">
                    <h3 class="m-0 text-sm font-bold text-[#29312D]">Before Saving</h3>

                    <p class="mb-[17px] mt-1.5 text-[11px] leading-[1.6] text-[#879089]">
                        Review these details before adding the item.
                    </p>

                    <div class="flex items-start gap-[9px] border-t border-[#EDF0EC] py-[10px] text-[11px] leading-[1.5] text-[#59645C]">
                        <i class="fa-solid fa-circle-check mt-0.5 text-[#00A878]"></i>
                        <span>Enter the correct item name.</span>
                    </div>

                    <div class="flex items-start gap-[9px] border-t border-[#EDF0EC] py-[10px] text-[11px] leading-[1.5] text-[#59645C]">
                        <i class="fa-solid fa-circle-check mt-0.5 text-[#00A878]"></i>
                        <span>Select the appropriate category and unit.</span>
                    </div>

                    <div class="flex items-start gap-[9px] border-t border-[#EDF0EC] py-[10px] text-[11px] leading-[1.5] text-[#59645C]">
                        <i class="fa-solid fa-circle-check mt-0.5 text-[#00A878]"></i>
                        <span>Set the initial quantity accurately.</span>
                    </div>

                    <div class="flex items-start gap-[9px] border-t border-[#EDF0EC] py-[10px] text-[11px] leading-[1.5] text-[#59645C]">
                        <i class="fa-solid fa-circle-check mt-0.5 text-[#00A878]"></i>
                        <span>Choose a minimum stock level.</span>
                    </div>
                </div>

                <div class="rounded-md border border-[#E1E6E0] bg-[#F8FBF8] p-[18px]">

                    <div class="mb-3 grid h-[34px] w-[34px] place-items-center rounded bg-[#E8F5EF] text-[#008C64]">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>

                    <h3 class="m-0 text-sm font-bold text-[#29312D]">Inventory Tip</h3>

                    <p class="mt-2 text-[11px] leading-[1.7] text-[#78827B]">
                        Set a reasonable minimum stock level so staff can identify supplies that need replenishment.
                    </p>

                </div>

            </aside>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput = document.getElementById('image');
    const preview = document.getElementById('image-preview');

    if (!imageInput || !preview) return;

    imageInput.addEventListener('change', function () {

        preview.innerHTML = '';
        preview.classList.add('hidden');

        const file = this.files[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            this.value = '';
            return;
        }

        const image = document.createElement('img');

        image.src = URL.createObjectURL(file);
        image.alt = 'Selected inventory image';

        image.className = 'h-[85px] w-[110px] rounded border border-[#E1E6E0] object-cover';

        preview.appendChild(image);
        preview.classList.remove('hidden');

    });

});
</script>
@endpush
