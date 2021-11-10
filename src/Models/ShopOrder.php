<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
  use HasFactory;

  public function area()
  {
    return $this->belongsTo(Area::class, 'area_id');
  }

  public function area_section()
  {
    return $this->belongsTo(AreaSection::class, 'area_section_id');
  }

  public function shop_order_shop_product()
  {
    return $this->hasMany(ShopOrderShopProduct::class, 'shop_order_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
