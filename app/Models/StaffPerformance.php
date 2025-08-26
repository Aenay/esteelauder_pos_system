<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'Staff_ID',
        'performance_date',
        'daily_sales_target',
        'actual_sales',
        'orders_processed',
        'customers_served',
        'customer_satisfaction',
        'performance_rating',
        'notes',
    ];

    protected $casts = [
        'performance_date' => 'date',
        'daily_sales_target' => 'decimal:2',
        'actual_sales' => 'decimal:2',
        'customer_satisfaction' => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'Staff_ID', 'Staff_ID');
    }

    public function getTargetAchievementAttribute()
    {
        if ($this->daily_sales_target > 0) {
            return round(($this->actual_sales / $this->daily_sales_target) * 100, 2);
        }
        return 0;
    }

    public function getPerformanceStatusAttribute()
    {
        $achievement = $this->target_achievement;
        if ($achievement >= 100) return 'Excellent';
        if ($achievement >= 80) return 'Good';
        if ($achievement >= 60) return 'Average';
        return 'Needs Improvement';
    }
}
