<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductImage extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function shop_product()
  {
    return $this->belongsTo(ShopProduct::class, 'shop_product_id');
  }
}
