<?php

namespace Modules\Pharmacy\Models;

use App\Models\Tenant;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drug extends Model
{
    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'name_mm',
        'generic_name',
        'brand',
        'manufacturer',
        'dosage_form',
        'strength',
        'unit',
        'barcode',
        'sku',
        'price',
        'cost_price',
        'stock_quantity',
        'reorder_level',
        'batch_number',
        'expiry_date',
        'requires_prescription',
        'is_controlled',
        'storage_conditions',
        'description',
        'side_effects',
        'contraindications',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'reorder_level' => 'integer',
        'expiry_date' => 'date',
        'requires_prescription' => 'boolean',
        'is_controlled' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExpiringSoon($query, $days = 90)
    {
        return $query->whereDate('expiry_date', '<=', now()->addDays($days))
            ->whereDate('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('expiry_date', '<', now());
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'reorder_level');
    }

    public function scopeRequiresPrescription($query)
    {
        return $query->where('requires_prescription', true);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 90): bool
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= $days;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expiry_date) return null;
        return now()->diffInDays($this->expiry_date, false);
    }
}
