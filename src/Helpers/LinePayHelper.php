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
  public static function payment_request(
    $amount = null,
    $currency = 'TWD',
    $orderId = null,
    $packages = null,
    $confirm_url = null,
    $cancel_url = null
  ) {
    if (!$confirm_url) {
      $confirm_url = env('WEB_URL') . '/line_pay/payment/confirm';
    }
    if (!$cancel_url) {
      $cancel_url = env('WEB_URL') . '/line_pay/payment/cancel';
    }
    $post_url     = self::getBaseUrl() . '/v3/payments/request';
    $request_body = [
      'amount'       => $amount,
      'currency'     => $currency,
      'orderId'      => $orderId,
      'packages'     => $packages,
      'redirectUrls' => [
        'confirmUrl' => $confirm_url,
        'cancelUrl'  => $cancel_url,
      ],
    ];
    $header   = self::get_request_header($request_body, '/v3/payments/request');
    $response = Http::withHeaders($header)
      ->post($post_url, $request_body);
    $res_json = $response->json();


    if ($res_json['returnCode'] != '0000') {
      throw new \Wasateam\Laravelapistone\Exceptions\LinePayException(
        'payment_request',
        $res_json['returnCode'],
        $res_json['returnMessage']
      );
    }

    return $response->json();
  }

  /**
   * 確認付款
   */
  public static function payment_confirm($transaction_id, $shop_order = null, $currency = 'TWD')
  {
    $post_url = self::getBaseUrl() . '/v3/payments/' . $transaction_id . '/confirm';
    // $amount       = ShopHelper::getOrderAmount($shop_order->shop_order_shop_products);
    $amount       = $shop_order->order_price;
    $request_body = [
      'amount'   => $amount,
      'currency' => $currency,
    ];
    $header   = self::get_request_header($request_body, '/v3/payments/' . $transaction_id . '/confirm');
    $response = Http::withHeaders($header)
      ->post($post_url, $request_body);
    $res_json = $response->json();

    if ($res_json['returnCode'] != '0000') {
      throw new \Wasateam\Laravelapistone\Exceptions\LinePayException(
        'payment_confirm',
        $res_json['returnCode'],
        $res_json['returnMessage']
      );
    }

    $shop_order->pay_at      = Carbon::now();
    $shop_order->pay_status  = 'paid';
    $shop_order->status      = 'established';
    $shop_order->ship_status = 'unfulfilled';
    $shop_order->pay_type    = 'line_pay';
    $shop_order->save();

    return $shop_order;
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

  public static function getLinePayPackageProductsFromShopOrder($shop_order)
  {
    $products = [];
    foreach ($shop_order->shop_order_shop_products as $shop_order_shop_product) {
      $price      = $shop_order_shop_product->discount_price && config('stone.shop.discount_price') ? $shop_order_shop_product->discount_price : $shop_order_shop_product->price;
      $products[] = [
        'name'     => $shop_order_shop_product->name,
        'quantity' => $shop_order_shop_product->count,
        'price'    => $price,
        'imageUrl' => '',
      ];
    }
    $products[] = [
      'name'     => '活動折抵',
      'quantity' => 1,
      'price'    => $shop_order->campaign_deduct * -1,
      'imageUrl' => '',
    ];
    $products[] = [
      'name'     => '紅利折抵',
      'quantity' => 1,
      'price'    => $shop_order->bonus_points_deduct * -1,
      'imageUrl' => '',
    ];
    $products[] = [
      'name'     => '運費',
      'quantity' => 1,
      'price'    => $shop_order->freight,
      'imageUrl' => '',
    ];
    return [
      [
        'id'       => $shop_order->no,
        'amount'   => $shop_order->order_price,
        'name'     => $shop_order->no,
        'products' => $products,
      ],
    ];
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
