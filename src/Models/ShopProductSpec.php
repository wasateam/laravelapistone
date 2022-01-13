<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProductSpec extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function shop_product()
  {
    return $this->belongsTo(ShopProduct::class, 'shop_product_id');
  }

  public function shop_product_spec_settings()
  {
    return $this->belongsToMany(ShopProductSpecSetting::class, 'shop_product_spec_shop_product_spec_setting', 'shop_product_spec_id', 'shop_product_spec_setting_id');
  }

  public function shop_product_spec_setting_items()
  {
    return $this->belongsToMany(ShopProductSpecSettingItem::class, 'shop_product_spec_shop_product_spec_setting_item', 'shop_product_spec_id', 'shop_product_spec_setting_item_id');
  }
}
