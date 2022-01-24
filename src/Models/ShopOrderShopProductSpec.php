<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopOrderShopProductSpec extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function shop_order_shop_product()
  {
    return $this->belongsTo(ShopOrderShopProduct::class, 'shop_order_shop_product_id');
  }

  public function shop_product_spec()
  {
    return $this->belongsTo(ShopProductSpec::class, 'shop_product_spec_id');
  }

  public function shop_order_shop_product_spec_settings()
  {
    return $this->belongsToMany(ShopOrderShopProductSpecSetting::class, 'shop_order_shop_product_spec_shop_product_spec_setting', 'shop_order_shop_product_spec_id', 'shop_order_shop_product_spec_setting_id');
  }

  public function shop_order_shop_product_spec_setting_items()
  {
    return $this->belongsToMany(ShopOrderShopProductSpecSettingItem::class, 'shop_order_shop_product_spec_shop_product_spec_setting_item', 'shop_order_shop_product_spec_id', 'shop_order_shop_product_spec_setting_item_id');
  }
}
