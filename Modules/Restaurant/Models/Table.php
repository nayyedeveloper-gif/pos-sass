<?php

namespace Modules\Restaurant\Models;

use App\Models\Tenant;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'name_mm',
        'capacity',
        'status',
        'position_x',
        'position_y',
        'floor',
        'section',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'position_x' => 'integer',
        'position_y' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_OCCUPIED = 'occupied';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_CLEANING = 'cleaning';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrder()
    {
        return $this->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->first();
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)
            ->where('is_active', true);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', self::STATUS_OCCUPIED);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function markAsOccupied(): void
    {
        $this->update(['status' => self::STATUS_OCCUPIED]);
    }

    public function markAsAvailable(): void
    {
        $this->update(['status' => self::STATUS_AVAILABLE]);
    }

    public function markAsReserved(): void
    {
        $this->update(['status' => self::STATUS_RESERVED]);
    }
}
