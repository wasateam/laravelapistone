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

  public function shop_ship_time_setting()
  {
    return $this->belongsTo(ShopShipTimeSetting::class, 'shop_ship_time_setting_id');
  }

  public function shop_ship_area_setting()
  {
    return $this->belongsTo(ShopShipAreaSetting::class, 'shop_ship_area_setting_id');
  }

  public function user_address()
  {
    return $this->belongsTo(UserAddress::class, 'user_address_id');
  }

  public function shop_order_shop_products()
  {
    return $this->hasMany(ShopOrderShopProduct::class, 'shop_order_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function shop_return_records()
  {
    return $this->hasMany(ShopReturnRecord::class);
  }

  public function repay_shop_order()
  {
    return $this->belongsTo(ShopOrder::class, 'repay_shop_order_id');
  }

  public function shop_campaign_shop_orders()
  {
    return $this->hasMany(ShopCampaignShopOrder::class);
  }

  protected $casts = [
    'discounts'         => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'orderer_birthday'  => 'datetime',
    'receiver_birthday' => 'datetime',
    'ship_date'         => 'datetime',
  ];
}
