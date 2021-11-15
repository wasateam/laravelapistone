<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCartProduct extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function shop_product()
  {
    return $this->belongsTo(ShopProduct::class, 'shop_product_id');
  }

  public function shop_cart()
  {
    return $this->belongsTo(ShopCart::class, 'shop_cart_id');
  }
}
