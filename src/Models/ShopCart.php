<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCart extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function shop_cart_products()
  {
    return $this->hasMany(ShopCartProduct::class, 'shop_cart_product_id')->where('status', '==', 1);
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
