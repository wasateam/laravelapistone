<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopReturnRecord extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function shop_order()
  {
    return $this->belongsTo(ShopOrder::class, 'shop_order_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function shop_order_shop_product()
  {
    return $this->belongsTo(ShopOrderShopProduct::class, 'shop_order_shop_product_id');
  }

  public function shop_product()
  {
    return $this->belongsTo(ShopProduct::class, 'shop_product_id');
  }
}
