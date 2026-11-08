<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'category',
        'image',
        'quantity',
        'unit',
        'minimum_stock',
        'description',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getStockStatusAttribute(): string
    {
        if ((float) $this->quantity <= 0) {
            return 'Out of Stock';
        }

        if ((float) $this->quantity <= (float) $this->minimum_stock) {
            return 'Low Stock';
        }

        return 'Available';
    }
}