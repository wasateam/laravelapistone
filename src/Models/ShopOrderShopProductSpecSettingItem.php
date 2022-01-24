<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopOrderShopProductSpecSettingItem extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function shop_order_shop_product()
  {
    return $this->belongsTo(ShopOrderShopProduct::class, 'shop_order_shop_product_id');
  }

  public function shop_order_shop_product_spec_setting()
  {
    return $this->belongsTo(ShopOrderShopProductSpecSetting::class, 'shop_order_shop_product_spec_setting_id');
  }

  public function shop_product_spec_setting_item()
  {
    return $this->belongsTo(ShopProductSpecSettingItem::class, 'shop_product_spec_setting_item_id');
  }
}
