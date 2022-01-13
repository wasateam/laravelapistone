<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Jobs\BonusPointFeedbackJob;
use Wasateam\Laravelapistone\Models\Area;
use Wasateam\Laravelapistone\Models\AreaSection;
use Wasateam\Laravelapistone\Models\ShopCampaign;
use Wasateam\Laravelapistone\Models\ShopCampaignShopOrder;
use Wasateam\Laravelapistone\Models\ShopCartProduct;
use Wasateam\Laravelapistone\Models\ShopFreeShipping;
use Wasateam\Laravelapistone\Models\ShopOrder;
use Wasateam\Laravelapistone\Models\ShopOrderShopProduct;
use Wasateam\Laravelapistone\Models\ShopProduct;
use Wasateam\Laravelapistone\Models\ShopReturnRecord;
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
      $shop_product->stock_count = $shop_product->stock_count + $return_record->count;
      $shop_product->save();
      // 訂單商品數量
      $shop_order_product->count = $shop_order_product->count - $return_record->count;
      $shop_order_product->save();
    } else if ($type == 'update') {
      // 商品庫存
      $shop_product->stock_count = $shop_product->stock_count - $shop_order_orginal_count + $shop_product->count + $return_record->count;
      $shop_product->save();
      // 訂單商品數量
      $shop_order_product->count = $shop_order_orginal_count - $return_record->count;
      $shop_order_product->save();
    }
  }

  public static function shopOrderProductChangeCount($shop_order_product_id)
  {
    //成立訂單，更改商品庫存
    $shop_order_product = ShopOrderShopProduct::where('id', $shop_order_product_id)->first();
    $shop_product       = ShopProduct::where('id', $shop_order_product->shop_product_id)->first();
    //減少商品庫存
    $shop_product->stock_count = $shop_product->stock_count - $shop_order_product->count;
    $shop_product->save();
  }

  //取得訂單金額、運費、商品總金額
  public static function calculateShopOrderPrice($shop_order_id, $order_type)
  {
    //計算訂單金額
    $today      = Carbon::now();
    $today_date = $today->format('Y-m-d');

    //計算訂單金額
    $shop_order = ShopOrder::where('id', $shop_order_id)->first();
    //商品價錢總和  - 沒有優惠價就使用售價
    $shop_order_shop_products = $shop_order->shop_order_shop_products;
    $shop_product_price_arr   = $shop_order_shop_products->map(function ($item) {
      return $item->discount_price ? $item->discount_price * $item->count : $item->price * $item->count;
    });
    $shop_product_price_total = Self::sum_total($shop_product_price_arr);

    //運費 default = 100
    $freight = config('stone.shop.freight_default') ? config('stone.shop.freight_default') : 100;
    if ($order_type == 'next-day') {
      //隔日配
      $free_freight_price = ShopFreeShipping::where('end_date', '>=', $today_date)->where('start_date', '<=', $today_date)->first();
      if ($free_freight_price) {
        if ($shop_product_price_total >= $free_freight_price->price) {
          $freight = 0;
        }
      }
    } else if ($order_type == 'pre-order') {
      //預購
      $all_product_freight_arr = $shop_order_shop_products->map(function ($item) {
        return $item->freight ? $item->freight * $item->count : 0;
      });
      $freight = Self::sum_total($all_product_freight_arr);
    }
    //紅利點數
    $bonus_points = $shop_order->bonus_points;
    //折扣碼
    //訂單金額 產品總和+運費
    $order_price = $shop_product_price_total + $freight - $bonus_points;

    return [
      "products_price" => $shop_product_price_total,
      "freight"        => $freight,
      "order_price"    => $order_price,
    ];

  }

  public static function changeShopOrderPrice($shop_order_id, $order_type = null)
  {
    //更新訂單價格
    $shop_order = ShopOrder::where('id', $shop_order_id)->first();
    //取得訂單類型
    $_order_type                = $order_type ? $order_type : $shop_order->order_type;
    $price_array                = Self::calculateShopOrderPrice($shop_order_id, $_order_type);
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
      $price = $shop_cart_product['discount_price'] ? $shop_cart_product['discount_price'] : $shop_cart_product['price'];
      $order_amount += $count * $price;
    }
    return $order_amount;
  }

  public static function getOrderProductAmountPrice($order_product)
  {
    $count = $order_product['count'];
    $price = $order_product['discount_price'] ? $order_product['discount_price'] : $order_product['price'];
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
        self::createUserAddress($user, $request->area, $request->area_section, self::getAddressWithoutArea($request->receive_address, $request->area, $request->area_section), 'delivery');
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
    BonusPointFeedbackJob::dispatch($shop_order_id)
      ->delay(Carbon::now()->addDays($feedback_after_invoice_days));
  }

  public static function samePageCoverDuration($start_date, $end_date, $id = null, $page_settings)
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
      $item_arr = array_map('intval', explode(',', $page_settings));
      $snap     = $snap->whereHas('page_settings', function ($query) use ($item_arr) {
        return $query->whereIn('id', $item_arr);
      });
    }
    $snap = $snap->first();
    if (isset($snap)) {
      return true;
    } else {
      return false;
    }
  }

  public static function adjustBonusPointEnough($user_id, $bonus_points_deduct)
  {
    //紅利點數是否足夠
    $user = User::find($user_id);

    if ($bonus_points_deduct) {
      if ($bonus_points_deduct > $user->bonus_points) {
        return false;
      }
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

}
