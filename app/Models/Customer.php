<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';    
    public $incrementing = false;             
    protected $keyType = 'string';

    protected $fillable = ['customer_id', 'name', 'email', 'password', 'phone', 'address'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }
}