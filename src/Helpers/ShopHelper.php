<?php

namespace Wasateam\Laravelapistone\Helpers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Wasateam\Laravelapistone\Jobs\BonusPointFeedbackJob;
use Wasateam\Laravelapistone\Models\Area;
use Wasateam\Laravelapistone\Models\AreaSection;
use Wasateam\Laravelapistone\Models\ShopCampaign;
use Wasateam\Laravelapistone\Models\ShopCampaignShopOrder;
use Wasateam\Laravelapistone\Models\ShopCartProduct;
use Wasateam\Laravelapistone\Models\ShopFreeShipping;
use Wasateam\Laravelapistone\Models\ShopOrder;
use Wasateam\Laravelapistone\Models\ShopOrderShopProduct;
use Wasateam\Laravelapistone\Models\ShopOrderShopProductSpecSetting;
use Wasateam\Laravelapistone\Models\ShopOrderShopProductSpecSettingItem;
use Wasateam\Laravelapistone\Models\ShopProduct;
use Wasateam\Laravelapistone\Models\ShopProductSpecSetting;
use Wasateam\Laravelapistone\Models\ShopProductSpecSettingItem;
use Wasateam\Laravelapistone\Models\ShopReturnRecord;
use Wasateam\Laravelapistone\Models\ShopShipTimeSetting;
use Wasateam\Laravelapistone\Models\ThePointRecord;
use Wasateam\Laravelapistone\Models\User;
use Wasateam\Laravelapistone\Models\UserAddress;

class ShopHelper
{
  public static function returnProductChangeCount($return_record_id, $type = 'store')
  {
    //商品退訂->商品庫存、訂單商品數量重新計算
    $return_record      = ShopReturnRecord::where('id', $return_record_id)->first();
    $shop_product       = ShopProduct::where('id', $return_record->shop_product_id)->first();
    $shop_order_product = ShopOrderShopProduct::where('id', $return_record->shop_order_shop_product_id)->first();
    //訂單商品原始數量
    $shop_order_orginal_count = $shop_order_product->original_count;
    if ($type == 'store') {
      // 商品庫存
      // 沒有所屬spec->加商品庫存，有所屬spec->加spec庫存
      if ($shop_order_product->shop_order_shop_product_spec) {
        $shop_product_spec              = ShopProductSpec::find($shop_order_product->shop_order_shop_product_spec->shop_product_spec_id);
        $shop_product_spec->stock_count = $shop_product_spec->stock_count + $return_record->count;
        $shop_product_spec->save();
      } else {
        $shop_product->stock_count = $shop_product->stock_count + $return_record->count;
        $shop_product->save();
      }
      // 訂單商品數量
      $shop_order_product->count = $shop_order_product->count - $return_record->count;
      $shop_order_product->save();
    } else if ($type == 'update') {
      // 商品庫存
      // 沒有所屬spec->加商品庫存，有所屬spec->加spec庫存
      if ($shop_order_product->shop_order_shop_product_spec) {
        $shop_product_spec              = ShopProductSpec::find($shop_order_product->shop_order_shop_product_spec->shop_product_spec_id);
        $shop_product_spec->stock_count = $shop_product_spec->stock_count - $shop_order_product->count + $return_record->count;
        $shop_product_spec->save();
      } else {
        $shop_product->stock_count = $shop_product->stock_count - $shop_order_product->count + $return_record->count;
        $shop_product->save();
      }
      // 訂單商品數量
      $shop_order_product->count = $shop_order_orginal_count - $return_record->count;
      $shop_order_product->save();
    }
  }

  public static function shopOrderProductChangeCount($shop_order_product_id)
  {
    //成立訂單，更改商品庫存
    $shop_order_product = ShopOrderShopProduct::where('id', $shop_order_product_id)->first();
    $buy_count          = $shop_order_product->count;
    if (isset($shop_order_product->shop_order_shop_proudct_spec)) {
      $shop_order_shop_proudct_spec              = $shop_order_product->shop_order_shop_proudct_spec;
      $shop_order_shop_proudct_spec->stock_count = $shop_order_shop_proudct_spec->stock_count - $buy_count;
      $shop_order_shop_proudct_spec->save();
    } else {
      $shop_product = ShopProduct::where('id', $shop_order_product->shop_product_id)->first();
      //減少商品庫存
      $shop_product->stock_count = $shop_product->stock_count - $buy_count;
      $shop_product->save();
    }
  }

  //取得訂單金額、運費、商品總金額
  public static function calculateShopOrderPrice($shop_order_id, $order_type, $request = null)
  {
    //計算訂單金額
    $today      = Carbon::now();
    $today_date = $today->format('Y-m-d');

    //計算訂單金額
    $shop_order = ShopOrder::where('id', $shop_order_id)->first();
    //商品價錢總和  - 沒有優惠價就使用售價
    $shop_order_shop_products = $shop_order->shop_order_shop_products;
    $shop_product_price_arr   = $shop_order_shop_products->map(function ($item) {
      $price = 0;
      if (isset($item->shop_order_shop_product_sepc)) {
        $spec  = $item->shop_order_shop_product_sepc;
        $price = $spec->discount_price ? $spec->discount_price : $spec->price;
      } else {
        $price = $item->discount_price ? $item->discount_price : $item->price;
      }
      return $price * $item->count;
    });
    $shop_product_price_total = Self::sum_total($shop_product_price_arr);

    // $dicount_shop_product_price_total = 0;
    // discount_code
    // create discount_code shop_camapign
    if ($request && $request->has('discount_code') && $request->discount_code) {
      $today_dicount_decode_campaign = Self::getTodayDiscountCodeCampaign($request->discount_code);
      if ($today_dicount_decode_campaign) {
        Self::createShopCampaignShopOrder($shop_order, $today_dicount_decode_campaign);
        if ($shop_product_price_total >= $today_dicount_decode_campaign->full_amount) {
          if ($today_dicount_decode_campaign->discount_percent) {
            $shop_product_price_total = $shop_product_price_total * $today_dicount_decode_campaign->discount_percent;
          } else if ($today_dicount_decode_campaign->discount_amount) {
            $shop_product_price_total = $shop_product_price_total - $today_dicount_decode_campaign->discount_amount;
          }
        }
      }
    }
    //紅利點數
    //create bonus_points record
    $bonus_points = $shop_order->bonus_points_deduct ? $shop_order->bonus_points_deduct : 0;
    Self::createThePointRecord($shop_order, null, $bonus_points, 'deduct');

    //運費 default = 100
    $freight = config('stone.shop.freight_default') ? config('stone.shop.freight_default') : 100;
    if ($order_type) {
      $order_types     = config('stone.shop.order_type') ? config('stone.shop.order_type') : [];
      $curr_order_type = str_replace('-', '_', $order_type);
      $has_type        = array_key_exists($curr_order_type, $order_types);
      if ($has_type) {
        $type = $order_types[$curr_order_type];
        // type has freight_default
        if ($type['freight_default']) {
          $freight = $type['freight_default'];
        }
        // type has has_shop_free_shipping
        if ($type['has_shop_free_shipping']) {
          $free_freight_price = ShopFreeShipping::where('end_date', '>=', $today_date)->where('start_date', '<=', $today_date)->first();
          if ($free_freight_price) {
            if ($shop_product_price_total >= $free_freight_price->price) {
              $freight = 0;
            }
          }
        }
        //type has freight_sepe
        if ($type['freight_separate']) {
          $all_product_freight_arr = $shop_order_shop_products->map(function ($item) {
            $freight = 0;
            if (isset($item->shop_order_shop_product_spec)) {
              $freight = $item->shop_order_shop_prdouct_spec->freight ? $item->shop_order_shop_prdouct_spec->freight : 0;
            } else {
              $freight = $item->freight ? $item->freight : 0;
            }
            return $freight * $item->count;
          });
          $freight = Self::sum_total($all_product_freight_arr);
        }
      }
    }

    //訂單金額 產品總和+運費
    $order_price = $shop_product_price_total + $freight - $bonus_points;

    return [
      "products_price" => $shop_product_price_total,
      "freight"        => $freight,
      "order_price"    => $order_price,
    ];

  }

  public static function changeShopOrderPrice($shop_order_id, $order_type = null, $request = null)
  {
    //更新訂單價格
    $shop_order = ShopOrder::where('id', $shop_order_id)->first();
    //取得訂單類型
    $_order_type                = $order_type ? $order_type : $shop_order->order_type;
    $price_array                = Self::calculateShopOrderPrice($shop_order_id, $_order_type, $request);
    $shop_order->products_price = $price_array['products_price'];
    $shop_order->freight        = $price_array['freight'];
    $shop_order->order_price    = $price_array['order_price'];
    $shop_order->save();
  }

  public static function sum_total($price_array)
  {
    $total = 0;
    foreach ($price_array as $price) {
      $total = $total + $price;
    }
    return $total;
  }

  public static function sameFreeDuration($start_date, $end_date, $id = null)
  {
    //是否有重複區間的免運門檻
    $snap = null;
    if (isset($start_date) && isset($end_date)) {
      $snap = ShopFreeShipping::where(function ($query) use ($start_date, $end_date) {
        $query->where(function ($query) use ($start_date, $end_date) {
          $query->where('start_date', '<=', Carbon::parse($start_date))->where('end_date', '>=', Carbon::parse($end_date));
        })->orWhere(function ($query) use ($start_date, $end_date) {
          $query->where('start_date', '<=', Carbon::parse($start_date))->where('end_date', '>=', Carbon::parse($start_date));
        })->orWhere(function ($query) use ($start_date, $end_date) {
          $query->where('start_date', '>=', Carbon::parse($start_date))->where('end_date', '<=', Carbon::parse($end_date));
        })->orWhere(function ($query) use ($start_date, $end_date) {
          $query->where('start_date', '<=', Carbon::parse($end_date))->where('end_date', '>=', Carbon::parse($end_date));
        });
      });
      if ($id) {
        $snap = $snap->where('id', '!=', $id);
      }
      $snap = $snap->first();
    }
    if (isset($snap)) {
      return true;
    } else {
      return false;
    }
  }

  # 生成商品編號
  public static function newShopOrderNo()
  {
    $time = Carbon::now()->timestamp;
    $str  = Str::random(8);
    return "{$time}{$str}";
  }

  public static function getOrderAmount($shop_cart_products)
  {
    if (!$shop_cart_products) {
      return 0;
    }
    $order_amount = 0;
    foreach ($shop_cart_products as $shop_cart_product) {
      $count = $shop_cart_product['count'];
      $price = 0;
      if (isset($shop_cart_product->shop_product_spec)) {
        $price = $shop_cart_product->shop_product_spec['discount_price'] ? $shop_cart_product->shop_product_spec['discount_price'] : $shop_cart_product->shop_product_spec['price'];
      } else {
        $price = $shop_cart_product['discount_price'] ? $shop_cart_product['discount_price'] : $shop_cart_product['price'];
      }
      $order_amount += $count * $price;
    }
    return $order_amount;
  }

  public static function getOrderProductAmountPrice($order_product)
  {
    $count = $order_product['count'];
    $price = 0;
    if (isset($order_product->shop_product_spec)) {
      $price = $order_product->shop_product_spec['discount_price'] ? $order_product->shop_product_spec['discount_price'] : $order_product->shop_product_spec['price'];
    } else {
      $price = $order_product['discount_price'] ? $order_product['discount_price'] : $order_product['price'];
    }
    return $count * $price;
  }

  public static function getOrderProductNames($shop_cart_products)
  {
    $product_names = "";
    foreach ($shop_cart_products as $key => $shop_cart_product) {
      if ($key > 0) {
        $product_names .= " ,";
      }
      $product_names .= $shop_cart_product['name'];
    }
    return $product_names;
  }

  # 從購物車商品取得訂單類別、用第一個商品做抓取
  public static function getOrderType($shop_cart_products)
  {
    if (!count($shop_cart_products)) {
      return null;
    }
    $model = ShopCartProduct::find($shop_cart_products[0]['id']);
    error_log($model->shop_product->order_type);
    return $model->shop_product->order_type;
  }

  //pdf 整理訂單裡的商品格式
  public static function fetchShopOrderProduct($order_products)
  {
    $datas = [];
    foreach ($order_products as $order_product) {
      $datas[] = [
        'id'              => $order_product->id,
        'name'            => $order_product->name,
        'spec'            => $order_product->spec,
        'weight_capacity' => $order_product->weight_capacity,
        'storage_space'   => $order_product->shop_product->storage_space,
        'count'           => $order_product->count,
      ];
    }
    return $datas;
  }

  public static function createUserAddress($user, $area_id, $area_section_id, $address, $type = 'delevery')
  {
    $user_address = UserAddress::
      where('user_id', $user->id)
      ->where('area_id', $area_id)
      ->where('area_section_id', $area_section_id)
      ->where('address', $address)
      ->where('type', $type)
      ->first();
    if (!$user_address) {
      $create_check       = true;
      $user_address_count = UserAddress::
        where('user_id', $user->id)
        ->where('area_id', $area_id)
        ->where('area_section_id', $area_section_id)
        ->where('address', $address)
        ->where('type', $type)
        ->count();
      if (config('user.address.' . $type)) {
        if (config('user.address.' . $type . '.limit')) {
          if ($user_address_count >= config('user.address.' . $type . '.limit')) {
            $create_check = false;
          }
        }
        if ($create_check) {
          $user_address                  = new UserAddress();
          $user_address->user_id         = $user->id;
          $user_address->area_id         = $area_id;
          $user_address->area_section_id = $area_section_id;
          $user_address->address         = $address;
          $user_address->type            = $type;
          $user_address->save();
        }
      }

    }
    return $user_address;
  }

  public static function getAddressWithoutArea($address, $area_id, $area_section_id)
  {
    $area         = Area::find($area_id);
    $area_section = AreaSection::find($area_section_id);
    $_address     = $address;
    $_address     = Str::replaceFirst($area->name, '', $_address);
    $_address     = Str::replaceFirst($area_section->name, '', $_address);
    return $_address;
  }

  public static function updateUserInfoFromShopOrderRequest($user, $request)
  {
    # User Address
    if (config('stone.user.address')) {
      if (config('stone.user.address.delivery')) {
        Self::createUserAddress($user, $request->area, $request->area_section, Self::getAddressWithoutArea($request->receive_address, $request->area, $request->area_section), 'delivery');
      }
    }

    # User
    if ($request->has('orderer_tel')) {
      $user->tel = $request->orderer_tel;
      $user->save();
    }
    if ($request->has('orderer_birthday')) {
      $user->birthday = $request->orderer_birthday;
      $user->save();
    }

    # carrier
    if (config('stone.invoice')) {
      if ($request->has('invoice_type')) {
        $invoice_type = $request->invoice_type;
        if ($invoice_type == 'persion') {
          $invoice_carrier_type   = $request->invoice_carrier_type;
          $invoice_carrier_number = $request->invoice_carrier_number;
          if ($invoice_carrier_type == 'mobile') {
            $user->carrier_phone = $request->invoice_carrier_number;
          } else if ($invoice_carrier_type == 'certificate') {
            $user->carrier_certificate = $request->invoice_carrier_number;
          } else if ($invoice_carrier_type == 'email') {
            $user->carrier_email = $request->invoice_carrier_number;
          }
          $user->save();
        }
      }
    }
  }

  public static function getOrderCost($order_products)
  {
    //扣掉退訂後的訂單成本
    $all_products_cost = $order_products->map(function ($product) {
      return $product->cost * $product->count;
    });
    $total_cost = Self::sum_total($all_products_cost);

    return $total_cost;
  }

  public static function getOrderOriginalCost($order_products)
  {
    //加上退訂後的訂單成本
    $all_products_cost = $order_products->map(function ($product) {
      return $product->cost * $product->original_count;
    });
    $total_cost = Self::sum_total($all_products_cost);

    return $total_cost;
  }

  public static function getProductReturnCount($order_product)
  {
    if (!$order_product->shop_return_records) {
      return 0;
    }
    $count_arr = $order_product->shop_return_records->map(function ($return_record) {
      return $return_record->count;
    });
    $count = Self::sum_total($count_arr);

    return $count;
  }

  public static function destroyReturnRecord($return_record)
  {
    $shop_product       = ShopProduct::where('id', $return_record->shop_product_id)->first();
    $shop_order_product = ShopOrderShopProduct::where('id', $return_record->shop_order_shop_product_id)->first();
    // 商品庫存
    $shop_product->stock_count = $shop_product->stock_count - $shop_order_product->original_count + $shop_order_product->count;
    $shop_product->save();
    // 訂單商品數量
    $shop_order_product->count = $shop_order_product->original_count;
    $shop_order_product->save();
  }

  public static function sameCampaignDuration($start_date, $end_date, $id = null, $type)
  {
    //是否有重複區間的促銷活動
    $snap = null;
    if (isset($start_date) && isset($end_date)) {
      $snap = Self::filterDuration('Wasateam\Laravelapistone\Models\ShopCampaign', $start_date, $end_date);
    }
    if ($id) {
      $snap = $snap->where('id', '!=', $id);
    }
    if (isset($type)) {
      $snap = $snap->where('type', $type);
    }
    $snap = $snap->first();
    if (isset($snap)) {
      return true;
    } else {
      return false;
    }
  }

  public static function filterDuration($model, $start_date, $end_date)
  {
    $snap = $model;
    if (isset($start_date) && isset($end_date)) {
      $snap = $snap::where(function ($query) use ($start_date, $end_date) {
        $query->where(function ($query) use ($start_date, $end_date) {
          $query->where('start_date', '<=', Carbon::parse($start_date))->where('end_date', '>=', Carbon::parse($end_date));
        })->orWhere(function ($query) use ($start_date, $end_date) {
          $query->where('start_date', '<=', Carbon::parse($start_date))->where('end_date', '>=', Carbon::parse($start_date));
        })->orWhere(function ($query) use ($start_date, $end_date) {
          $query->where('start_date', '>=', Carbon::parse($start_date))->where('end_date', '<=', Carbon::parse($end_date));
        })->orWhere(function ($query) use ($start_date, $end_date) {
          $query->where('start_date', '<=', Carbon::parse($end_date))->where('end_date', '>=', Carbon::parse($end_date));
        });
      });
    }
    return $snap;
  }

  public static function createBonusPointFeedbackJob($shop_order_id)
  {
    $stone_feedback_after_invoice_days = config('stone.shop_campaign.items.bonus_point_feedback.feedback_after_invoice_days');
    $feedback_after_invoice_days       = $stone_feedback_after_invoice_days ? $stone_feedback_after_invoice_days : 3;
    BonusPointFeedbackJob::dispatch($shop_order_id);
      // ->delay(Carbon::now()->addDays($feedback_after_invoice_days));
  }

  public static function samePageCoverDuration($start_date, $end_date, $id = null, $page_settings = null)
  {
    //是否有重複區間的頁面彈跳穿
    $snap = null;
    if (isset($start_date) && isset($end_date)) {
      $snap = Self::filterDuration('Wasateam\Laravelapistone\Models\PageCover', $start_date, $end_date);
    }
    if ($id) {
      $snap = $snap->where('id', '!=', $id);
    }
    if (isset($page_settings)) {
      $snap = $snap->whereHas('page_settings', function ($query) use ($page_settings) {
        return $query->whereIn('id', $page_settings);
      });
    }
    $snap = $snap->first();
    if (isset($snap)) {
      return true;
    } else {
      return false;
    }
  }

  public static function checkBonusPointEnough($request)
  {

    if (!$request->has('bonus_points_deduct')) {
      return;
    }

    $user                = User::find($request->user);
    $bonus_points_deduct = $request->bonus_points_deduct;
    if ($bonus_points_deduct > $user->bonus_points) {
      return false;
      throw new \Wasateam\Laravelapistone\Exceptions\OutOfException('bonus_points');
    }
  }

  public static function getTodayDiscountCodeCampaign($discount_code)
  {
    //取得折扣碼活動
    $today_date                   = Carbon::now()->format('Y-m-d');
    $today_discount_code_campaign = ShopCampaign::whereDate('start_date', '<=', $today_date)->whereDate('end_date', '>=', $today_date)->where('type', 'discount_code')->where('is_active', 1)->where('discount_code', $discount_code)->first();

    if (!$today_discount_code_campaign) {
      return false;
    }
    // shop_campaign limit is enought or not
    if ($today_discount_code_campaign->limit) {
      $shop_campaign_shop_product_count = $today_discount_code_campaign->shop_campaign_shop_products->count;
      if ($shop_campaign_shop_product_count >= $today_discount_code_campaign->limit) {
        return false;
      }
    }

    return $today_discount_code_campaign;
  }

  public static function createShopCampaignShopOrder($shop_order, $shop_campaign)
  {
    //建立訂單促銷活動紀錄
    $shop_campaign_shop_order                   = new ShopCampaignShopOrder;
    $shop_campaign_shop_order->shop_camapign_id = $shop_campaign->id;
    $shop_campaign_shop_order->shop_order_id    = $shop_order->id;
    $shop_campaign_shop_order->user_id          = $shop_order->user->id;
    $shop_campaign_shop_order->type             = $shop_campaign->type;
    $shop_campaign_shop_order->name             = $shop_campaign->name;
    $shop_campaign_shop_order->condition        = $shop_campaign->condition;
    $shop_campaign_shop_order->full_amount      = $shop_campaign->full_amount;
    $shop_campaign_shop_order->discount_percent = $shop_campaign->discount_percent;
    $shop_campaign_shop_order->discount_amount  = $shop_campaign->discount_amount;
    $shop_campaign_shop_order->feedback_rate    = $shop_campaign->feedback_rate;

    $shop_campaign_shop_order->save();
  }

  public static function shopProductCreateSpec($shop_product_spec_settings, $shop_product_specs, $shop_product_id)
  {
    //商品建立規格
    // create/update shop_product_spec,shop_product_spec_setting,shop_product_spec_setting_item when shop_product created/updated
    $shop_product_spec_setting_ids      = []; //[1,2,3]
    $shop_product_spec_setting_item_ids = []; //[[1,2],[3,4]]
    foreach ($shop_product_spec_settings as $shop_product_spec_setting) {

      //shop_product_spec_setting
      $new_shop_product_spec_setting = null;
      if (!isset($shop_product_spec_setting['id'])) {
        $new_shop_product_spec_setting = new ShopProductSpecSetting;
      } else {
        $new_shop_product_spec_setting = ShopProductSpecSetting::find($shop_product_spec_setting['id']);
      }
      $new_shop_product_spec_setting->name            = $shop_product_spec_setting['name'];
      $new_shop_product_spec_setting->sq              = $shop_product_spec_setting['sq'];
      $new_shop_product_spec_setting->shop_product_id = $shop_product_id;
      $new_shop_product_spec_setting->save();

      //shop_product_spec_setting_item
      $item_ids = [];
      foreach ($shop_product_spec_setting['shop_product_spec_setting_items'] as $shop_product_spec_setting_item) {
        $new_shop_product_spec_setting_item = null;
        if (!isset($shop_product_spec_setting_item['id'])) {
          $new_shop_product_spec_setting_item = new ShopProductSpecSettingItem;
        } else {
          $new_shop_product_spec_setting_item = ShopProductSpecSettingItem::find($shop_product_spec_setting_item['id']);
        }
        $new_shop_product_spec_setting_item->name                         = $shop_product_spec_setting_item['name'];
        $new_shop_product_spec_setting_item->sq                           = $shop_product_spec_setting_item['sq'];
        $new_shop_product_spec_setting_item->shop_product_id              = $shop_product_id;
        $new_shop_product_spec_setting_item->shop_product_spec_setting_id = $new_shop_product_spec_setting->id;
        $new_shop_product_spec_setting_item->save();
        $item_ids[] = $new_shop_product_spec_setting_item->id;
      }
      $shop_product_spec_setting_item_ids[] = $item_ids;
      $shop_product_spec_setting_ids[]      = $new_shop_product_spec_setting->id;
    }

    $id_combination = Self::combinateArray($shop_product_spec_setting_item_ids);

    //shop_product_spec
    foreach ($shop_product_specs as $shop_product_spec_key => $shop_product_spec) {
      $new_shop_product_spec = $shop_product_spec;
      //shop_product_spec_settings
      $shop_product_spec_settings = $shop_product_spec_setting_ids;
      //shop_product_spec_setting_items
      $shop_product_spec_setting_items = $id_combination[$shop_product_spec_key];

      $new_shop_product_spec['shop_product']                    = $shop_product_id;
      $new_shop_product_spec['shop_product_spec_settings']      = $shop_product_spec_settings;
      $new_shop_product_spec['shop_product_spec_setting_items'] = $shop_product_spec_setting_items;
      // create/update shop_product_spec
      if (isset($shop_product_spec['id'])) {
        ModelHelper::ws_UpdateHandler(new \Wasateam\Laravelapistone\Controllers\ShopProductSpecController, new Request($new_shop_product_spec), $shop_product_spec['id']);
      } else {
        ModelHelper::ws_StoreHandler(new \Wasateam\Laravelapistone\Controllers\ShopProductSpecController, new Request($new_shop_product_spec));
      }
    }

  }

  public static function shopProductDeleteSpec($shop_product_spec_settings, $shop_product_specs, $shop_product_id)
  {
    // delete shop_product_spec,shop_product_spec_setting,shop_product_spec_setting_item when shop_product updated
    $shop_product                        = ShopProduct::find($shop_product_id);
    $shop_product_spec_ids               = Self::getItemsIdArray($shop_product_specs);
    $shop_product_spec_setting_ids       = [];
    $shop_product_spec_settting_item_ids = [];
    foreach ($shop_product_spec_settings as $shop_product_spec_setting) {
      if (isset($shop_product_spec_setting['id'])) {
        $shop_product_spec_setting_ids[] = $shop_product_spec_setting['id'];
      }
      $shop_product_spec_settting_item_ids = Self::getItemsIdArray($shop_product_spec_setting['shop_product_spec_setting_items'], $shop_product_spec_settting_item_ids);
    }
    //delete
    Self::deleteItemByIdArray($shop_product->shop_product_specs, $shop_product_spec_ids);
    Self::deleteItemByIdArray($shop_product->shop_product_spec_settings, $shop_product_spec_setting_ids);
    Self::deleteItemByIdArray($shop_product->shop_product_spec_setting_items, $shop_product_spec_settting_item_ids);
  }

  public static function getItemsIdArray($items, $id_array = [])
  {
    $ids = $id_array;
    foreach ($items as $item) {
      if (isset($item['id'])) {
        $ids[] = $item['id'];
      }
    }
    return $ids;
  }

  public static function deleteItemByIdArray($items, $id_array)
  {
    foreach ($items as $item) {
      if (!in_array($item->id, $id_array)) {
        $item->delete();
      }
    }
  }

  public static function checkProductStockEnough($shop_cart_product)
  {
    $shop_stock_count = 0;
    $buy_count        = $shop_cart_product->count ? $shop_cart_product->count : 0;
    if (isset($shop_cart_product->shop_product_spec)) {
      $shop_stock_count = $shop_cart_product->shop_product_spec->stock_count;
    } else {
      $shop_stock_count = $shop_cart_product->shop_product->stock_count;
    }
    if ($buy_count > $shop_stock_count) {
      throw new \Wasateam\Laravelapistone\Exceptions\OutOfException('shop product stock', 'shop_product', $shop_cart_product->shop_product->id);
    }
  }

  public static function createShopOrderShopProduct($shop_cart_product, $shop_order_id)
  {
    # 建立訂單時建立訂單商品(用購物車商品判斷)
    $new_order_product                       = new ShopOrderShopProduct;
    $shop_product                            = $shop_cart_product->shop_product;
    $new_order_product->name                 = $shop_product->name;
    $new_order_product->subtitle             = $shop_product->subtitle;
    $new_order_product->count                = $shop_cart_product->count;
    $new_order_product->original_count       = $shop_cart_product->count;
    $new_order_product->price                = $shop_product->price;
    $new_order_product->discount_price       = $shop_product->discount_price;
    $new_order_product->spec                 = $shop_product->spec;
    $new_order_product->weight_capacity      = $shop_product->weight_capacity;
    $new_order_product->cover_image          = $shop_product->cover_image;
    $new_order_product->order_type           = $shop_product->order_type;
    $new_order_product->freight              = $shop_product->freight;
    $new_order_product->shop_product_id      = $shop_product->id;
    $new_order_product->cost                 = $shop_product->cost;
    $new_order_product->shop_cart_product_id = $shop_cart_product['id'];
    $new_order_product->shop_order_id        = $shop_order_id;
    $new_order_product->save();
    if (isset($shop_cart_product->shop_product_spec)) {
      # create shop_order_shop_prdouct_spec
      $shop_product_spec = $shop_cart_product->shop_product_spec;
      Self::createShopOrderShopProductSpec($shop_product_spec, $new_order_product->id);
    }
    return $new_order_product;
  }

  public static function createShopOrderShopProductSpec($shop_product_spec, $shop_order_shop_product_id)
  {
    # 建立訂單商品規格(用商品格式判斷)
    # create shop_order_shop_product_spec_setting
    $shop_product_settings = $shop_product_spec->shop_product_spec_settings;
    $new_settings          = [];
    foreach ($shop_product_settings as $shop_product_settings) {
      $new_shop_order_product_setting                               = new ShopOrderShopProductSpecSetting;
      $new_shop_order_product_setting->name                         = $shop_product_settings->name;
      $new_shop_order_product_setting->sq                           = $shop_product_settings->sq;
      $new_shop_order_product_setting->shop_order_shop_product_id   = $shop_order_shop_product_id;
      $new_shop_order_product_setting->shop_product_spec_setting_id = $shop_product_settings->id;
      $new_shop_order_product_setting->save();
      $new_settings[] = $new_shop_order_product_setting;
    }
    # create shop_order_shop_product_spec_setting_item
    $shop_product_setting_items = $shop_product_spec->shop_product_spec_setting_items;
    foreach ($shop_product_setting_items as $shop_product_setting_item) {
      $new_shop_order_product_setting_item                                    = new ShopOrderShopProductSpecSettingItem;
      $new_shop_order_product_setting_item->name                              = $shop_product_setting_item->name;
      $new_shop_order_product_setting_item->sq                                = $shop_product_setting_item->sq;
      $new_shop_order_product_setting_item->shop_order_shop_product_id        = $shop_order_shop_product_id;
      $new_shop_order_product_setting_item->shop_product_spec_setting_item_id = $shop_product_setting_item->id;
      //shop_order_shop_product_spec_setting
      $shop_order_shop_product_spec_setting                                         = Self::findItemsInArray($new_settings, 'id', $shop_product_setting_item->shop_product_spec_setting_id);
      $new_shop_order_product_setting_item->shop_order_shop_prdouct_spec_setting_id = $shop_order_shop_product_spec_setting[0]->id;
      $new_shop_order_product_setting->save();
    }
  }

  public static function findItemsInArray($array, $key, $value)
  {
    $results = array();

    if (is_array($array)) {
      if (isset($array[$key]) && $array[$key] == $value) {
        $results[] = $array;
      }

      foreach ($array as $subarray) {
        $results = array_merge($results, search($subarray, $key, $value));
      }
    }

    return $results;
  }
  public static function getShopProductPriceRange($shop_product)
  {
    $price = null;
    if (isset($shop_product->shop_product_specs) && $shop_product->shop_product_specs->count()) {
      $all_price = [];
      foreach ($shop_product->shop_product_specs as $shop_product_spec) {
        $all_price[] = $shop_product_spec->discount_price ? $shop_product_spec->discount_price : $shop_product_spec->price;
      }
      if (count($all_price) > 1) {
        sort($all_price);
        $price = "{$all_price[0]}-{$all_price[$shop_product->shop_product_specs->count() - 1]}";
      } else {
        $price = $all_price[0];
      }
    } else {
      $price = $shop_product->price;
    }

    return $price;
  }

  public static function getShopProductAllStockCount($shop_product)
  {
    $stock_count = 0;
    if (isset($shop_product->shop_product_specs) && $shop_product->shop_product_specs->count()) {
      foreach ($shop_product->shop_product_specs as $shop_product_spec) {
        $stock_count += $shop_product_spec->stock_count;
      }
    } else {
      $stock_count = $shop_product->stock_count;
    }

    return $stock_count;
  }

  public static function checkShopCartProductsExist($request)
  {
    if (!$request->has('shop_cart_products') || !is_array($request->shop_cart_products)) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('shop_cart_products');
    }
  }

  public static function shopShipTimeLimitCheck($request)
  {
    if (config('stone.shop.ship_time')) {
      if (!$request->has('shop_ship_time_setting')) {
        throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('shop_ship_time_setting');
      }
      $shop_ship_time_setting = ShopShipTimeSetting::where('id', $request->shop_ship_time_setting)->first();
      if (config('stone.shop.ship_time.daily_count_limit')) {
        if ($shop_ship_time_setting->max_count <= count($shop_ship_time_setting->today_shop_orders)) {
          throw new \Wasateam\Laravelapistone\Exceptions\OutOfException('shop_ship_time_setting count', 'shop_ship_time_setting', $shop_ship_time_setting->id);
        }
      }
    }
  }

  public static function getShopCartOrderType($shop_cart_products = [])
  {
    $shop_cart_product = $shop_cart_products[0];
    $cart_product      = ShopCartProduct::where('id', $shop_cart_product['id'])->where('status', 1)->where('count', ">", 0)->first();
    if (!$cart_product) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_cart_product', $shop_cart_product['id']);
    }
    return $cart_product->shop_product->order_type;
  }

  public static function getMyCartProducts($request, $order_type)
  {
    $my_cart_products  = $request->shop_cart_products;
    $_my_cart_products = [];
    foreach ($my_cart_products as $my_cart_product) {
      $cart_product = ShopCartProduct::where('id', $my_cart_product['id'])->where('status', 1)->where('count', ">", 0)->first();
      if (!$cart_product) {
        throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_cart_product', $my_cart_product['id']);
      }
      if ($cart_product->user_id != Auth::user()->id) {
        throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_cart_product', $cart_product->id);
      }
      self::checkProductStockEnough($cart_product);
      if ($order_type && $cart_product->shop_product->order_type != $order_type) {
        throw new \Wasateam\Laravelapistone\Exceptions\FieldNotMatchException('order_type', $order_type);
      }
      $order_type          = $cart_product->shop_product->order_type;
      $_my_cart_products[] = $cart_product;
    }
    return $_my_cart_products;
  }

  public static function checkDiscountCode($request)
  {
    if ($request->has('discount_code') && $request->discount_code) {
      $today_dicount_decode_campaign = self::getTodayDiscountCodeCampaign($request->discount_code);
      if (!$today_dicount_decode_campaign) {
        throw new \Wasateam\Laravelapistone\Exceptions\InvalidException('discount_code');
      }
    }
  }

  // public static function createShopInvoice($shop_order)
  // {
  //   // @Q@ 待調整完成
  //   if (config('stone.invoice')) {
  //     if (config('stone.invoice.service') == 'ecpay') {
  //       try {
  //         $invoice_type   = $shop_order->invoice_type;
  //         $customer_email = $shop_order->orderer_email;
  //         $customer_tel   = $shop_order->orderer_tel;
  //         $customer_addr  = $shop_order->receive_address;
  //         $order_amount   = ShopHelper::getOrderAmount($_my_cart_products);
  //         $items          = EcpayHelper::getInvoiceItemsFromShopCartProducts($_my_cart_products);
  //         $customer_id    = Auth::user()->id;
  //         $post_data      = [
  //           'Items'         => $items,
  //           'SalesAmount'   => $order_amount,
  //           'TaxType'       => 1,
  //           'CustomerEmail' => $customer_email,
  //           'CustomerAddr'  => $customer_addr,
  //           'CustomerPhone' => $customer_tel,
  //           'CustomerID'    => $customer_id,
  //         ];
  //         if ($invoice_type == 'personal') {
  //           $invoice_carrier_type      = $shop_order->invoice_carrier_type;
  //           $invoice_carrier_number    = $shop_order->invoice_carrier_number;
  //           $post_data['Print']        = 0;
  //           $post_data['CustomerName'] = $shop_order->orderer;
  //           if ($invoice_carrier_type == 'mobile') {
  //             $post_data['CarrierType'] = 3;
  //             $post_data['CarrierNum']  = $invoice_carrier_number;
  //           } else if ($invoice_carrier_type == 'certificate') {
  //             $post_data['CarrierType'] = 2;
  //             $post_data['CarrierNum']  = $invoice_carrier_number;
  //           } else if ($invoice_carrier_type == 'email') {
  //             $post_data['CarrierType']   = 1;
  //             $post_data['CarrierNum']    = '';
  //             $post_data['CustomerEmail'] = $invoice_carrier_number;
  //           }
  //         } else if ($invoice_type == 'triple') {
  //           $invoice_title                   = $shop_order->invoice_title;
  //           $invoice_uniform_number          = $shop_order->invoice_uniform_number;
  //           $post_data['CarrierType']        = '';
  //           $post_data['Print']              = 1;
  //           $post_data['CustomerName']       = $invoice_title;
  //           $post_data['CustomerIdentifier'] = $invoice_uniform_number;
  //         }
  //         $post_data      = EcpayHelper::getInvoicePostData($post_data);
  //         $invoice_number = EcpayHelper::createInvoice($post_data);
  //         $invoice_status = 'done';
  //       } catch (\Throwable $th) {
  //         $invoice_status = 'fail';
  //       }
  //     }
  //   }
  // }
  public static function checkSettingItemsMatchSpecs($settings, $specs)
  {
    // check combination of all setting items count match specs count or not;
    $setting_items_count_arr = [];
    foreach ($settings as $setting) {
      $setting_items_count_arr[] = count($setting['shop_product_spec_setting_items']);
    }
    $setting_items_total = Self::multiplyArray($setting_items_count_arr);
    if ($setting_items_total == count($specs)) {
      return true;
    } else {
      return false;
    }
  }

  public static function combinateArray($array)
  {
    // combination array element
    // array example: [[1,2],[4,5],[6,7]]
    if (count($array) === 0 || !isset($array)) {
      return [];
    }
    $current = $array[0]; //[1,2]
    for ($i = 1; $i < count($array); $i++) {
      $result = [];
      for ($c = 0; $c < count($current); $c++) {
        for ($r = 0; $r < count($array[$i]); $r++) {
          $result[] = [$current[$c], $array[$i][$r]];
        }
      }
      $current = $result;
    }
    return $current;
  }

  public static function multiplyArray($array)
  {
    //multiply items in array
    //$array = [1,2,3]
    $result = 0;
    if (!isset($array) || !count($array)) {
      return $result;
    }
    $result = array_product($array);
    return $result;
  }

  public static function getShopReturnRecordPrice($count, $shop_order_shop_product)
  {
    $price = 0;
    if (isset($shop_order_shop_product->shop_order_shop_product_spec)) {
      $spec  = $shop_order_shop_product->shop_order_shop_product_spec;
      $price = $spec->discount_price ? $spec->discount_price : $spec->price;
    } else {
      $price = $shop_order_shop_product->discount_price ? $shop_order_shop_product->dicount_price : $shop_order_shop_product->price;
    }
    return $price * $count;
  }

  public static function createShopReturnRecord($shop_order, $shop_order_shop_product, $remark = null, $return_reason = null, $type = null)
  {
    $shop_return_record                             = new ShopReturnRecord;
    $shop_return_record->user_id                    = $shop_order->user_id;
    $shop_return_record->shop_order_id              = $shop_order->id;
    $shop_return_record->shop_product_id            = $shop_order_shop_product->shop_product_id;
    $shop_return_record->shop_order_shop_product_id = $shop_order_shop_product->id;
    $shop_return_record->count                      = $shop_order_shop_product->count;
    $shop_return_record->price                      = $shop_order_shop_product->dicount_price ? $shop_order_shop_product->dicount_price : $shop_order_shop_product->price;
    $shop_return_record->remark                     = $remark;
    $shop_return_record->return_reason              = $return_reason;
    $shop_return_record->type                       = $type;
    $shop_return_record->save();
    return $shop_return_record;
  }

  public static function createThePointRecord($shop_order, $shop_campaign_id = null, $point_count, $type)
  {
    //create the_point_record when get/deduct bonus_points
    $the_point_record                   = new ThePointRecord;
    $the_point_record->user_id          = $shop_order->user_id;
    $the_point_record->shop_order_id    = $shop_order->id;
    $the_point_record->shop_campaign_id = $shop_campaign_id;
    $the_point_record->type             = $type;
    $the_point_record->source           = 'new_shop_order';
    $the_point_record->count            = $point_count;
    $the_point_record->save();
  }

  public static function createInvoice($shop_order)
  {
    if (config('stone.invoice')) {
      if (config('stone.invoice.service') == 'ecpay') {
        // try {
        $invoice_type       = $shop_order->invoice_type;
        $SalesAmount        = ShopHelper::getOrderAmount($shop_order->shop_order_shop_products);
        $Items              = EcpayHelper::getInvoiceItemsFromShopCartProducts($shop_order->shop_order_shop_products);
        $CustomerID         = $shop_order->user_id;
        $CustomerIdentifier = '';
        $CustomerName       = '';
        $CustomerAddr       = $shop_order->receive_address;
        $CustomerPhone      = $shop_order->orderer_tel;
        $CustomerEmail      = $shop_order->orderer_email;
        $Print              = '0';
        $Donation           = '0';
        $CarrierType        = '';
        $CarrierNum         = '';
        $TaxType            = '1';
        if ($invoice_type == 'personal') {
          $invoice_carrier_type   = $shop_order->invoice_carrier_type;
          $invoice_carrier_number = $shop_order->invoice_carrier_number;
          $Print                  = '0';
          $CustomerName           = $shop_order->orderer;
          if ($invoice_carrier_type == 'mobile') {
            $CarrierType = '3';
            $CarrierNum  = $invoice_carrier_number;
          } else if ($invoice_carrier_type == 'certificate') {
            $CarrierType = '2';
            $CarrierNum  = $invoice_carrier_number;
          } else if ($invoice_carrier_type == 'email') {
            $CarrierType   = '1';
            $CarrierNum    = '';
            $CustomerEmail = $invoice_carrier_number;
          }
        } else if ($invoice_type == 'triple') {
          $invoice_title          = $shop_order->invoice_title;
          $invoice_uniform_number = $shop_order->invoice_uniform_number;
          $CarrierType            = '';
          $Print                  = '1';
          $CustomerName           = $invoice_title;
          $CustomerIdentifier     = $invoice_uniform_number;
        }
        if (config('stone.invoice.delay')) {
          error_log('delay');
          $DelayDay  = config('stone.invoice.delay');
          $post_data = EcpayHelper::getDelayInvoicePostData(
            $CustomerID,
            $CustomerIdentifier,
            $CustomerName,
            $CustomerAddr,
            $CustomerPhone,
            $CustomerEmail,
            $Print,
            $Donation,
            $CarrierType,
            $CarrierNum,
            $TaxType,
            $SalesAmount,
            $Items,
            $DelayDay,
          );
          $invoice_res = EcpayHelper::createDelayInvoice($post_data);
        } else {
          error_log('immediate');
          $post_data = EcpayHelper::getInvoicePostData(
            $CustomerID,
            $CustomerIdentifier,
            $CustomerName,
            $CustomerAddr,
            $CustomerPhone,
            $CustomerEmail,
            $Print,
            $Donation,
            $CarrierType,
            $CarrierNum,
            $TaxType,
            $SalesAmount,
            $Items
          );
          $invoice_res = EcpayHelper::createInvoice($post_data);
        }
        $shop_order->invoice_status = 'done';
        $shop_order->invoice_number = $invoice_res->InvoiceNo;
        self::createBonusPointFeedbackJob($shop_order->id);
        $shop_order->save();
        return response()->json($shop_order,200);
      }
    }
  }

  public static function setShopOrderNo($shop_order)
  {
    if (config('stone.shop.custom_shop_order')) {
      $shop_order->no = \App\Helpers\AppHelper::newShopOrderNo($shop_order->order_type);
    } else {
      $shop_order->no = self::newShopOrderNo();
    }
    $shop_order->save();
    return $shop_order;
  }

}
