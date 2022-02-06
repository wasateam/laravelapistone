<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * 用來串接 LINE Pay 服務的動作
 *
 */
class LinePayHelper
{

  static $url_prod = 'https://api-pay.line.me';
  static $url_dev  = 'https://sandbox-api-pay.line.me';

  /**
   * 建立付款請求
   */
  public static function payment_request($shop_order = null, $confirm_url = null, $cancel_url = null, $currency = 'TWD')
  {
    // if (!$shop_order) {
    //   throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    // }
    if (!$confirm_url) {
      $confirm_url = env('WEB_URL') . '/linepay/payment/confirm';
    }
    if (!$cancel_url) {
      $cancel_url = env('WEB_URL') . '/linepay/payment/cancel';
    }
    $post_url = self::getBaseUrl() . '/v3/payments/request';
    error_log('$post_url');
    error_log($post_url);
    // $amount       = ShopHelper::getOrderAmount($shop_order->shop_order_shop_products);
    // $products     = self::getPackageProductsFromShopOrder($shop_order);
    $request_body = [
      'amount'       => 250,
      'currency'     => $currency,
      'orderId'      => '0002',
      'packages'     => [
        [
          'id'       => '0002',
          'amount'   => 250,
          'name'     => 'AAAAA',
          'products' => [
            [
              'name'     => 'asvasdvad',
              'quantity' => 1,
              'price'    => 250,
              'imageUrl' => '',
            ],
          ],
        ],
      ],
      'redirectUrls' => [
        'confirmUrl' => $confirm_url,
        'cancelUrl'  => $cancel_url,
      ],
    ];
    // $request_body = [
    //   'amount'       => $amount,
    //   'currency'     => $currency,
    //   'orderId'      => $shop_order->id,
    //   'packages'     => [
    //     [
    //       'id'       => $shop_order->id,
    //       'amount'   => $amount,
    //       'name'     => $shop_order->order_type,
    //       'products' => $products,
    //     ],
    //   ],
    //   'redirectUrls' => [
    //     'confirmUrl' => $confirm_url,
    //     'cancelUrl'  => $cancel_url,
    //   ],
    // ];
    $header   = self::get_request_header($request_body, '/v3/payments/request');
    $response = Http::withHeaders($header)
      ->post($post_url, $request_body);
      error_log('$response');
      error_log($response->body());
    return $response->json();
  }

  /**
   * 確認付款
   */
  public static function payment_confirm($transaction_id, $shop_order = null, $currency = 'TWD')
  {
    // $post_url     = env('LINE_PAY_API_URL') . '/v3/payments/' . $transaction_id . '/confirm';
    // $amount       = ShopHelper::getOrderAmount($shop_order->shop_order_shop_products);
    // $request_body = [
    //   'amount'   => $amount,
    //   'currency' => $currency,
    // ];
    // $header   = self::get_request_header($request_body);
    // $response = Http::withHeaders($header)
    //   ->post($post_url, $request_body);
    // return $response->json();

    # Test
    $post_url     = self::getBaseUrl() . '/v3/payments/' . $transaction_id . '/confirm';
    $amount       = 250;
    $request_body = [
      'amount'   => $amount,
      'currency' => $currency,
    ];
    error_log('$post_url');
    error_log($post_url);
    error_log('$request_body');
    error_log(json_encode($request_body));
    $header   = self::get_request_header($request_body, '/v3/payments/' . $transaction_id . '/confirm');
    $response = Http::withHeaders($header)
      ->post($post_url, $request_body);
    error_log($response->body());
    return $response->json();
  }

  /**
   * 取消付款
   */
  public static function payment_cancel($transaction_id)
  {

  }

  /**
   * 取得 POST 用內容
   */
  public static function get_post_signature($uri, $request_body, $nonce, $secret_key = null)
  {
    if (!$secret_key) {
      $secret_key = env('LINE_PAY_CHENNEL_SECRET_KEY');
    }
    $sig = base64_encode(hash_hmac('sha256', $secret_key . $uri . json_encode($request_body) . $nonce, $secret_key, true));
    return $sig;
  }

  /**
   * 取得 HEADER
   */
  // public static function get_request_header($nonce, $sig, $channel_id = null)
  public static function get_request_header($request_body, $uri, $channel_id = null)
  {
    if (!$channel_id) {
      $channel_id = env('LINE_PAY_CHANNEL_ID');
    }
    // $nonce = Carbon::now()->timestamp;
    # Test
    $nonce = Carbon::now()->timestamp;
    $sig   = self::get_post_signature($uri, $request_body, $nonce);
    return [
      'Content-Type'               => 'application/json',
      'X-LINE-ChannelId'           => $channel_id,
      'X-LINE-Authorization-Nonce' => $nonce,
      'X-LINE-Authorization'       => $sig,
    ];
  }

  public static function getPackageProductsFromShopOrder($shop_order)
  {
    $products = [];
    foreach ($shop_order->shop_order_shop_products as $shop_order_shop_product) {
      $price      = $shop_order_shop_product->discount_price ? $shop_order_shop_product->discount_price : $shop_order_shop_product->price;
      $products[] = [
        'name'     => $shop_order_shop_product->name,
        'quantity' => $shop_order_shop_product->count,
        'price'    => $price,
        'imageUrl' => '',
      ];
    }
    return $products;
  }

  public static function getBaseUrl()
  {
    $mode = env('THIRD_PARTY_PAYMENT_MODE');
    if ($mode == 'dev') {
      return self::$url_dev;
    } else {
      return self::$url_prod;
    }
  }
}
