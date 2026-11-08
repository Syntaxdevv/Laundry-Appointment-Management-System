<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupDelivery extends Model
{
    protected $fillable = [
        'request_number',
        'customer_name',
        'phone',
        'email',
        'service_type',
        'pickup_address',
        'delivery_address',
        'scheduled_at',
        'instructions',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }
}
