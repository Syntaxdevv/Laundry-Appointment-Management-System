<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'customer_name',
        'weight_kg',
        'total_price',
        'service_option',
        'pickup_date',
        'pickup_time',
        'notes',
        'status',
        'assigned_staff_id',
        'assigned_delivery_id',
        'delivery_status',
        'payment_method',
        'payment_status',
        'payment_provider',
        'phone',
'pickup_address',
'delivery_address',
'pickup_latitude',
'pickup_longitude',
'delivery_latitude',
'delivery_longitude',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function assignedDelivery(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_delivery_id');
    }
}