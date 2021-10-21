<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedClass extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function shop_products()
  {
    return $this->belongsToMany(ShopProduct::class, 'feature_class_shop_product', 'feature_class_id', 'shop_product_id');
  }
}
