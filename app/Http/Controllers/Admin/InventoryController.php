<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    /**
     * Display inventory items.
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => InventoryItem::count(),

            'in_stock' => InventoryItem::whereColumn(
                'quantity',
                '>',
                'minimum_stock'
            )->count(),

            'low_stock' => InventoryItem::where('quantity', '>', 0)
                ->whereColumn('quantity', '<=', 'minimum_stock')
                ->count(),

            'out_of_stock' => InventoryItem::where('quantity', '<=', 0)
                ->count(),
        ];

        return view('admin.inventory.index', compact('items', 'stats'));
    }

    /**
     * Show the create inventory form.
     */
    public function create()
    {
        return view('admin.inventory.create');
    }

    /**
     * Store a new inventory item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('inventory', 'public');
        }

        DB::transaction(function () use ($validated) {
            $item = InventoryItem::create($validated);

            if ((float) $item->quantity > 0) {
                InventoryTransaction::create([
                    'inventory_item_id' => $item->id,
                    'user_id' => auth()->id(),
                    'type' => 'stock_in',
                    'quantity' => $item->quantity,
                    'balance_after' => $item->quantity,
                    'remarks' => 'Initial inventory stock',
                ]);
            }
        });

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Supply added successfully.');
    }

    /**
     * Show the edit inventory form.
     */
    public function edit($id)
    {
        $inventory = InventoryItem::findOrFail($id);

        return view('admin.inventory.edit', compact('inventory'));
    }

    /**
     * Update inventory information and stock quantity.
     */
    public function update(Request $request, $id)
    {
        $inventory = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        DB::transaction(function () use ($request, $validated, $inventory) {

            $lockedItem = InventoryItem::whereKey($inventory->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldQuantity = (float) $lockedItem->quantity;
            $newQuantity = (float) $validated['quantity'];

            $quantityDifference = abs($newQuantity - $oldQuantity);

            if ($newQuantity > $oldQuantity) {
                $transactionType = 'stock_in';
            } elseif ($newQuantity < $oldQuantity) {
                $transactionType = 'stock_out';
            } else {
                $transactionType = null;
            }

            if ($request->hasFile('image')) {
                $oldImage = $lockedItem->image;

                $newImage = $request->file('image')
                    ->store('inventory', 'public');

                $validated['image'] = $newImage;

                if (
                    $oldImage &&
                    Storage::disk('public')->exists($oldImage)
                ) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $lockedItem->update($validated);

            if ($transactionType !== null) {
                InventoryTransaction::create([
                    'inventory_item_id' => $lockedItem->id,
                    'user_id' => auth()->id(),
                    'type' => $transactionType,
                    'quantity' => $quantityDifference,
                    'balance_after' => $newQuantity,
                    'remarks' => 'Stock quantity updated through Edit Inventory.',
                ]);
            }
        });

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Inventory updated successfully.');
    }

    /**
     * Adjust inventory stock through Stock In / Stock Out.
     */
    public function adjustStock(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:stock_in,stock_out'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $item) {

            $lockedItem = InventoryItem::whereKey($item->id)
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (float) $validated['quantity'];
            $currentStock = (float) $lockedItem->quantity;

            if ($validated['type'] === 'stock_in') {
                $newStock = $currentStock + $quantity;
            } else {
                if ($quantity > $currentStock) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Stock out quantity cannot exceed available stock.',
                    ]);
                }

                $newStock = $currentStock - $quantity;
            }

            $lockedItem->update([
                'quantity' => $newStock,
            ]);

            InventoryTransaction::create([
                'inventory_item_id' => $lockedItem->id,
                'user_id' => auth()->id(),
                'type' => $validated['type'],
                'quantity' => $quantity,
                'balance_after' => $newStock,
                'remarks' => $validated['remarks'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Inventory stock updated successfully.');
    }
}
