```blade
@extends('layouts.admin')

@section('title', 'Edit Inventory Item')

@section('content')
<div class="text-[#29312D]">

    <div class="mb-[25px] flex items-center justify-between gap-5 max-[600px]:flex-col max-[600px]:items-start">
        <div>
            <div class="mb-[9px] flex items-center gap-[9px] text-xs text-[#89918B]">
                <a href="{{ route('admin.inventory.index') }}" class="text-[#008C64] no-underline">Inventory</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <span>Edit Item</span>
            </div>

            <h1 class="m-0 text-[25px] font-bold text-[#252A34]">Edit Inventory Item</h1>
            <p class="mt-1.5 text-[13px] text-[#78827B]">Update the information for {{ $inventory->name }}.</p>
        </div>

        <a href="{{ route('admin.inventory.index') }}"
           class="inline-flex min-h-10 items-center justify-center gap-[9px] rounded-[5px] border border-[#E1E6E0] bg-white px-[15px] text-xs font-semibold text-[#39433D] no-underline hover:bg-[#F3F5F2]">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Inventory
        </a>
    </div>

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

    <form action="{{ route('admin.inventory.update', $inventory->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-[minmax(0,1fr)_265px] items-start gap-5 max-[950px]:grid-cols-1">

            <div class="min-w-0">

                {{-- PRODUCT INFORMATION --}}
                <section class="mb-[18px] rounded-md border border-[#E1E6E0] bg-white p-[23px] max-[600px]:p-[17px]">

                    <div class="mb-[19px] border-b border-[#EDF0EC] pb-[17px]">
                        <h2 class="m-0 text-base font-bold text-[#252A34]">Product Information</h2>
                        <p class="mt-[5px] text-xs text-[#879089]">Update the basic details of your inventory item.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-[18px] max-[600px]:grid-cols-1">

                        <div class="col-span-2 min-w-0 max-[600px]:col-span-1">
                            <label for="name" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Item Name <span class="text-[#D64C4C">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $inventory->name) }}"
                                required
                                class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                            >

                            @error('name')
                                <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="min-w-0">
                            <label for="category" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Category <span class="text-[#D64C4C">*</span>
                            </label>

                            <select id="category" name="category" required
                                    class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]">
                                @foreach(['Detergent', 'Fabric Conditioner', 'Bleach', 'Cleaning Supplies', 'Packaging', 'Other'] as $category)
                                    <option
                                        value="{{ $category }}"
                                        @selected(old('category', $inventory->category) === $category)
                                    >
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
                                Unit <span class="text-[#D64C4C">*</span>
                            </label>

                            <select id="unit" name="unit" required
                                    class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]">
                                @foreach(['pcs', 'bottle', 'liter', 'ml', 'kg', 'gram', 'pack', 'box', 'roll'] as $unit)
                                    <option
                                        value="{{ $unit }}"
                                        @selected(old('unit', $inventory->unit) === $unit)
                                    >
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
                            >{{ old('description', $inventory->description) }}</textarea>

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
                        <p class="mt-[5px] text-xs text-[#879089]">Update the current quantity and minimum stock level.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-[18px] max-[600px]:grid-cols-1">

                        <div class="min-w-0">
                            <label for="quantity" class="mb-2 block text-xs font-semibold text-[#39433D]">
                                Current Quantity <span class="text-[#D64C4C]">*</span>
                            </label>

                            <div class="relative">
                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    value="{{ old('quantity', $inventory->quantity) }}"
                                    min="0"
                                    step="0.01"
                                    required
                                    class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] pr-[75px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                                >

                                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-[#78827B]">
                                    {{ $inventory->unit }}
                                </span>
                            </div>

                            <small class="mt-1.5 block text-[11px] text-[#7A837E]">
                                Enter 0 if the item is out of stock.
                            </small>

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
                                min="0"
                                step="0.01"
                                value="{{ old('minimum_stock', $inventory->minimum_stock) }}"
                                required
                                class="w-full rounded border border-[#DDE3DC] bg-white px-3 py-[11px] text-[13px] text-[#29312D] outline-none focus:border-[#00A878] focus:ring-[3px] focus:ring-[#00A878]/[0.08]"
                            >

                            @error('minimum_stock')
                                <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div id="stock-status-preview" class="mt-[18px] flex items-center gap-3 rounded border border-[#E1E6E0] bg-[#FAFBF9] p-[13px]">
                        <div id="status-indicator" class="h-[10px] w-[10px] shrink-0 rounded-full bg-[#008C64]"></div>

                        <div>
                            <strong id="stock-status-text" class="block text-xs text-[#354039]">Stock Status</strong>
                            <p id="stock-status-description" class="mt-1 text-[11px] text-[#78827B]">
                                Current inventory status.
                            </p>
                        </div>
                    </div>

                    <div class="mt-[14px] flex items-start gap-[10px] rounded border border-[#DDEDE4] bg-[#F5FAF6] px-[13px] py-3 text-[#47745D]">
                        <i class="fa-solid fa-circle-info mt-0.5"></i>
                        <p class="m-0 text-xs leading-[1.6]">
                            Updating the quantity will automatically record
                            a Stock In or Stock Out transaction based on the
                            difference from the previous quantity.
                        </p>
                    </div>
                </section>

                {{-- PRODUCT IMAGE --}}
                <section class="mb-[18px] rounded-md border border-[#E1E6E0] bg-white p-[23px] max-[600px]:p-[17px]">

                    <div class="mb-[19px] border-b border-[#EDF0EC] pb-[17px]">
                        <h2 class="m-0 text-base font-bold text-[#252A34]">Product Image</h2>
                        <p class="mt-[5px] text-xs text-[#879089]">View the current image or upload a replacement.</p>
                    </div>

                    @if($inventory->image)
                        <div class="mb-4">
                            <img
                                src="{{ asset('storage/' . $inventory->image) }}"
                                alt="{{ $inventory->name }}"
                                class="h-[120px] w-[150px] rounded-[5px] border border-[#E1E6E0] object-cover"
                            >
                        </div>
                    @else
                        <p class="mb-4 text-[11px] text-[#7A837E]">No product image uploaded.</p>
                    @endif

                    <label for="image"
                           class="flex min-h-[150px] cursor-pointer flex-col items-center justify-center rounded-[5px] border border-dashed border-[#C8D2C9] bg-[#FAFBF9] p-[22px] text-center">

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

                        <strong class="text-[13px] text-[#354039]">Upload a new image (optional)</strong>
                        <span class="mt-[5px] text-[11px] text-[#89918B]">JPG, JPEG, PNG or WEBP · Maximum 2MB</span>

                        <div id="image-preview" class="mt-[13px] hidden"></div>

                    </label>

                    @error('image')
                        <small class="mt-1.5 block text-[11px] text-[#C53E3E]">{{ $message }}</small>
                    @enderror
                </section>

                {{-- FORM ACTIONS --}}
                <div class="flex justify-end gap-[10px] pt-1 max-[600px]:flex-col-reverse">

                    <a href="{{ route('admin.inventory.index') }}"
                       class="inline-flex min-h-10 items-center justify-center gap-[9px] rounded-[5px] border border-[#E1E6E0] bg-white px-[15px] text-xs font-semibold text-[#39433D] no-underline hover:bg-[#F3F5F2] max-[600px]:w-full">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex min-h-10 items-center justify-center gap-[9px] rounded-[5px] border border-[#008C64] bg-[#008C64] px-[15px] text-xs font-semibold text-white hover:bg-[#007653] max-[600px]:w-full">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Update Inventory Item
                    </button>

                </div>

            </div>

            {{-- SIDEBAR --}}
            <aside class="flex flex-col gap-[15px] max-[950px]:grid max-[950px]:grid-cols-2 max-[600px]:grid-cols-1">

                <div class="rounded-md border border-[#E1E6E0] bg-white p-[18px]">
                    <h3 class="m-0 text-sm font-bold text-[#29312D]">Before Updating</h3>

                    <p class="mb-[17px] mt-1.5 text-[11px] leading-[1.6] text-[#879089]">
                        Review these details before saving your changes.
                    </p>

                    <div class="flex items-start gap-[9px] border-t border-[#EDF0EC] py-[10px] text-[11px] leading-[1.5] text-[#59645C]">
                        <i class="fa-solid fa-circle-check mt-0.5 text-[#00A878]"></i>
                        <span>Verify the item name and category.</span>
                    </div>

                    <div class="flex items-start gap-[9px] border-t border-[#EDF0EC] py-[10px] text-[11px] leading-[1.5] text-[#59645C]">
                        <i class="fa-solid fa-circle-check mt-0.5 text-[#00A878]"></i>
                        <span>Confirm the unit of measurement.</span>
                    </div>

                    <div class="flex items-start gap-[9px] border-t border-[#EDF0EC] py-[10px] text-[11px] leading-[1.5] text-[#59645C]">
                        <i class="fa-solid fa-circle-check mt-0.5 text-[#00A878]"></i>
                        <span>Update the current stock quantity.</span>
                    </div>

                    <div class="flex items-start gap-[9px] border-t border-[#EDF0EC] py-[10px] text-[11px] leading-[1.5] text-[#59645C]">
                        <i class="fa-solid fa-circle-check mt-0.5 text-[#00A878]"></i>
                        <span>Set an appropriate minimum stock level.</span>
                    </div>
                </div>

                <div class="rounded-md border border-[#E1E6E0] bg-[#F8FBF8] p-[18px]">

                    <div class="mb-3 grid h-[34px] w-[34px] place-items-center rounded bg-[#E8F5EF] text-[#008C64]">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>

                    <h3 class="m-0 text-sm font-bold text-[#29312D]">Inventory Tip</h3>

                    <p class="mt-2 text-[11px] leading-[1.7] text-[#78827B]">
                        Keep inventory quantities accurate. Set the quantity
                        to zero when a supply is completely out of stock.
                        Update the quantity whenever new supplies arrive.
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

    // IMAGE PREVIEW
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('image-preview');

    if (imageInput && preview) {
        imageInput.addEventListener('change', function () {

            preview.innerHTML = '';
            preview.classList.add('hidden');

            const file = this.files[0];

            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image.');
                this.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Image must not exceed 2MB.');
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
    }

    // LIVE STOCK STATUS PREVIEW
    const quantityInput = document.getElementById('quantity');
    const minimumStockInput = document.getElementById('minimum_stock');

    const statusPreview = document.getElementById('stock-status-preview');
    const statusIndicator = document.getElementById('status-indicator');
    const statusText = document.getElementById('stock-status-text');
    const statusDescription = document.getElementById('stock-status-description');

    function updateStockStatus() {

        const quantity = parseFloat(quantityInput.value) || 0;
        const minimumStock = parseFloat(minimumStockInput.value) || 0;

        statusPreview.classList.remove(
            'bg-[#FAFBF9]',
            'border-[#E1E6E0]',
            'bg-[#F2FAF5]',
            'border-[#D7EBDD]',
            'bg-[#FFF9ED]',
            'border-[#F0E2BF]',
            'bg-[#FFF3F2]',
            'border-[#F1D8D5]'
        );

        statusIndicator.classList.remove(
            'bg-[#008C64]',
            'bg-[#16A36A]',
            'bg-[#D99A27]',
            'bg-[#D64C4C]'
        );

        if (quantity <= 0) {

            statusPreview.classList.add('bg-[#FFF3F2]', 'border-[#F1D8D5]');
            statusIndicator.classList.add('bg-[#D64C4C]');

            statusText.textContent = 'Out of Stock';
            statusDescription.textContent =
                'This item currently has no available stock.';

        } else if (quantity <= minimumStock) {

            statusPreview.classList.add('bg-[#FFF9ED]', 'border-[#F0E2BF]');
            statusIndicator.classList.add('bg-[#D99A27]');

            statusText.textContent = 'Low Stock';
            statusDescription.textContent =
                'This item has reached or fallen below its minimum stock level.';

        } else {

            statusPreview.classList.add('bg-[#F2FAF5]', 'border-[#D7EBDD]');
            statusIndicator.classList.add('bg-[#16A36A]');

            statusText.textContent = 'Available';
            statusDescription.textContent =
                'This item has sufficient stock available.';

        }
    }

    if (quantityInput && minimumStockInput) {

        quantityInput.addEventListener('input', updateStockStatus);

        minimumStockInput.addEventListener('input', updateStockStatus);

        updateStockStatus();
    }

});
</script>
@endpush