<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'connection_type', // 'network' or 'usb'
        'ip_address',
        'port',
        'is_active',
        'paper_width',
    ];

    protected function casts(): array
    {
        return [
            'port' => 'integer',
            'is_active' => 'boolean',
            'paper_width' => 'integer',
        ];
    }

    /**
     * Scope a query to only include active printers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include kitchen printers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKitchen($query)
    {
        return $query->where('type', 'kitchen');
    }

    /**
     * Scope a query to only include bar printers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBar($query)
    {
        return $query->where('type', 'Bar');
    }

    /**
     * Scope a query to only include nan pyar printers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNanPyar($query)
    {
        return $query->where('type', 'nan_pyar');
    }

    /**
     * Scope a query to only include receipt printers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReceipt($query)
    {
        return $query->where('type', 'receipt');
    }
}
