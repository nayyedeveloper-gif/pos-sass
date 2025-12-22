<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * @property-read Table|null $table
 * @property-read User|null $waiter
 * @property-read User|null $cashier
 * @property-read Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|OrderItem[] $items
 */
class Order extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'order_number',
        'table_id',
        'waiter_id',
        'cashier_id',
        'order_type',
        'status',
        'subtotal',
        'tax_amount',
        'tax_percentage',
        'discount_amount',
        'discount_percentage',
        'service_charge',
        'service_charge_percentage',
        'total',
        'payment_method',
        'amount_received',
        'change_amount',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'service_charge' => 'decimal:2',
            'service_charge_percentage' => 'decimal:2',
            'total' => 'decimal:2',
            'amount_received' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $maxAttempts = 100;
        $attempt = 0;

        do {
            // Use database locking to prevent race conditions
            $orderNumber = DB::transaction(function () use ($date) {
                $lastOrder = self::withTrashed()
                    ->where('order_number', 'LIKE', $date . '%')
                    ->orderBy('order_number', 'desc')
                    ->lockForUpdate()
                    ->first();

                $sequence = 1;
                if ($lastOrder) {
                    $lastSequence = (int) substr($lastOrder->order_number, -4);
                    $sequence = $lastSequence + 1;
                }

                // Ensure 4-digit sequence
                $sequence = min($sequence, 9999);
                return $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            });

            // Double-check if this order number exists (extra safety)
            if (!self::withTrashed()->where('order_number', $orderNumber)->exists()) {
                return $orderNumber;
            }

            $attempt++;
        } while ($attempt < $maxAttempts);

        // Fallback: Use timestamp if all else fails
        return $date . substr((string) time(), -4);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Table, Order>
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Order>
     */
    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Order>
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Customer, Order>
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<OrderItem>
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<OrderItem>
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('subtotal');
        
        // Calculate tax
        if ($this->tax_percentage > 0) {
            $this->tax_amount = ($this->subtotal * $this->tax_percentage) / 100;
        }
        
        // Calculate discount
        if ($this->discount_percentage > 0) {
            $this->discount_amount = ($this->subtotal * $this->discount_percentage) / 100;
        }
        
        // Calculate total
        $this->total = $this->subtotal + $this->tax_amount - $this->discount_amount + $this->service_charge;
        
        $this->save();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
