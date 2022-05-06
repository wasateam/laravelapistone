<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrderShopProduct extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function shop_product()
  {
    return $this->belongsTo(ShopProduct::class, 'shop_product_id');
  }

  public function shop_cart_product()
  {
    return $this->belongsTo(ShopCartProduct::class, 'shop_cart_product_id');
  }

  public function shop_order()
  {
    return $this->belongsTo(ShopOrder::class, 'shop_order_id');
  }

  public function shop_return_records()
  {
    return $this->hasMany(ShopReturnRecord::class);
  }

  public function shop_order_shop_product_spec()
  {
    return $this->hasOne(ShopOrderShopProductSpec::class, 'shop_order_shop_product_id');
  }

  public function shop_order_shop_product_spec_settings()
  {
    return $this->hasMany(ShopOrderShopProductSpecSetting::class, 'shop_order_shop_product_id');
  }

  public function shop_order_shop_product_spec_setting_items()
  {
    return $this->hasMany(ShopOrderShopProductSpecSettingItem::class, 'shop_order_shop_product_id');
  }

  public function getWeightCapacityAttribute()
  {
    if ($this->shop_product) {
      return $this->shop_product->weight_capacity_unit;
    } else {
      return;
    }
  }
}
