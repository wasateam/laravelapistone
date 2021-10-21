<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopClass extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function parent_shop_class()
  {
    return $this->belongsTo(ShopClass::class, 'parent_shop_class_id');
  }

  public function shop_classes()
  {
    return $this->hasMany(ShopClass::class, 'parent_shop_class_id');
  }
}
