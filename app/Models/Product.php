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
        'image'
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'Quantity_on_Hand' => 'integer',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'Product_ID', 'Product_ID');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'Product_ID', 'Product_ID');
    }
}