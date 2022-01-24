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
  /**
   * 建立付款請求
   */
  public static function payment_request($confirm_url = null, $cancel_url = null)
  {
    if (!$confirm_url) {
      $confirm_url = env('WEB_URL') . '/linepay/payment/confirm';
    }
    if (!$cancel_url) {
      $confirm_url = env('WEB_URL') . '/linepay/payment/cancel';
    }
    $post_url     = env('LINE_PAY_API_URL') . '/v3/payments/request';
    $nonce        = Carbon::now()->timestamp;
    $request_body = [
      'amount'       => 250,
      'currency'     => 'TWD',
      'orderId'      => '00001',
      'packages'     => [
        [
          'id'       => 'Your package ID',
          'amount'   => 250,
          'name'     => 'Your package name',
          'products' => [
            [
              'name'     => 'Your product name',
              'quantity' => 1,
              'price'    => 250,
              'imageUrl' => 'https://yourname.com/assets/img/product.png',
            ],
          ],
        ],
      ],
      'redirectUrls' => [
        'confirmUrl' => $confirm_url,
        'cancelUrl'  => $cancel_url,
      ],
    ];
    $sig      = self::get_post_signature('/v3/payments/request', $request_body, $nonce);
    $header   = self::get_request_header($nonce, $sig);
    $response = Http::withHeaders($header)
      ->post($post_url, $request_body);

    return $response->json();
  }

  /**
   * 確認付款
   */
  public static function payment_confirm($transaction_id)
  {
    $post_url = env('LINE_PAY_API_URL') . '/v3/payments/' . $transaction_id . '/confirm';
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
  public static function get_request_header($nonce, $sig, $channel_id = null)
  {
    if (!$channel_id) {
      $channel_id = env('LINE_PAY_CHANNEL_ID');
    }
    return [
      'Content-Type'               => 'application/json',
      'X-LINE-ChannelId'           => $channel_id,
      'X-LINE-Authorization-Nonce' => $nonce,
      'X-LINE-Authorization'       => $sig,
    ];
  }
}
