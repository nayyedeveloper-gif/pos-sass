<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class SignageMedia extends Model
{
    use BelongsToTenant;
    protected $table = 'signage_media';
    
    protected $fillable = [
        'tenant_id',
        'title',
        'title_mm',
        'type',
        'file_path',
        'duration',
        'sort_order',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
