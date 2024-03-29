<?php

namespace Wasateam\Laravelapistone\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopShipTimeSetting extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function today_shop_orders()
  {
    // $target_day = Carbon::now();
    // return $this->hasMany(ShopOrder::class, 'shop_ship_time_setting_id')->where('ship_date', $target_day->format('Y-m-d'));
    $target_day = Carbon::now();
    return $this->hasMany(ShopOrder::class, 'shop_ship_time_setting_id')->whereDate('created_at', '=', $target_day->format('Y-m-d'));
  }

  protected $casts = [
    'start_time' => \Wasateam\Laravelapistone\Casts\TimeCast::class,
    'end_time'   => \Wasateam\Laravelapistone\Casts\TimeCast::class,
  ];
}
