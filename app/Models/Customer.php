<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'tenant_id',
        'customer_code',
        'name',
        'name_mm',
        'phone',
        'email',
        'date_of_birth',
        'gender',
        'address',
        'loyalty_points',
        'total_spent',
        'visit_count',
        'last_visit_at',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'loyalty_points' => 'integer',
        'total_spent' => 'decimal:2',
        'visit_count' => 'integer',
        'last_visit_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_code)) {
                $customer->customer_code = self::generateCustomerCode();
            }
        });
    }

    public static function generateCustomerCode(): string
    {
        $lastCustomer = self::orderBy('id', 'desc')->first();
        $nextId = $lastCustomer ? $lastCustomer->id + 1 : 1;
        return 'CUST' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(CustomerLoyaltyTransaction::class);
    }

    // Helper methods
    public function earnPoints(int $points, ?Order $order = null, string $description = null): void
    {
        $balanceBefore = $this->loyalty_points;
        $this->increment('loyalty_points', $points);

        $this->loyaltyTransactions()->create([
            'order_id' => $order?->id,
            'type' => 'earn',
            'points' => $points,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $points,
            'description' => $description ?? 'Points earned',
        ]);
    }

    public function redeemPoints(int $points, ?Order $order = null, string $description = null): bool
    {
        if ($this->loyalty_points < $points) {
            return false;
        }

        $balanceBefore = $this->loyalty_points;
        $this->decrement('loyalty_points', $points);

        $this->loyaltyTransactions()->create([
            'order_id' => $order?->id,
            'type' => 'redeem',
            'points' => -$points,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore - $points,
            'description' => $description ?? 'Points redeemed',
        ]);

        return true;
    }

    public function getPointsValueAttribute(): float
    {
        // 100 points = 1000 Ks
        return ($this->loyalty_points / 100) * 1000;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTopSpenders($query, int $limit = 10)
    {
        return $query->orderByDesc('total_spent')->limit($limit);
    }

    public function scopeFrequentVisitors($query, int $limit = 10)
    {
        return $query->orderByDesc('visit_count')->limit($limit);
    }
}
