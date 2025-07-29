<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity'];



//     public function product()
// {
//     return $this->belongsTo(\App\Models\Product::class);
// }
public function product()
{
    return $this->belongsTo(\App\Models\Product::class, 'product_id');
}

}



