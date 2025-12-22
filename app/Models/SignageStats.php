<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SignageStats extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'tenant_id',
        'date',
        'total_views',
        'category_rotations',
        'media_displays',
        'total_uptime_minutes',
        'popular_categories',
        'media_views',
    ];

    protected $casts = [
        'date' => 'date',
        'total_views' => 'integer',
        'category_rotations' => 'integer',
        'media_displays' => 'integer',
        'total_uptime_minutes' => 'integer',
        'popular_categories' => 'array',
        'media_views' => 'array',
    ];

    public static function recordView()
    {
        $today = Carbon::today();
        
        $stats = self::firstOrCreate(
            ['date' => $today],
            [
                'total_views' => 0,
                'category_rotations' => 0,
                'media_displays' => 0,
                'total_uptime_minutes' => 0,
            ]
        );
        
        $stats->increment('total_views');
    }

    public static function recordCategoryRotation($categoryId)
    {
        $today = Carbon::today();
        
        $stats = self::firstOrCreate(['date' => $today]);
        $stats->increment('category_rotations');
        
        $popularCategories = $stats->popular_categories ?? [];
        $popularCategories[$categoryId] = ($popularCategories[$categoryId] ?? 0) + 1;
        $stats->update(['popular_categories' => $popularCategories]);
    }

    public static function recordMediaDisplay($mediaId)
    {
        $today = Carbon::today();
        
        $stats = self::firstOrCreate(['date' => $today]);
        $stats->increment('media_displays');
        
        $mediaViews = $stats->media_views ?? [];
        $mediaViews[$mediaId] = ($mediaViews[$mediaId] ?? 0) + 1;
        $stats->update(['media_views' => $mediaViews]);
    }

    public static function recordUptime($minutes = 1)
    {
        $today = Carbon::today();
        
        $stats = self::firstOrCreate(['date' => $today]);
        $stats->increment('total_uptime_minutes', $minutes);
    }

    public static function getTodayStats()
    {
        return self::whereDate('date', Carbon::today())->first();
    }

    public static function getWeekStats()
    {
        return self::whereBetween('date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->get();
    }

    public static function getMonthStats()
    {
        return self::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->get();
    }
}
