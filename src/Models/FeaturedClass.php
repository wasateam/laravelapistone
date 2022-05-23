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
    return $this->belongsToMany(ShopProduct::class, 'featured_class_shop_product', 'featured_class_id', 'shop_product_id');
  }

  public function shop_products_is_active()
  {
    return $this->belongsToMany(ShopProduct::class, 'featured_class_shop_product', 'featured_class_id', 'shop_product_id')->onshelf()->nostocklast()->withPivot('sq')->orderBy('sq');
  }

  public function shop_products_is_active_order()
  {
    return $this->belongsToMany(ShopProduct::class, 'featured_class_shop_product', 'featured_class_id', 'shop_product_id')->onshelf()->withPivot('sq')->orderBy('sq');
  }

  protected $casts = [
    'icon' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
  ];
}
