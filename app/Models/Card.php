<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'card_number',
        'customer_id',
        'balance',
        'status',
        'card_type',
        'issued_date',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CardTransaction::class);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->expiry_date === null || $this->expiry_date->isFuture());
    }

    public function hasBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    public function addBalance(float $amount, string $paymentMethod = null, float $bonusAmount = 0, int $userId = null): void
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount + $bonusAmount;
        $this->save();

        CardTransaction::create([
            'card_id' => $this->id,
            'transaction_type' => 'load',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'payment_method' => $paymentMethod,
            'bonus_amount' => $bonusAmount,
            'description' => $bonusAmount > 0 
                ? "Loaded {$amount} Ks + {$bonusAmount} Ks bonus" 
                : "Loaded {$amount} Ks",
            'created_by' => $userId ?? auth()->id(),
        ]);
    }

    public function deductBalance(float $amount, int $orderId = null, int $userId = null): void
    {
        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->save();

        CardTransaction::create([
            'card_id' => $this->id,
            'transaction_type' => 'payment',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'order_id' => $orderId,
            'description' => "Payment for order #{$orderId}",
            'created_by' => $userId ?? auth()->id(),
        ]);
    }

    public function refund(float $amount, int $orderId = null, int $userId = null): void
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->save();

        CardTransaction::create([
            'card_id' => $this->id,
            'transaction_type' => 'refund',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'order_id' => $orderId,
            'description' => "Refund for order #{$orderId}",
            'created_by' => $userId ?? auth()->id(),
        ]);
    }

    // Generate unique card number
    public static function generateCardNumber(): string
    {
        do {
            $cardNumber = 'TC' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (self::where('card_number', $cardNumber)->exists());

        return $cardNumber;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithBalance($query)
    {
        return $query->where('balance', '>', 0);
    }
}
