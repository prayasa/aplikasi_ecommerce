<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['category_id', 'name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}