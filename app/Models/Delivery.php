<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $primaryKey = 'Delivery_ID';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'Supplier_ID',
        'Delivery_Reference',
        'Expected_Delivery_Date',
        'Actual_Delivery_Date',
        'Status',
        'Notes',
        'Total_Amount',
        'Tracking_Number',
        'Carrier',
        'delivery_type',
        'Order_ID',
    ];

    protected $casts = [
        'Expected_Delivery_Date' => 'date',
        'Actual_Delivery_Date' => 'date',
        'Total_Amount' => 'decimal:2',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'Order_ID', 'Order_ID');
    }

    public function deliveryDetails()
    {
        return $this->hasMany(DeliveryDetail::class, 'Delivery_ID', 'Delivery_ID');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $statusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_transit' => 'bg-blue-100 text-blue-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        $color = $statusColors[$this->Status] ?? 'bg-gray-100 text-gray-800';
        
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . 
               ucfirst(str_replace('_', ' ', $this->Status)) . '</span>';
    }

    public function getIsOverdueAttribute()
    {
        return $this->Status === 'pending' && $this->Expected_Delivery_Date < now()->toDateString();
    }

    public function getDaysUntilDeliveryAttribute()
    {
        if ($this->Status === 'delivered') {
            return 0;
        }
        
        return now()->diffInDays($this->Expected_Delivery_Date, false);
    }

    public function scopeSupplier($query)
    {
        return $query->where('delivery_type', 'supplier');
    }

    public function scopeCustomer($query)
    {
        return $query->where('delivery_type', 'customer');
    }
}
