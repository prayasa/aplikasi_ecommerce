<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';    // Primary key sesuai diagram
    public $incrementing = false;             // Karena tipe string (UUID)
    protected $keyType = 'string';

    protected $fillable = ['customer_id', 'name', 'email', 'password', 'phone', 'address'];

    // Relasi 1 customer punya banyak order
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }
}