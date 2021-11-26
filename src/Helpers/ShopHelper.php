<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Models\ShopCartProduct;
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
    if ($free_freight_price) {
      if ($shop_product_price_total >= $free_freight_price->price) {
        $freight = 0;
      }
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
    //更新訂單價格
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
}
