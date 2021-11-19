<?php

namespace Wasateam\Laravelapistone\Helpers;

class CartHelper
{
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
}
