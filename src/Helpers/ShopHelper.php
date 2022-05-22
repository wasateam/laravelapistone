<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\EcpayInvoiceHelper;
use Wasateam\Laravelapistone\Helpers\ModelHelper;
use Wasateam\Laravelapistone\Helpers\TimeHelper;
use Wasateam\Laravelapistone\Helpers\UserInviteHelper;
use Wasateam\Laravelapistone\Models\Area;
use Wasateam\Laravelapistone\Models\AreaSection;
use Wasateam\Laravelapistone\Models\BonusPointRecord;
use Wasateam\Laravelapistone\Models\GeneralContent;
use Wasateam\Laravelapistone\Models\InvoiceJob;
use Wasateam\Laravelapistone\Models\ShopCampaign;
use Wasateam\Laravelapistone\Models\ShopCampaignShopOrder;
use Wasateam\Laravelapistone\Models\ShopCart;
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

  public static function createShopOrderShopProductsFromCartProducts($cart_products, $shop_order)
  {

    foreach ($cart_products as $cart_product) {
      $_cart_product          = ShopCartProduct::where('id', $cart_product['id'])->where('status', 1)->first();
      $new_shop_order_product = self::createShopOrderShopProduct($cart_product, $shop_order->id);
      $_cart_product->status  = 0;
      $_cart_product->save();
      self::shopOrderProductChangeCount($new_shop_order_product->id);
    }

  }

  public static function shopOrderProductChangeCount($shop_order_product_id)
  {
    $shop_order_product = ShopOrderShopProduct::where('id', $shop_order_product_id)->first();
    $buy_count          = $shop_order_product->count;
    $origin_stock_count = 0;
    $after_stock_count  = 0;
    $shop_product       = null;
    $shop_product_spec  = null;
    if (isset($shop_order_product->shop_order_shop_proudct_spec)) {
      $origin_stock_count             = $shop_product_spec->stock_count;
      $shop_product_spec              = $shop_order_product->shop_order_shop_proudct_spec->shop_product_spec;
      $shop_product_spec->stock_count = $shop_product_spec->stock_count - $buy_count;
      $shop_product_spec->save();
      $shop_product = $shop_product_spec->shop_product;
    } else {
      $shop_product              = ShopProduct::where('id', $shop_order_product->shop_product_id)->first();
      $origin_stock_count        = $shop_product->stock_count;
      $shop_product->stock_count = $shop_product->stock_count - $buy_count;
      $shop_product->save();
    }
    if ($shop_product->stock_count <= $shop_product->stock_alert_count && $origin_stock_count > $shop_product->stock_alert_count) {
      EmailHelper::notify_shop_order_stock_alert($shop_product, $shop_product_spec);
    }
  }

  public static function getFreight($shop_order = null)
  {
    $freight = config('stone.shop.freight_default');
    return $freight;
  }

  public static function getFreightAfterDeduct(
    $order_type,
    $products_price,
    $campaign_deduct,
    $invite_no_deduct
  ) {
    $freight     = self::getFreight();
    $order_price = $products_price - $campaign_deduct - $invite_no_deduct;
    if (config("stone.shop.order_type.{$order_type}")) {
      if (config("stone.shop.order_type.{$order_type}.freight_default")) {
        $freight = config("stone.shop.order_type.{$order_type}.freight_default");
      }
    }
    $today_date         = Carbon::now()->format('Y-m-d');
    $free_freight_price = ShopFreeShipping::where('end_date', '>=', $today_date)
      ->where('start_date', '<=', $today_date)
      ->first();
    if ($free_freight_price) {
      if ($order_price >= $free_freight_price->price) {
        $freight = 0;
      }
    }
    return $freight;
  }

  public static function getOrderFreight($order_type, $shop_order_shop_products)
  {
    $freight = config('stone.shop.freight_default') ? config('stone.shop.freight_default') : 100;
    if ($order_type) {
      $order_types     = config('stone.shop.order_type') ? config('stone.shop.order_type') : [];
      $curr_order_type = $order_type;
      // $curr_order_type = str_replace('-', '_', $order_type);
      $has_type = array_key_exists($curr_order_type, $order_types);
      if ($has_type) {
        $today_date = Carbon::now()->format('Y-m-d');
        // $order_price              = $shop_order->products_price;
        $order_price = self::getOrderProductsAmount($shop_order_shop_products);
        // $shop_order_shop_products = $shop_order->shop_order_shop_products;
        $type = $order_types[$curr_order_type];
        if ($type['freight_default']) {
          $freight = $type['freight_default'];
        }
        if ($type['has_shop_free_shipping']) {
          $free_freight_price = ShopFreeShipping::where('end_date', '>=', $today_date)->where('start_date', '<=', $today_date)->first();
          if ($free_freight_price) {
            if ($order_price >= $free_freight_price->price) {
              $freight = 0;
            }
          }
        }
        //type has freight_sepe
        if (array_key_exists('freight_separate', $type) && $type['freight_separate']) {
          $all_product_freight_arr = $shop_order_shop_products->map(function ($shop_order_shop_product) {
            $freight = 0;
            if (isset($shop_order_shop_product->shop_order_shop_product_spec)) {
              $freight = $shop_order_shop_product->shop_order_shop_prdouct_spec->freight ? $shop_order_shop_product->shop_order_shop_prdouct_spec->freight : 0;
            } else {
              $freight = $shop_order_shop_product->freight ? $shop_order_shop_product->freight : 0;
            }
            return $freight * $shop_order_shop_product->count;
          });
          $freight = Self::sum_total($all_product_freight_arr);
        }
      }
    }
    return $freight;
  }

  //取得訂單金額、運費、商品總金額
  // @Q@ 要拿掉，目前使用於退訂
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
        $price = $spec->discount_price && config('stone.shop.discount_price') ? $spec->discount_price : $spec->price;
      } else {
        $price = $item->discount_price && config('stone.shop.discount_price') ? $item->discount_price : $item->price;
      }
      return $price * $item->count;
    });
    $shop_product_price_total = Self::sum_total($shop_product_price_arr);

    // $dicount_shop_product_price_total = 0;
    // discount_code
    // create discount_code shop_camapign

    // @Q@ deifferent day change will cause error
    // if ($request && $request->has('discount_code') && $request->discount_code) {
    //   $today_dicount_decode_campaign = self::getTodayDiscountCodeCampaign($request->discount_code);
    //   if ($today_dicount_decode_campaign) {
    //     self::createShopCampaignShopOrder($shop_order, $today_dicount_decode_campaign);
    //     if ($shop_product_price_total >= $today_dicount_decode_campaign->full_amount) {
    //       if ($today_dicount_decode_campaign->discount_percent) {
    //         $shop_product_price_total = $shop_product_price_total * $today_dicount_decode_campaign->discount_percent;
    //       } else if ($today_dicount_decode_campaign->discount_amount) {
    //         $shop_product_price_total = $shop_product_price_total - $today_dicount_decode_campaign->discount_amount;
    //       }
    //     }
    //   }
    // }
    //紅利點數
    //create bonus_points record
    $bonus_points = $shop_order->bonus_points_deduct ? $shop_order->bonus_points_deduct : 0;
    // self::createBonusPointRecordFromShopOrder($shop_order, null, $bonus_points, 'deduct');

    //運費 default = 100
    $freight = config('stone.shop.freight_default') ? config('stone.shop.freight_default') : 100;
    if ($order_type) {
      $order_types     = config('stone.shop.order_type') ? config('stone.shop.order_type') : [];
      $curr_order_type = $order_type;
      // $curr_order_type = str_replace('-', '_', $order_type);
      $has_type = array_key_exists($curr_order_type, $order_types);
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
    $price_array                = self::calculateShopOrderPrice($shop_order_id, $_order_type, $request);
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

  public static function updateShopOrderPrice(
    $shop_order,
    $discount_code,
    $bonus_points,
    $invite_no
  ) {
    $products_price      = self::getOrderProductsAmount($shop_order->shop_order_shop_products);
    $campaign_deduct     = self::getDateCampaignDeduct($shop_order->user, $shop_order->created_at, $products_price, $discount_code);
    $invite_no_deduct    = self::getInviteNoDeduct($products_price, $invite_no, $shop_order->user, [$shop_order->id]);
    $bonus_points_deduct = self::getBonusPointsDeduct($bonus_points, $products_price, $campaign_deduct, $invite_no_deduct);
    $freight             = self::getFreightAfterDeduct($shop_order->order_type, $products_price, $campaign_deduct, $invite_no_deduct);
    $order_price         = self::getOrderPrice($products_price, $freight, $bonus_points_deduct, $campaign_deduct, $invite_no_deduct);

    $shop_order->products_price      = $products_price;
    $shop_order->campaign_deduct     = $campaign_deduct;
    $shop_order->invite_no_deduct    = $invite_no_deduct;
    $shop_order->bonus_points_deduct = $bonus_points_deduct;
    $shop_order->freight             = $freight;
    $shop_order->order_price         = $order_price;
    $shop_order->save();

    // self::createShopCampaignShopOrder($shop_order, $today_dicount_decode_campaign, $campaign_deduct);
  }

  public static function setCampaignDeduct($shop_order, $discount_code)
  {
    $campaign_deduct = 0;
    if ($discount_code) {
      $today_dicount_decode_campaign = self::getAvailableShopCampaign('discount_code', $shop_order->user_id, $shop_order->created_at, $discount_code);
      $campaign_deduct               = self::getShopCampaignDeductFromShopCampaign($shop_order->products_price, $today_dicount_decode_campaign);
      self::createShopCampaignShopOrder($shop_order, $today_dicount_decode_campaign, $campaign_deduct);
    }
    return $campaign_deduct;
  }

  public static function getShopCampaignDeductFromShopCampaign($products_price, $today_dicount_decode_campaign = null)
  {
    $campaign_deduct = 0;
    if ($today_dicount_decode_campaign) {
      if ($products_price >= $today_dicount_decode_campaign->full_amount) {
        if ($today_dicount_decode_campaign->discount_way == 'discount_amount') {
          $campaign_deduct += $products_price >= $today_dicount_decode_campaign->discount_amount ? $today_dicount_decode_campaign->discount_amount : $products_price;
        } else if ($today_dicount_decode_campaign->discount_way == 'discount_percent') {
          $campaign_deduct += $products_price - round($products_price * $today_dicount_decode_campaign->discount_percent / 10);
        }
      }
    }
    return $campaign_deduct;
  }

  public static function getDateCampaignDeduct(
    $user,
    $datetime,
    $products_price,
    $discount_code
  ) {
    $campaign_deduct = 0;
    if ($discount_code) {
      $today_dicount_decode_campaign = self::getAvailableShopCampaign('discount_code', $user->id, $datetime, $discount_code);
      $campaign_deduct               = self::getShopCampaignDeductFromShopCampaign($products_price, $today_dicount_decode_campaign);
      // if ($today_dicount_decode_campaign) {
      //   if ($products_price >= $today_dicount_decode_campaign->full_amount) {
      //     if ($today_dicount_decode_campaign->discount_percent) {
      //       $campaign_deduct += $products_price - round($products_price * $today_dicount_decode_campaign->discount_percent / 10);
      //     } else if ($today_dicount_decode_campaign->discount_amount) {
      //       if ($today_dicount_decode_campaign->discount_amount > $products_price) {
      //         $campaign_deduct = $products_price;
      //       } else {
      //         $campaign_deduct += $today_dicount_decode_campaign->discount_amount;
      //       }
      //     }
      //   }
      // }
    }
    return $campaign_deduct;
  }

  public static function getInviteNoDeduct(
    $products_price,
    $invite_no,
    $user,
    $neglect_shop_order_ids = null
  ) {
    if (!$invite_no) {
      return 0;
    }

    $check = UserInviteHelper::check($invite_no, $user, $neglect_shop_order_ids);
    if (!$check) {
      return 0;
    }

    $invited_shop_deduct_rate = 87;
    if (config('stone.user.invite.general')) {
      if (config('stone.user.invite.general.invited_shop_deduct_rate')) {
        $invited_shop_deduct_rate = config('stone.user.invite.general.invited_shop_deduct_rate');
      }
      $general_user_invite = GeneralContent::where('name', 'general_user_invite')->first();
      if ($general_user_invite) {
        if ($general_user_invite->content) {
          if ($general_user_invite->content['invited_shop_deduct_rate']) {
            $invited_shop_deduct_rate = $general_user_invite->content['invited_shop_deduct_rate'];
            $invite_no_deduct         = $products_price - round($products_price * $invited_shop_deduct_rate / 10);
          }
          if ($general_user_invite->content['invited_shop_deduct']) {
            if ($general_user_invite->content['invited_shop_deduct'] > $products_price) {
              $invite_no_deduct = $products_price;
            } else {
              $invite_no_deduct = $general_user_invite->content['invited_shop_deduct'];
            }
          }
        }
      }
    }
    return $invite_no_deduct;
  }

  public static function getOrderProductsAmount($shop_order_shop_products)
  {
    $order_amount = 0;
    foreach ($shop_order_shop_products as $shop_cart_product) {
      $count = $shop_cart_product['count'];
      $price = 0;
      if (isset($shop_cart_product->shop_product_spec)) {
        $price = $shop_cart_product->shop_product_spec['discount_price'] && config('stone.shop.discount_price') ? $shop_cart_product->shop_product_spec['discount_price'] : $shop_cart_product->shop_product_spec['price'];
      } else {
        $price = $shop_cart_product['discount_price'] && config('stone.shop.discount_price') ? $shop_cart_product['discount_price'] : $shop_cart_product['price'];
      }
      $order_amount += $count * $price;
    }
    return $order_amount;
  }

  public static function getOrderAmountFromShopOrder($shop_order)
  {
    $order_amount = $shop_order->products_price;
    if ($shop_order->freight) {
      $order_amount += $shop_order->freight;
    }
    if ($shop_order->bonus_points_deduct) {
      $order_amount -= $shop_order->bonus_points_deduct;
    }
    if ($shop_order->campaign_deduct) {
      $order_amount -= $shop_order->campaign_deduct;
    }
    return $order_amount;
  }

  public static function getOrderPrice($products_price, $freight, $bonus_points_deduct, $campaign_deduct, $invite_no_deduct)
  {
    $order_amount = $products_price;
    if ($freight) {
      $order_amount += $freight;
    }
    if ($bonus_points_deduct) {
      $order_amount -= $bonus_points_deduct;
    }
    if ($campaign_deduct) {
      $order_amount -= $campaign_deduct;
    }
    if ($invite_no_deduct) {
      $order_amount -= $invite_no_deduct;
    }
    return $order_amount;
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
        $price = $shop_cart_product->shop_product_spec['discount_price'] && config('stone.shop.discount_price') ? $shop_cart_product->shop_product_spec['discount_price'] : $shop_cart_product->shop_product_spec['price'];
      } else {
        $price = $shop_cart_product['discount_price'] && config('stone.shop.discount_price') ? $shop_cart_product['discount_price'] : $shop_cart_product['price'];
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
      $price = $order_product->shop_product_spec['discount_price'] && config('stone.shop.discount_price') ? $order_product->shop_product_spec['discount_price'] : $order_product->shop_product_spec['price'];
    } else {
      $price = $order_product['discount_price'] && config('stone.shop.discount_price') ? $order_product['discount_price'] : $order_product['price'];
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

  //pdf 整理訂單裡的商品格式
  public static function fetchShopOrderProduct($order_products)
  {
    $datas = [];
    foreach ($order_products as $order_product) {
      $datas[] = [
        'id'                   => $order_product->id,
        'no'                   => $order_product->shop_product->no,
        'name'                 => $order_product->name,
        'spec'                 => $order_product->spec,
        'weight_capacity'      => $order_product->weight_capacity,
        'storage_space'        => $order_product->shop_product->storage_space,
        'count'                => $order_product->count,
        'weight_capacity_unit' => $order_product->shop_product->weight_capacity_unit,
      ];
    }

    usort($datas, function ($a, $b) {
      return strcmp($a['storage_space'], $b['storage_space']);
    });

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

  public static function getBonusPointFromShopOrder($shop_order)
  {
    $shop_campaign = self::getAvailableShopCampaign('bonus_point_feedback', $shop_order->user_id, $shop_order->created_at);
    if (!$shop_campaign) {
      return 0;
    }
    $calc_order_price = $shop_order->order_price - $shop_order->freight;

    if ($shop_campaign->feedback_rate) {
      return intval(intval($calc_order_price) * floatval($shop_campaign->feedback_rate) / 100);
    } else {
      return 0;
    }
  }

  public static function createBonusPointFromShopOrder($shop_order)
  {
    $record = BonusPointRecord::where('shop_order_id', $shop_order->id)
      ->where('source', 'new_shop_order')
      ->where('type', 'get')
      ->first();
    if ($record) {
      return;
    }

    if ($shop_order->bonus_points) {
      $user                 = $shop_order->user;
      $user->bonus_points   = $user->bonus_points + $shop_order->bonus_points;
      $user->save();
      self::createBonusPointRecordFromShopOrder($shop_order, $shop_campaign->id, $bonus_point_feedback, 'get');
    }

    if (config('stone.user.invite')) {
      if ($shop_order->invite_no) {
        $invite_feedback_bonus_points = 87;
        if (config('stone.user.invite.general')) {
          if (config('stone.user.invite.general.invite_feedback_bonus_points')) {
            $invite_feedback_bonus_points = config('stone.user.invite.general.invite_feedback_bonus_points');
          }
        }
        $general_user_invite = GeneralContent::where('name', 'general_user_invite')->first();
        if ($general_user_invite) {
          if ($general_user_invite->content) {
            if ($general_user_invite->content['invite_feedback_bonus_points']) {
              $invite_feedback_bonus_points = $general_user_invite->content['invite_feedback_bonus_points'];
            }
          }
        }
        $target_user = User::where('invite_no', $shop_order->invite_no)
          ->whereNull('deleted_at')
          ->first();
        $target_user->bonus_points = $target_user->bonus_points + $invite_feedback_bonus_points;
        $target_user->save();
        self::createBonusPointRecordFromInvite($target_user, $invite_feedback_bonus_points);
      }
    }
  }

  public static function getAvailableShopCampaign($type, $user_id, $date = null, $discount_code = null)
  {
    $snap = ShopCampaign::where('type', $type)
      ->where('is_active', 1)
      ->where(function ($query) use ($date) {
        $query->where(function ($query) use ($date) {
          if (!$date) {
            $date = Carbon::now();
          }
          $today_date = Carbon::parse($date)->format('Y-m-d');
          $query->where('start_date', '<=', $today_date);
          $query->where('end_date', '>=', $today_date);
        });
        $query->orWhere(function ($query) {
          $query->whereNull('start_date');
          $query->whereNull('end_date');
        });
      });
    if ($type == 'discount_code' && $discount_code) {
      $snap->where('discount_code', $discount_code);
    }

    $shop_campaign = $snap->first();
    if ($shop_campaign) {
      if ($shop_campaign->limit) {
        $count = $shop_campaign->shop_campaign_shop_products->count;
        if ($count >= $shop_campaign->limit) {
          return false;
        }
      }
      if ($shop_campaign->condition == 'first-purchase') {
        $shop_order = ShopOrder::where('user_id', $user_id)
          ->where('pay_status', 'paid')
          ->first();
        if ($shop_order) {
          return null;
        }
      }
      return $shop_campaign;
    } else {
      return null;
    }
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

    if (!$request->has('bonus_points')) {
      return;
    }

    $user         = User::find($request->user);
    $bonus_points = $request->bonus_points;
    if ($bonus_points > $user->bonus_points) {
      return false;
      throw new \Wasateam\Laravelapistone\Exceptions\OutOfException('bonus_points');
    }
  }

  public static function checkShopOrderInviteNo($request, $user)
  {

    if (!$request->has('invite_no')) {
      return;
    }
    if (!$request->invite_no) {
      return;
    }

    $check = UserInviteHelper::check($request->invite_no, $user);

    if (!$check) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('invite_no');
    }
  }

  public static function getTodayDiscountCodeCampaign($discount_code)
  {
    //取得折扣碼活動
    $today_date                   = Carbon::now()->format('Y-m-d');
    $today_discount_code_campaign = ShopCampaign::whereDate('start_date', '<=', $today_date)->whereDate('end_date', '>=', $today_date)->where('type', 'discount_code')->where('is_active', 1)->where('discount_code', $discount_code)->first();

    if (!$today_discount_code_campaign) {
      return null;
    }
    // shop_campaign limit is enought or not
    if ($today_discount_code_campaign->limit) {
      if ($today_discount_code_campaign->shop_campaign_shop_orders->count >= $today_discount_code_campaign->limit) {
        return null;
      }
    }

    return $today_discount_code_campaign;
  }

  public static function createShopCampaignShopOrder($shop_order, $shop_campaign = null, $campaign_deduct)
  {
    if (!$shop_campaign) {
      return;
    }
    $shop_campaign_shop_order                   = new ShopCampaignShopOrder;
    $shop_campaign_shop_order->shop_campaign_id = $shop_campaign->id;
    $shop_campaign_shop_order->shop_order_id    = $shop_order->id;
    $shop_campaign_shop_order->user_id          = $shop_order->user->id;
    $shop_campaign_shop_order->type             = $shop_campaign->type;
    $shop_campaign_shop_order->name             = $shop_campaign->name;
    $shop_campaign_shop_order->condition        = $shop_campaign->condition;
    $shop_campaign_shop_order->full_amount      = $shop_campaign->full_amount;
    $shop_campaign_shop_order->discount_percent = $shop_campaign->discount_percent;
    $shop_campaign_shop_order->discount_amount  = $shop_campaign->discount_amount;
    $shop_campaign_shop_order->feedback_rate    = $shop_campaign->feedback_rate;
    $shop_campaign_shop_order->campaign_deduct  = $campaign_deduct;
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
      $new_shop_product_spec_setting->sq              = isset($shop_product_spec_setting['sq']) ? $shop_product_spec_setting['sq'] : null;
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
        $new_shop_product_spec_setting_item->sq                           = isset($shop_product_spec_setting_item['sq']) ? $shop_product_spec_setting_item['sq'] : null;
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

  public static function get_user_shop_cart($user)
  {
    $shop_cart = ShopCart::where('user_id', $user->id)->first();
    if (!$shop_cart) {
      $shop_cart          = new ShopCart;
      $shop_cart->user_id = $user->id;
      $shop_cart->save();
    }
    return $shop_cart;
  }

  public static function createShopOrderShopProduct($shop_cart_product, $shop_order_id)
  {
    # 建立訂單時建立訂單商品(用購物車商品判斷)
    $shop_order_shop_product                       = new ShopOrderShopProduct;
    $shop_product                                  = $shop_cart_product->shop_product;
    $shop_order_shop_product->name                 = $shop_product->name;
    $shop_order_shop_product->subtitle             = $shop_product->subtitle;
    $shop_order_shop_product->count                = $shop_cart_product->count;
    $shop_order_shop_product->original_count       = $shop_cart_product->count;
    $shop_order_shop_product->price                = $shop_product->price;
    $shop_order_shop_product->discount_price       = $shop_product->discount_price;
    $shop_order_shop_product->spec                 = $shop_product->spec;
    $shop_order_shop_product->weight_capacity      = $shop_product->weight_capacity;
    $shop_order_shop_product->cover_image          = $shop_product->cover_image;
    $shop_order_shop_product->order_type           = $shop_product->order_type;
    $shop_order_shop_product->freight              = $shop_product->freight;
    $shop_order_shop_product->shop_product_id      = $shop_product->id;
    $shop_order_shop_product->cost                 = $shop_product->cost;
    $shop_order_shop_product->shop_cart_product_id = $shop_cart_product['id'];
    $shop_order_shop_product->shop_order_id        = $shop_order_id;
    $shop_order_shop_product->save();
    if (isset($shop_cart_product->shop_product_spec)) {
      self::createShopOrderShopProductSpec($shop_cart_product->shop_product_spec, $shop_order_shop_product->id);
    } else {

    }
    $shop_order_shop_product->save();
    return $shop_order_shop_product;
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
        $all_price[] = $shop_product_spec->discount_price && config('stone.shop.discount_price') ? $shop_product_spec->discount_price : $shop_product_spec->price;
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

  public static function getOrderTypeFromShopCartProducts($shop_cart_products = [])
  {
    $shop_cart_product = $shop_cart_products[0];
    $cart_product      = ShopCartProduct::where('id', $shop_cart_product['id'])->where('status', 1)->where('count', ">", 0)->first();
    if (!$cart_product) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_cart_product', $shop_cart_product['id']);
    }
    return $cart_product->shop_product->order_type;
  }

  // public static function getMyCartProducts($request, $order_type)
  public static function filterCartProducts($shop_cart_products, $user, $order_type)
  {
    $_filtered_cart_products = [];
    foreach ($shop_cart_products as $shop_cart_product) {
      $cart_product = ShopCartProduct::where('id', $shop_cart_product['id'])->where('status', 1)->where('count', ">", 0)->first();
      if (!$cart_product) {
        throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_cart_product', $shop_cart_product['id']);
      }
      if ($cart_product->user_id != $user->id) {
        throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_cart_product', $cart_product->id);
      }
      if ($order_type && $cart_product->shop_product->order_type != $order_type) {
        throw new \Wasateam\Laravelapistone\Exceptions\FieldNotMatchException('order_type', $order_type);
      }
      self::updateShopCartProductPrice($cart_product);
      self::checkProductStockEnough($cart_product);
      $_filtered_cart_products[] = $cart_product;
    }
    return $_filtered_cart_products;
  }

  public static function updateShopCartProductPrice($shop_cart_product)
  {
    if ($shop_cart_product->price != $shop_cart_product->shop_product->price) {
      $shop_cart_product->price = $shop_cart_product->shop_product->price;
    }
    if ($shop_cart_product->dicount_price != $shop_cart_product->shop_product->dicount_price) {
      $shop_cart_product->dicount_price = $shop_cart_product->shop_product->dicount_price;
    }
    $shop_cart_product->save();
  }

  public static function checkDiscountCode($discount_code = null)
  {
    if ($discount_code) {
      $today_dicount_decode_campaign = self::getTodayDiscountCodeCampaign($discount_code);
      if (!$today_dicount_decode_campaign) {
        throw new \Wasateam\Laravelapistone\Exceptions\InvalidException('discount_code');
      }
    }
  }

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
      $price = $spec->discount_price && config('stone.shop.discount_price') ? $spec->discount_price : $spec->price;
    } else {
      $price = $shop_order_shop_product->discount_price && config('stone.shop.discount_price') ? $shop_order_shop_product->dicount_price : $shop_order_shop_product->price;
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

  public static function createBonusPointRecordFromShopOrder($shop_order, $shop_campaign_id = null, $point_count, $type)
  {
    $bonus_point_record                   = new BonusPointRecord;
    $bonus_point_record->user_id          = $shop_order->user_id;
    $bonus_point_record->shop_order_id    = $shop_order->id;
    $bonus_point_record->shop_campaign_id = $shop_campaign_id;
    $bonus_point_record->type             = $type;
    $bonus_point_record->source           = 'new_shop_order';
    $bonus_point_record->count            = $point_count;
    $bonus_point_record->save();
  }

  public static function createBonusPointRecordFromInvite($user, $point_count)
  {
    $bonus_point_record          = new BonusPointRecord;
    $bonus_point_record->user_id = $user->id;
    $bonus_point_record->type    = 'get';
    $bonus_point_record->source  = 'member_invite';
    $bonus_point_record->count   = $point_count;
    $bonus_point_record->save();
  }

  public static function readyToCreateInvoice($shop_order, $ori_shop_order = null)
  {
    if ($shop_order->order_price <= 0) {
      return;
    }
    if ($ori_shop_order) {
      $check = 0;
      if (!count($shop_order->shop_return_records) && $ori_shop_order->ship_status == 'collected' && $shop_order->ship_status == 'shipped') {
        $check = 1;
      }
      if (count($shop_order->shop_return_records) && $ori_shop_order->status == 'return-part-apply' && $shop_order->status == 'return-part-complete') {
        $check = 1;
      }
      if ($ori_shop_order->status == 'cancel' && $shop_order->status == 'cancel-complete') {
        $check = 1;
      }
      if (!$check) {
        return;
      }
    }
    if (config('stone.invoice')) {
      if ($shop_order->invoice_status == 'fail') {
        $shop_order = self::createInvoice($shop_order);
      } else if (config('stone.invoice.delay')) {
        self::createInvoiceJob($shop_order, config('stone.invoice.delay'));
        $shop_order->invoice_status = 'waiting';
        $shop_order->save();
      } else {
        $shop_order = self::createInvoice($shop_order);
      }
    }
    return $shop_order;
  }

  public static function createInvoiceJob($shop_order, $delay_days)
  {
    $model                = new InvoiceJob;
    $model->status        = 'waiting';
    $model->invoice_date  = \Carbon\Carbon::now()->addDays($delay_days);
    $model->shop_order_id = $shop_order->id;
    $model->save();
  }

  public static function createInvoice($shop_order)
  {
    if ($shop_order->invoice_status == 'done') {
      return;
    }
    if (config('stone.invoice')) {
      if (config('stone.invoice.service') == 'ecpay') {
        try {
          $invoice_type       = $shop_order->invoice_type;
          $SalesAmount        = $shop_order->order_price;
          $Items              = EcpayInvoiceHelper::getInvoiceItemsFromShopOrder($shop_order);
          $CustomerID         = $shop_order->user_id;
          $CustomerIdentifier = '';
          $CustomerName       = '';
          $CustomerAddr       = $shop_order->receive_address;
          $CustomerPhone      = $shop_order->orderer_tel;
          $CustomerEmail      = $shop_order->orderer_email;
          $Tsr                = $shop_order->no;
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
            if ($shop_order->invoice_status == 'fail') {
              $customer_email = $shop_order->user->email;
              $CarrierType    = '1';
              $CarrierNum     = '';
              $CustomerEmail  = $customer_email;
            } else {
              if ($invoice_carrier_type == 'mobile') {
                $CarrierType = '3';
                $CarrierNum  = $invoice_carrier_number;
              } else if ($invoice_carrier_type == 'certificate') {
                $CarrierType = '2';
                $CarrierNum  = $invoice_carrier_number;
              } else if ($invoice_carrier_type == 'email') {
                $customer_email = $invoice_carrier_number;
                if (!$invoice_carrier_number && $shop_order->user) {
                  $customer_email = $shop_order->user->email;
                }
                $CarrierType   = '1';
                $CarrierNum    = '';
                $CustomerEmail = $customer_email;
              }
            }
          } else if ($invoice_type == 'triple') {
            $invoice_title          = $shop_order->invoice_title;
            $invoice_uniform_number = $shop_order->invoice_uniform_number;
            $CarrierType            = '';
            $Print                  = '1';
            $CustomerName           = $invoice_title;
            $CustomerIdentifier     = $invoice_uniform_number;
          }
          $post_data = EcpayInvoiceHelper::getInvoicePostData(
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
          $invoice_res                = EcpayInvoiceHelper::createInvoice($post_data);
          $shop_order->invoice_status = 'done';
          $shop_order->invoice_number = $invoice_res->InvoiceNo;
          $shop_order->save();
        } catch (\Throwable $th) {
          $shop_order->invoice_status = 'fail';
          $shop_order->save();
          throw $th;
        }
      }
      if ($shop_order->invoice_status == 'done') {
        self::createBonusPointFromShopOrder($shop_order);
      }
    }
  }

  public static function deductBonusPointFromShopOrder($shop_order)
  {
    if (!$shop_order->bonus_points_deduct) {
      return;
    }
    $user               = User::find($shop_order->user_id);
    $user->bonus_points = $user->bonus_points - $shop_order->bonus_points_deduct;
    $user->save();
    self::createBonusPointRecordFromShopOrder($shop_order, null, $shop_order->bonus_points_deduct, 'deduct');
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

  public static function getBonusPointsDeduct(
    $bonus_points,
    $products_price,
    $campaign_deduct,
    $invite_no_deduct
  ) {
    $order_price = $products_price - $campaign_deduct - $invite_no_deduct;
    if ($bonus_points > $order_price) {
      $bonus_points_deduct = $order_price;
    } else {
      $bonus_points_deduct = $bonus_points;
    }
    if (!$bonus_points_deduct) {
      $bonus_points_deduct = 0;
    }
    return $bonus_points_deduct;
  }

  public static function ShopOrderNoPriceCheck($shop_order)
  {
    if (!$shop_order->order_price) {
      $shop_order->pay_at         = Carbon::now();
      $shop_order->pay_status     = 'paid';
      $shop_order->status         = 'established';
      $shop_order->ship_status    = 'unfulfilled';
      $shop_order->invoice_status = 'no-need';
      $shop_order->save();
      self::setShopOrderNo($shop_order);
    }
  }

  public static function ShopProductStorageSpaceCheck($storage_space, $on_time, $off_time = null, $shop_product_id = null)
  {
    $snap = ShopProduct::where('storage_space', $storage_space)
      ->where(function ($query) use ($on_time, $off_time) {
        if ($off_time) {
          $query->where(function ($query) use ($on_time, $off_time) {
            $query->where('on_time', '>', $on_time);
            $query->where('on_time', '<', $off_time);
          });
          $query->orWhere(function ($query) use ($off_time) {
            $query->where('on_time', '<', $off_time);
            $query->whereNull('off_time');
          });
          $query->orWhere(function ($query) use ($on_time, $off_time) {
            $query->where('on_time', '<', $on_time);
            $query->where('off_time', '>', $off_time);
          });
          $query->orWhere(function ($query) use ($on_time, $off_time) {
            $query->where('off_time', '>', $on_time);
            $query->where('off_time', '<', $off_time);
          });
        } else {
          $query->where(function ($query) use ($on_time) {
            $query->where('off_time', '>', $on_time);
          });
          $query->orWhere(function ($query) use ($on_time) {
            $query->whereNull('off_time');
          });
        }
      });
    if ($shop_product_id) {
      $snap = $snap->where('id', '!=', $shop_product_id);
    }
    $exist = $snap->first();
    if ($exist) {
      throw new \Wasateam\Laravelapistone\Exceptions\GeneralException('storage_space repeat.');
    }
  }

  public static function getShopProductSpecName($shop_product_spec)
  {
    $spec_name = '';
    foreach ($shop_product_spec->shop_product_spec_setting_items as $index => $shop_product_spec_setting_item) {
      if ($index > 0) {
        $spec_name .= ', ';
      }
      $spec_name .= $shop_product_spec_setting_item->name;
    }
    return $spec_name;
  }

  public static function getShopProductSalesCount($shop_product_id, $shop_order_request)
  {
    $shop_order_shop_products = ShopOrderShopProduct::where('shop_product_id', $shop_product_id)
      ->whereHas('shop_order', function ($query) use ($shop_order_request) {
        $setting = ModelHelper::getSetting(new \Wasateam\Laravelapistone\Controllers\ShopOrderController);
        $query   = ModelHelper::indexGetSnap($setting, $shop_order_request, null, false, $query);
        $query->whereIn('status',
          [
            'established',
            'not-established',
            'return-part-apply',
            'cancel',
            'return-part-complete',
            'cancel-complete',
            'complete',
          ]);
      })
      ->get();
    $sales_count = 0;
    foreach ($shop_order_shop_products as $shop_order_shop_product) {
      $sales_count += $shop_order_shop_product->count;
      foreach ($shop_order_shop_product->shop_return_records as $shop_return_record) {
        $sales_count -= $shop_return_record->count;
      }
    }
    return $sales_count;
  }

  public static function checkShopOrderPayExpire()
  {
    $expire_time = config('stone.shop.pay_expire.time_limit');
    if (!$expire_time) {
      throw new \Wasateam\Laravelapistone\Exceptions\StoneConfigNotSetException('stone.shop.pay_expire.time_limit');
    }
    $check_time  = Carbon::now()->addSeconds($expire_time * -1);
    $shop_orders = ShopOrder::where('pay_status', 'waiting')
      ->where('created_at', '<', $check_time)
      ->get();
    foreach ($shop_orders as $shop_order) {
      foreach ($shop_order->shop_order_shop_products as $shop_order_shop_product) {
        $shop_product = $shop_order_shop_product->shop_product;
        $shop_product->stock_count += $shop_order_shop_product->count;
        $shop_product->save();
      }
    }
    $shop_orders = ShopOrder::where('pay_status', 'waiting')
      ->where('created_at', '<', $check_time)
      ->update([
        'pay_status' => 'not-paid',
      ]);
  }

  public static function ShopOrderShipTimeSet($shop_order)
  {
    $shop_ship_time_setting      = $shop_order->shop_ship_time_setting;
    $shop_order->ship_start_time = $shop_ship_time_setting->start_time;
    $shop_order->ship_end_time   = $shop_ship_time_setting->end_time;
    $shop_order->ship_date       = Carbon::parse($shop_order->created_at)->addDays(1);
    $shop_order->save();
  }

  public static function getReceiveWayText($receive_way)
  {
    if ($receive_way == 'phone-contact') {
      return '電話聯絡收件人';
    } else if ($receive_way == 'phone-contact-building-manager') {
      return '電聯收件人後，交由管理室代收';
    } else if ($receive_way == 'building-manager') {
      return '不需電聯，直接交由管理室代收';
    }
  }

  public static function shopOrderShipStatusModifyCheck($shop_order, $request)
  {
    if ($request->filled('ship_status') && $request->ship_status == 'shipped') {
      if ($shop_order->ship_status != 'collected' && $shop_order->ship_status != 'shipped') {
        throw new \Wasateam\Laravelapistone\Exceptions\GeneralException('need collected status for shipped.');
      }
    }
  }

  public static function shopOrderReturnAllProducts($shop_order)
  {
    foreach ($shop_order->shop_order_shop_products as $shop_order_shop_product) {
      $shop_return_record                             = new ShopReturnRecord;
      $shop_return_record->count                      = $shop_order_shop_product->count;
      $shop_return_record->user_id                    = $shop_order->user_id;
      $shop_return_record->shop_order_id              = $shop_order->id;
      $shop_return_record->shop_order_shop_product_id = $shop_order_shop_product->id;
      $shop_return_record->type                       = 'return-all';
      $shop_return_record->shop_product_id            = $shop_order_shop_product->shop_product->id;
      $shop_return_record->save();
    }
  }

  public static function getShopOrderShipTimeRange($shop_order, $timezone)
  {
    return TimeHelper::getTimeFromTimezone($shop_order->ship_start_time, $timezone) . '-' . TimeHelper::getTimeFromTimezone($shop_order->ship_end_time, $timezone);
  }

  public static function getShopOrderCreatedAt($shop_order, $timezone)
  {
    if (!$shop_order->created_at) {
      return null;
    }
    $created_at = $shop_order->created_at->timezone($timezone);
    if (config('stone.shop.order.export')) {
      if (config('stone.shop.order.export.created_at')) {
        if (config('stone.shop.order.export.created_at.format')) {
          $created_at = $created_at->format(config('stone.shop.order.export.created_at.format'));
        }
      }
    }
    return $created_at;
  }

  public static function getShopOrderShipDate($shop_order, $timezone)
  {
    if (!$shop_order->ship_date) {
      return null;
    }
    $ship_date = $shop_order->ship_date->timezone($timezone);
    if (config('stone.shop.order.export')) {
      if (config('stone.shop.order.export.ship_date')) {
        if (config('stone.shop.order.export.ship_date.format')) {
          $ship_date = $ship_date->format(config('stone.shop.order.export.ship_date.format'));
        }
      }
    }
    return $ship_date;
  }

  public static function checkFirstShopOrderOfYearOfUser($shop_order)
  {
    $first_purchase_check = 0;
    if (!$shop_order->user) {
      return 0;
    }
    $current_year_paid_shop_order = ShopOrder::where('user_id', $shop_order->user->id)
      ->whereYear('created_at', \Carbon\Carbon::parse($shop_order->created_at)->format('Y'))
      ->orderBy('pay_at', 'asc')
      ->first();
    if (
      !$current_year_paid_shop_order ||
      $current_year_paid_shop_order->id == $shop_order->id
    ) {
      $first_purchase_check = 1;
    }
    return $first_purchase_check;
  }

  public static function getShopOrderOrderTypeTitle($shop_order)
  {
    $title      = '';
    $order_type = $shop_order->order_type;
    if (config('stone.shop.order_type')) {
      if (config('stone.shop.order_type')[$order_type]) {
        $title = config('stone.shop.order_type')[$order_type]['title'];
      }
    }
    return $title;
  }

  public static function getShopOrderShipStatusTitle($shop_order)
  {
    $ship_status = $shop_order->ship_status;
    $title_arr   = [
      'unfulfilled' => '待出貨',
      'collected'   => '準備出貨',
      'shipped'     => '已出貨',
      'pending'     => '問題待解決',
    ];
    return $ship_status && $title_arr[$ship_status] ? $title_arr[$ship_status] : '';
  }

  public static function getShopOrderStatusTitle($shop_order)
  {
    $status    = $shop_order->status;
    $title_arr = [
      'established'          => '成立',
      'not-established'      => '未成立',
      'return-part-apply'    => '申請部分退訂',
      'return-part-complete' => '部分退訂完成',
      'cancel'               => '申請全部退訂',
      'cancel-complete'      => '全部退訂完成',
      'complete'             => '訂單完成',
    ];
    return $status && $title_arr[$status] ? $title_arr[$status] : '';
  }

  public static function getFreightDeduct($shop_order)
  {
    if ($shop_order->freight) {
      return self::getFreight();
    } else {
      return 0;
    }
  }

  public static function getShopOrderArrayForExport($shop_orders, $request)
  {
    $array    = [];
    $timezone = 'UTC';
    if ($request && $request->country_code) {
      $timezone = TimeHelper::getTimeZoneFromCountryCode($request->country_code);
    } else if (config('stone.timezone')) {
      $timezone = config('stone.timezone');
    }
    foreach ($shop_orders as $shop_order) {
      $order_products = $shop_order->shop_order_shop_products;

      foreach ($order_products as $index => $order_product) {
        $shop_product                       = $order_product->shop_product;
        $first_purchase_check               = '';
        $shop_order_user_id                 = '';
        $orderer                            = '';
        $orderer_tel                        = '';
        $orderer_email                      = '';
        $invoice_uniform_number             = '';
        $invoice_title                      = '';
        $receiver                           = '';
        $receiver_tel                       = '';
        $area                               = '';
        $area_section                       = '';
        $receive_address                    = '';
        $created_at                         = '';
        $ship_date                          = '';
        $ship_time                          = '';
        $order_type                         = '';
        $ship_status                        = '';
        $status                             = '';
        $shop_order_no                      = '';
        $shop_product_no                    = '';
        $shop_product_name                  = '';
        $shop_product_spec                  = '';
        $product_price                      = '';
        $product_price_cost                 = '';
        $original_count                     = '';
        $subtotal_price                     = '';
        $subtotal_cost                      = '';
        $order_price                        = '';
        $order_cost_products                = '';
        $freight                            = '';
        $freight_deduct                     = '';
        $bonus_points_deduct                = '';
        $coupon_deduct                      = '';
        $coupon_name                        = '';
        $shop_campaign_discount_code_deduct = '';
        $shop_campaign_discount_code_name   = '';
        $invite_no                          = '';
        $invite_no_deduct                   = '';
        $shop_campaign_order_type_deduct    = '';
        $shop_campaign_order_type_name      = '';
        $order_price                        = '';
        $customer_service_remark            = '';
        $pay_type_text                      = '';
        $return_count                       = '';
        $return_subtotal_price              = '';
        $return_subtotal_cost               = '';
        $return_price                       = '';
        $return_cost                        = '';
        $return_reason                      = '';
        $order_price_after_return           = '';
        $order_price_after_return           = '';
        $order_price_after_return           = '';
        $order_cost                         = '';
        if ($index == 0) {
          $first_purchase_check               = self::checkFirstShopOrderOfYearOfUser($shop_order) ? 'v' : '';
          $shop_order_user_id                 = $shop_order->user ? $shop_order->user->id : '';
          $orderer                            = $shop_order->orderer;
          $orderer_tel                        = $shop_order->orderer_tel;
          $orderer_email                      = $shop_order->orderer_email;
          $invoice_uniform_number             = $shop_order->invoice_uniform_number;
          $invoice_title                      = $shop_order->invoice_title;
          $receiver                           = $shop_order->receiver;
          $receiver_tel                       = $shop_order->receiver_tel;
          $area                               = $shop_order->area ? $shop_order->area->name : null;
          $area_section                       = $shop_order->area_section ? $shop_order->area_section->name : null;
          $receive_address                    = $shop_order->receive_address;
          $created_at                         = ShopHelper::getShopOrderCreatedAt($shop_order, $timezone);
          $ship_date                          = ShopHelper::getShopOrderShipDate($shop_order, $timezone);
          $ship_time                          = ShopHelper::getShopOrderShipTimeRange($shop_order, $timezone);
          $order_type                         = ShopHelper::getShopOrderOrderTypeTitle($shop_order);
          $ship_status                        = ShopHelper::getShopOrderShipStatusTitle($shop_order);
          $status                             = ShopHelper::getShopOrderStatusTitle($shop_order);
          $shop_order_no                      = $shop_order->no;
          $shop_product_no                    = $shop_product ? $shop_product->no : '';
          $shop_product_name                  = $order_product->name;
          $shop_product_spec                  = $order_product->spec;
          $product_price                      = $order_product->discount_price && config('stone.shop.discount_price') ? $order_product->discount_price : $order_product->price;
          $product_price_cost                 = $order_product->cost;
          $original_count                     = $order_product->original_count;
          $subtotal_price                     = $product_price * $order_product->original_count;
          $subtotal_cost                      = $order_product->cost * $order_product->original_count;
          $order_price                        = $shop_order->order_price;
          $order_cost_products                = $shop_order->order_cost_products;
          $freight                            = $shop_order->freight;
          $freight_deduct                     = $shop_order->freight_deduct;
          $bonus_points_deduct                = $shop_order->bonus_points_deduct;
          $shop_campaign_discount_code_deduct = $shop_order->shop_campaign_discount_code_deduct;
          $shop_campaign_discount_code_name   = $shop_order->shop_campaign_discount_code ? $shop_order->shop_campaign_discount_code->name : null;
          $invite_no                          = $shop_order->invite_no;
          $invite_no_deduct                   = $shop_order->invite_no_deduct;
          $order_price                        = $shop_order->order_price;
          $customer_service_remark            = $shop_order->customer_service_remark;
          $pay_type_text                      = $shop_order->pay_type_text;
          $return_count                       = ShopHelper::getProductReturnCount($order_product);
          $return_subtotal_price              = $return_count * $order_product->price;
          $return_subtotal_cost               = $return_count * $order_product->cost;
          $return_price                       = $shop_order->return_price;
          $return_cost                        = $shop_order->return_cost;
          $return_reason                      = $shop_order->return_reason;
          $order_price_after_return           = $shop_order->order_price_after_return;
          $order_price_after_return           = $shop_order->order_price_after_return;
          $order_price_after_return           = $shop_order->order_price_after_return;
          $order_cost                         = $shop_order->order_cost;
        } else {
          $shop_product_no       = $shop_product ? $shop_product->no : '';
          $shop_product_name     = $order_product->name;
          $shop_product_spec     = $order_product->spec;
          $product_price         = $order_product->discount_price && config('stone.shop.discount_price') ? $order_product->discount_price : $order_product->price;
          $product_price_cost    = $order_product->cost;
          $original_count        = $order_product->original_count;
          $subtotal_price        = $product_price * $order_product->original_count;
          $return_count          = ShopHelper::getProductReturnCount($order_product);
          $return_subtotal_price = $return_count * $order_product->price;
          $return_subtotal_cost  = $return_count * $order_product->cost;
        }
        $array[] = [
          $first_purchase_check,
          $shop_order_user_id,
          $orderer,
          $orderer_tel,
          $orderer_email,
          $invoice_uniform_number,
          $invoice_title,
          $receiver,
          $receiver_tel,
          $area,
          $area_section,
          $receive_address,
          $created_at,
          $ship_date,
          $ship_time,
          $order_type,
          $ship_status,
          $status,
          $shop_order_no,
          $shop_product_no,
          $shop_product_name,
          $shop_product_spec,
          $product_price,
          $product_price_cost,
          $original_count,
          $subtotal_price,
          $subtotal_cost,
          $order_price,
          $order_cost_products,
          $freight,
          $freight_deduct,
          $bonus_points_deduct,
          $coupon_deduct,
          $coupon_name,
          $shop_campaign_discount_code_deduct,
          $shop_campaign_discount_code_name,
          $invite_no,
          $invite_no_deduct,
          $shop_campaign_order_type_deduct,
          $shop_campaign_order_type_name,
          $order_price,
          $customer_service_remark,
          $pay_type_text,
          $return_count,
          $return_subtotal_price,
          $return_subtotal_cost,
          $return_price,
          $return_cost,
          $return_reason,
          $order_price_after_return,
          $order_price_after_return,
          $order_price_after_return,
          $order_cost,
        ];
      }
    }
    return $array;
  }

  public static function CancelCompleteRestoreStockCount($shop_order, $ori_shop_order)
  {
    $check = 0;
    if ($ori_shop_order->status == 'cancel' && $shop_order->status == 'cancel-complete') {
      $check = 1;
    }
    if (!$check) {
      return;
    }
    foreach ($shop_order->shop_return_records as $shop_return_record) {
      $shop_product = $shop_return_record->shop_product;
      $shop_product->stock_count += $shop_return_record->count;
      $shop_product->save();
    }
  }
}
