<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $primaryKey = 'Staff_ID';

    protected $fillable = [
        'Staff_Name',
        'Staff_Phone',
        'Staff_Address',
        'email',
        'password',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'Department_ID');
    }
}
