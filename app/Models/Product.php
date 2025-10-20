<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'Product_ID';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'Product_Name',
        'SKU',
        'Price',
        'Quantity_on_Hand',
        'description',
        'image',
        'Supplier_ID',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'Quantity_on_Hand' => 'integer',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'Product_ID', 'Product_ID');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'Product_ID', 'Product_ID');
    }

    public function deliveryDetails()
    {
        return $this->hasMany(DeliveryDetail::class, 'Product_ID', 'Product_ID');
    }

    // Inventory Management Methods
    public function updateStock($quantity, $type = 'add')
    {
        if ($type === 'add') {
            $this->Quantity_on_Hand += $quantity;
        } elseif ($type === 'subtract') {
            $this->Quantity_on_Hand = max(0, $this->Quantity_on_Hand - $quantity);
        }
        
        $this->save();
        return $this;
    }

    public function getLowStockAttribute()
    {
        // Consider low stock if less than 10 items
        return $this->Quantity_on_Hand < 10;
    }

    public function getStockStatusAttribute()
    {
        if ($this->Quantity_on_Hand == 0) {
            return 'out_of_stock';
        } elseif ($this->Quantity_on_Hand < 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusBadgeAttribute()
    {
        $statusColors = [
            'out_of_stock' => 'bg-red-100 text-red-800',
            'low_stock' => 'bg-yellow-100 text-yellow-800',
            'in_stock' => 'bg-green-100 text-green-800',
        ];

        $statusText = [
            'out_of_stock' => 'Out of Stock',
            'low_stock' => 'Low Stock',
            'in_stock' => 'In Stock',
        ];

        $color = $statusColors[$this->stock_status] ?? 'bg-gray-100 text-gray-800';
        
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . 
               $statusText[$this->stock_status] . '</span>';
    }
}