<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
   use SoftDeletes;
    //
    protected $fillable = ['name', 'description', 'price','volume_point','dp_price', 'stock','image','category_id',];

    public function category()
{
    return $this->belongsTo(Category::class);
}
public function product()
{
    return $this->belongsTo(Product::class);
}

}
