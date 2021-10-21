<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductCoverFrame extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function shop_products()
  {
    return $this->hasMany(ShopProduct::class);
  }

  protected $casts = [
    'url' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
  ];
}
