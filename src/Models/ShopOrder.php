<?php

namespace Wasateam\Laravelapistone\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

class ShopOrder extends Model
{
  use HasFactory;
  use \Illuminate\Database\Eloquent\SoftDeletes;

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

  public function shop_campaign_shop_orders_discount_code()
  {
    return $this->hasMany(ShopCampaignShopOrder::class)->where('type', 'discount_code');
  }

  public function bonus_point_records()
  {
    return $this->hasMany(BonusPointRecord::class);
  }

  public function shop_campaigns_discount_code()
  {
    return $this->belongsToMany(ShopCampaign::class, 'shop_campaign_shop_orders', 'shop_order_id', 'shop_campaign_id')->wherePivot('type', 'discount_code');
  }

  public function getShopCampaignDiscountCodeAttribute()
  {
    if (count($this->shop_campaigns_discount_code)) {
      return $this->shop_campaigns_discount_code[0];
    } else {
      return;
    }
  }

  public function getShopCampaignDiscountCodeDeductAttribute()
  {
    return count($this->shop_campaign_shop_orders_discount_code) ? $this->shop_campaign_shop_orders_discount_code[0]->campaign_deduct : 0;
  }

  public function shop_campaigns()
  {
    return $this->belongsToMany(ShopCampaign::class, 'shop_campaign_shop_orders', 'shop_order_id', 'shop_campaign_id');
  }

  public function getDiscountCodeAttribute()
  {
    if (count($this->shop_campaigns_discount_code)) {
      return $this->shop_campaigns_discount_code[0]->discount_code;
    } else {
      return;
    }
  }

  public function getReturnCostAttribute()
  {
    $return_cost    = 0;
    $order_products = $this->shop_order_shop_products;
    foreach ($order_products as $order_product) {
      $return_count = ShopHelper::getProductReturnCount($order_product);
      $return_cost += $return_count * $order_product->cost;
    }
    return $return_cost;
  }

  public function getOrderPriceAfterReturnAttribute()
  {
    if (
      $this->status == 'return-part-complete' ||
      $this->status == 'return-all-complete'
    ) {
      return ($this->order_price - $this->return_price);
    } else {
      return;
    }
  }

  public function getOrderCostProductsAttribute()
  {
    $order_cost_products = 0;
    foreach ($this->shop_order_shop_products as $shop_order_shop_product) {
      $order_cost_products += $shop_order_shop_product->cost * $shop_order_shop_product->count;
    }
    return $order_cost_products;
  }

  public function getFreightDeductAttribute()
  {
    if ($this->freight) {
      return ShopHelper::getFreight() - $this->freight;
    } else {
      return 0;
    }
  }

  public function getOrderCostAttribute()
  {
    $return_cost = $this->return_cost ? $this->return_cost : 0;
    return $this->order_cost_products + $this->freight - $this->freight_deduct - $return_cost;
  }

  public function getPayTypeTextAttribute()
  {
    $text_arr = [
      'Credit'   => '信用卡',
      'CVS'      => 'CVS',
      'ATM'      => 'ATM',
      'BARCODE'  => '超商條碼',
      'line_pay' => 'LINE Pay',
    ];
    if ($this->pay_type && $text_arr[$this->pay_type]) {
      return $text_arr[$this->pay_type];
    } else {
      return;
    }
  }

  protected $casts = [
    'discounts'         => \Wasateam\Laravelapistone\Casts\JsonCast::class,
    'orderer_birthday'  => 'datetime',
    'receiver_birthday' => 'datetime',
    'ship_date'         => 'datetime',
    'pay_at'            => 'datetime',
    'ship_start_time'   => \Wasateam\Laravelapistone\Casts\TimeCast::class,
    'ship_end_time'     => \Wasateam\Laravelapistone\Casts\TimeCast::class,
  ];
}
