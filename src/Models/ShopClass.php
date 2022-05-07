<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopClass extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function shop_subclasses()
  {
    if (config('stone.mode') == 'cms') {
      return $this->hasMany(ShopSubclass::class)->orderBy('sq');
    } else if (config('stone.mode') == 'webapi') {
      return $this->hasMany(ShopSubclass::class)->where('is_active', 1)->orderBy('sq');
    }
  }

  protected $casts = [
    'icon' => \Wasateam\Laravelapistone\Casts\UrlCast::class,
  ];
}
