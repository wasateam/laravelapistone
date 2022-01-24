<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopOrderShopProductSpecSetting extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function shop_order_shop_product()
  {
    return $this->belongsTo(ShopOrderShopProduct::class, 'shop_order_shop_product_id');
  }

  public function shop_product_spec_setting()
  {
    return $this->belongsTo(ShopProductSpecSetting::class, 'shop_product_spec_setting');
  }
}
