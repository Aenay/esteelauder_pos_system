<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'branches';

    protected $fillable = [
        'branch_code',
        'branch_name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'email',
        'manager_name',
        'manager_phone',
        'manager_email',
        'opening_hours',
        'status',
        'notes'
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'status' => 'string'
    ];

    // Relationships
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->postal_code}, {$this->country}";
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-red-100 text-red-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            'closed' => 'bg-gray-100 text-gray-800'
        ];

        $color = $statuses[$this->status] ?? 'bg-gray-100 text-gray-800';
        
        return "<span class='px-2 py-1 text-xs font-medium rounded-full {$color}'>{$this->status}</span>";
    }

    public function getStaffCountAttribute()
    {
        return $this->staff()->count();
    }

    public function getOrderCountAttribute()
    {
        return $this->orders()->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', '!=', 'active');
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }
}
