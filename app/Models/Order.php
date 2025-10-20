<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'Order_ID';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'Order_Date',
        'Staff_ID',
        'Customer_ID',
        'customer_type',
        'Promotion_ID',
        'Subtotal',
        'Discount_Amount',
        'Final_Amount',
        'payment_method',
        'payment_status',
        'transaction_id',
    ];

    protected $casts = [
        'Order_Date' => 'date',
        'Subtotal' => 'decimal:2',
        'Discount_Amount' => 'decimal:2',
        'Final_Amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'Customer_ID', 'Customer_ID');
    }

    /**
     * Check if this order is eligible for loyalty points
     */
    public function isEligibleForLoyalty(): bool
    {
        return $this->customer_type !== 'walk_in' && 
               $this->customer && 
               $this->customer->Customer_Type !== 'walk_in';
    }

    /**
     * Get the amount eligible for loyalty points calculation
     */
    public function getLoyaltyEligibleAmount(): float
    {
        return $this->Final_Amount;
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'Staff_ID', 'Staff_ID');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'Order_ID', 'Order_ID');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'Order_ID', 'Order_ID');
    }
}
