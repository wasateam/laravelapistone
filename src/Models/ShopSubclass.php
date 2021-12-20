<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSubclass extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function shop_class()
  {
    return $this->belongsTo(ShopClass::class, 'shop_class_id');
  }

  public function shop_products()
  {
    return $this->belongsToMany(ShopProduct::class, 'shop_product_shop_subclass', 'shop_subclass_id', 'shop_product_id')->withPivot('sq')->orderBy('sq');
  }

  protected $casts = [
    'icon' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
  ];
}
