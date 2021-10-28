<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopShipAreaSetting extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function area()
  {
    return $this->belongsTo(Area::class);
  }

  public function area_sections()
  {
    return $this->belongsToMany(AreaSection::class, 'shop_ship_area_setting_area_section', 'shop_ship_area_setting_id', 'area_section_id');
  }

  protected $casts = [
    'ship_ways' => \Wasateam\Laravelapistone\Casts\JsonCast::class,
  ];
}
