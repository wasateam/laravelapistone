<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProductSpecSetting extends Model
{
  use HasFactory;
  use SoftDeletes;

  public function shop_product()
  {
    return $this->belongsTo(ShopProduct::class, 'shop_product_id');
  }

  public function shop_product_spec_setting_items()
  {
    return $this->hasMany(ShopProductSpecSettingItem::class);
  }
}
