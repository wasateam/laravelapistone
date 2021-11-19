<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Wasateam\Laravelapistone\Models\ShopFreeShipping;
use Wasateam\Laravelapistone\Models\ShopOrder;
use Wasateam\Laravelapistone\Models\ShopOrderShopProduct;
use Wasateam\Laravelapistone\Models\ShopProduct;
use Wasateam\Laravelapistone\Models\ShopReturnRecord;

class ShopHelper
{
  public static function returnProductChangeCount($return_record_id)
  {
    //商品退訂->商品庫存、訂單商品數量重新計算
    $return_record      = ShopReturnRecord::where('id', $return_record_id)->first();
    $shop_product       = ShopProduct::where('id', $return_record->shop_product_id)->first();
    $shop_order_product = ShopOrderShopProduct::where('id', $return_record->shop_order_shop_product_id)->first();
    //加商品庫存
    $shop_product->stock_count = $shop_product->stock_count + $return_record->count;
    $shop_product->save();
    //減少訂單商品數量
    $shop_order_product->count = $shop_order_product->count - $return_record->count;
    $shop_order_product->save();
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

  public static function calculateShopOrderPrice($shop_order_id)
  {
    $today      = Carbon::now();
    $today_date = $today->format('Y-m-d');

    //計算訂單金額
    $shop_order = ShopOrder::where('id', $shop_order_id)->first();
    //商品價錢總和  - 沒有優惠價就使用售價
    $shop_order_shop_products = $shop_order->shop_order_shop_products;
    $shop_product_price_arr   = $shop_order_shop_products->map(function ($item) {
      return $item->discount_price ? $item->discount_price * $item->count : $item->price * $item->count;
    });
    $shop_product_price_total = Self::sum_price($shop_product_price_arr);

    //運費 default = 100
    $free_freight_price = ShopFreeShipping::where('end_date', '>=', $today_date)->where('start_date', '<=', $today_date)->first();
    $freight            = 100;
    if ($free_freight_price && $shop_product_price_total >= $free_freight_price) {
      $freight = 0;
    }

    $order_price = $shop_product_price_total + $freight;

    return [
      "products_price" => $shop_product_price_total,
      "freight"        => $freight,
      "order_price"    => $order_price,
    ];

  }

  public static function changeShopOrderPrice($shop_order_id)
  {
    $shop_order                 = ShopOrder::where('id', $shop_order_id)->first();
    $price_array                = Self::calculateShopOrderPrice($shop_order_id);
    $shop_order->products_price = $price_array['products_price'];
    $shop_order->freight        = $price_array['freight'];
    $shop_order->order_price    = $price_array['order_price'];
    $shop_order->save();
  }

  public static function sum_price($price_array)
  {
    $total = 0;
    foreach ($price_array as $price) {
      $total = $total + $price;
    }
    return $total;
  }

  public static function sameFreeDuration($start_date, $end_date)
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
      })->first();
    }
    if (isset($snap)) {
      return true;
    } else {
      return false;
    }
  }
}
