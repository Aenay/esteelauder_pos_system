<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $primaryKey = 'Staff_ID';

    protected $fillable = [
        'Staff_Name',
        'Staff_Phone',
        'Staff_Address',
        'email',
        'password',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'Department_ID');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'Staff_ID', 'Staff_ID');
    }

    public function performances()
    {
        return $this->hasMany(StaffPerformance::class, 'Staff_ID', 'Staff_ID');
    }

    public function getTodayPerformanceAttribute()
    {
        return $this->performances()->whereDate('performance_date', today())->first();
    }

    public function getMonthlyPerformanceAttribute()
    {
        return $this->performances()
            ->whereYear('performance_date', now()->year)
            ->whereMonth('performance_date', now()->month)
            ->get();
    }

    public function getTotalSalesAttribute()
    {
        return $this->orders()->sum('Final_Amount');
    }

    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }
}
