<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableLayoutElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'section_id',
        'type',
        'name',
        'position_x',
        'position_y',
        'width',
        'height',
        'color',
        'rotation',
    ];

    protected $casts = [
        'position_x' => 'integer',
        'position_y' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'rotation' => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function section()
    {
        return $this->belongsTo(TableSection::class, 'section_id');
    }
}
