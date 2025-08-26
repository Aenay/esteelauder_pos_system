<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'Delivery_Detail_ID';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'Delivery_ID',
        'Product_ID',
        'Quantity_Ordered',
        'Quantity_Received',
        'Unit_Cost',
        'Total_Cost',
        'Notes',
    ];

    protected $casts = [
        'Unit_Cost' => 'decimal:2',
        'Total_Cost' => 'decimal:2',
    ];

    // Relationships
    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'Delivery_ID', 'Delivery_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    // Accessors
    public function getQuantityRemainingAttribute()
    {
        return $this->Quantity_Ordered - $this->Quantity_Received;
    }

    public function getIsFullyReceivedAttribute()
    {
        return $this->Quantity_Received >= $this->Quantity_Ordered;
    }

    public function getReceiptPercentageAttribute()
    {
        if ($this->Quantity_Ordered == 0) return 0;
        return round(($this->Quantity_Received / $this->Quantity_Ordered) * 100, 1);
    }
}
