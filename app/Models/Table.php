<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'section_id',
        'floor',
        'name',
        'name_mm',
        'capacity',
        'shape',
        'position_x',
        'position_y',
        'width',
        'height',
        'merged_with',
        'is_merged',
        'merge_parent_id',
        'current_order_id',
        'occupied_at',
        'guest_count',
        'waiter_id',
        'status',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'is_active' => 'boolean',
            'is_merged' => 'boolean',
            'sort_order' => 'integer',
            'floor' => 'integer',
            'position_x' => 'integer',
            'position_y' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'guest_count' => 'integer',
            'merged_with' => 'array',
            'occupied_at' => 'datetime',
        ];
    }

    public function section()
    {
        return $this->belongsTo(TableSection::class, 'section_id');
    }

    public function currentOrder()
    {
        return $this->belongsTo(Order::class, 'current_order_id');
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function mergeParent()
    {
        return $this->belongsTo(Table::class, 'merge_parent_id');
    }

    public function mergedTables()
    {
        return $this->hasMany(Table::class, 'merge_parent_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrder()
    {
        return $this->hasOne(Order::class)
            ->whereIn('status', ['pending', 'preparing'])
            ->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->is_active;
    }

    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }
}
