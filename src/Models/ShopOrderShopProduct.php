<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrderShopProduct extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function shop_order() {
    return $this->belongsTo(ShopOrder::class, 'shop_orders_id');
  }
  public function shop_product() {
    return $this->belongsTo(ShopProduct::class, 'shop_products_id');
  }
}
