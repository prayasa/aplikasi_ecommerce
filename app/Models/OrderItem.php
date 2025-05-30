<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'order_id', 'product_id', 'quantity', 'price'];

    // Relasi order item milik satu order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // Relasi order item milik satu product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}