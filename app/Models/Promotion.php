<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $primaryKey = 'Promotion_ID';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'Promotion_Name',
        'Description',
        'Discount_Type',
        'Discount_Value',
        'Start_Date',
        'End_Date',
        'Is_Active',
    ];

    protected $casts = [
        'Discount_Value' => 'decimal:2',
        'Start_Date' => 'date',
        'End_Date' => 'date',
        'Is_Active' => 'boolean',
    ];

    public function isCurrentlyActive(): bool
    {
        $today = now()->startOfDay();
        $withinStart = $this->Start_Date ? $today->gte($this->Start_Date) : true;
        $withinEnd = $this->End_Date ? $today->lte($this->End_Date) : true;
        return $this->Is_Active && $withinStart && $withinEnd;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'Promotion_ID', 'Promotion_ID');
    }
}
