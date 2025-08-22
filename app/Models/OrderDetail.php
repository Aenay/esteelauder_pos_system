<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'Order_Detail_ID';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'Order_ID',
        'Product_ID',
        'Quantity',
    ];

    protected $casts = [
        'Quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'Order_ID', 'Order_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }
}
