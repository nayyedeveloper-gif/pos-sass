<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'order_id',
        'item_id',
        'quantity',
        'foc_quantity',
        'price',
        'subtotal',
        'is_foc',
        'notes',
        'status',
        'is_printed',
        'printed_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'foc_quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'is_foc' => 'boolean',
        'is_printed' => 'boolean',
        'printed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($orderItem) {
            // Calculate subtotal based on chargeable quantity (quantity - foc_quantity)
            $focQuantity = $orderItem->foc_quantity ?? 0;
            $chargeableQuantity = $orderItem->quantity - $focQuantity;
            $orderItem->subtotal = $chargeableQuantity * $orderItem->price;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Order, OrderItem>
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Item, OrderItem>
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeUnprinted($query)
    {
        return $query->where('is_printed', false);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
