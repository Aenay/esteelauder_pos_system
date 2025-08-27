<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'Customer_ID';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'Customer_Name',
        'Customer_Phone',
        'Customer_Address',
        'Customer_Email',
        'Customer_Type',
        'Registration_Date',
    ];

    protected $casts = [
        'Registration_Date' => 'date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'Customer_ID', 'Customer_ID');
    }

    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoint::class, 'Customer_ID', 'Customer_ID');
    }

    public function getLoyaltyTierAttribute()
    {
        return $this->loyaltyPoints?->tier_level ?? 'none';
    }

    public function getLoyaltyPointsAttribute()
    {
        return $this->loyaltyPoints?->current_balance ?? 0;
    }
}
