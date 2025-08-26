<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $primaryKey = 'Supplier_ID';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'Supplier_Name',
        'Supplier_Phone',
        'Supplier_Address',
    ];

    // Relationships
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'Supplier_ID', 'Supplier_ID');
    }

    // Accessors
    public function getActiveDeliveriesAttribute()
    {
        return $this->deliveries()->whereIn('Status', ['pending', 'in_transit'])->count();
    }

    public function getTotalDeliveriesAttribute()
    {
        return $this->deliveries()->count();
    }
}
