<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /**
     * Display inventory items.
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->stock === 'low') {
            $query->where('quantity', '>', 0)
                ->whereColumn('quantity', '<=', 'minimum_stock');
        } elseif ($request->stock === 'out') {
            $query->where('quantity', '<=', 0);
        } elseif ($request->stock === 'available') {
            $query->whereColumn('quantity', '>', 'minimum_stock');
        }

        $items = $query->latest()
            ->paginate(12)
            ->withQueryString();

        $totalItems = InventoryItem::count();

        $availableItems = InventoryItem::whereColumn(
            'quantity',
            '>',
            'minimum_stock'
        )->count();

        $lowStockItems = InventoryItem::where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'minimum_stock')
            ->count();

        $outOfStockItems = InventoryItem::where('quantity', '<=', 0)
            ->count();

        $categories = InventoryItem::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('staff.inventory.index', compact(
            'items',
            'totalItems',
            'availableItems',
            'lowStockItems',
            'outOfStockItems',
            'categories'
        ));
    }

    /**
     * Show the create inventory form.
     */
   public function create()
{
    $totalItems = InventoryItem::count();

    $availableItems = InventoryItem::whereColumn(
        'quantity',
        '>',
        'minimum_stock'
    )->count();

    $lowStockItems = InventoryItem::where('quantity', '>', 0)
        ->whereColumn('quantity', '<=', 'minimum_stock')
        ->count();

    $outOfStockItems = InventoryItem::where('quantity', '<=', 0)
        ->count();

    $categories = InventoryItem::select('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

$items = InventoryItem::latest()->paginate(12)->withQueryString();

    return view('staff.inventory.create', compact(
        'totalItems',
        'availableItems',
        'lowStockItems',
        'outOfStockItems',
        'categories',
        'items'
    ));
}
    /**
     * Store a new inventory item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('inventory', 'public');
        }

        $item = InventoryItem::create($validated);

        if ((float) $item->quantity > 0) {
            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'user_id' => auth()->id(),
                'type' => 'stock_in',
                'quantity' => $item->quantity,
                'balance_after' => $item->quantity,
                'remarks' => 'Initial stock',
            ]);
        }

        return redirect()
            ->route('staff.inventory.index')
            ->with('success', 'Inventory item added successfully.');
    }

    /**
     * Show the edit inventory form.
     */
    public function edit(InventoryItem $item)
    {
        return view('staff.inventory.edit', compact('item'));
    }

    /**
     * Update an inventory item.
     */
    public function update(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:50'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $validated['image'] = $request->file('image')
                ->store('inventory', 'public');
        }

        $item->update($validated);

        return redirect()
            ->route('staff.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Delete an inventory item.
     */
    public function destroy(InventoryItem $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()
            ->route('staff.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Add or remove inventory stock.
     */
    public function stock(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in(['stock_in', 'stock_out'])
            ],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($validated, $item) {
                $lockedItem = InventoryItem::whereKey($item->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $amount = (float) $validated['quantity'];
                $current = (float) $lockedItem->quantity;

                if (
                    $validated['type'] === 'stock_out'
                    && $amount > $current
                ) {
                    throw new \RuntimeException(
                        'Not enough stock available for this transaction.'
                    );
                }

                $newBalance = $validated['type'] === 'stock_in'
                    ? $current + $amount
                    : $current - $amount;

                $lockedItem->update([
                    'quantity' => $newBalance,
                ]);

                InventoryTransaction::create([
                    'inventory_item_id' => $lockedItem->id,
                    'user_id' => auth()->id(),
                    'type' => $validated['type'],
                    'quantity' => $amount,
                    'balance_after' => $newBalance,
                    'remarks' => $validated['remarks'] ?? null,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors([
                'stock' => $e->getMessage(),
            ]);
        }

        return back()->with(
            'success',
            'Stock updated successfully.'
        );
    }

    /**
     * Display inventory transaction history.
     */
    public function history()
    {
        $transactions = InventoryTransaction::with(['item', 'user'])
            ->latest()
            ->paginate(15);

        return view(
            'staff.inventory.history',
            compact('transactions')
        );
    }
}
