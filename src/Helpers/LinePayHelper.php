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
  public static function payment_request($confirm_url, $cancel_url)
  {
    $uri          = env('LINEPAY_API_URL') . '/v3/payments/request';
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
      ->post($uri, $request_body);

    return $response->json();
  }

  /**
   * 取得 POST 用內容
   */
  public static function get_post_signature($uri, $request_body, $nonce, $linepay_secret_key = null)
  {
    if (!$linepay_secret_key) {
      $linepay_secret_key = env('LINEPAY_CHENNEL_SECRET_KEY');
    }
    $sig = base64_encode(hash_hmac('sha256', $linepay_secret_key . $uri . json_encode($request_body) . $nonce, $linepay_secret_key, true));
    return $sig;
  }

  /**
   * 取得 HEADER
   */
  public static function get_request_header($nonce, $sig, $channel_id = null)
  {
    if (!$channel_id) {
      $channel_id = env('LINEPAY_CHANNEL_ID');
    }
    return [
      'Content-Type'               => 'application/json',
      'X-LINE-ChannelId'           => $channel_id,
      'X-LINE-Authorization-Nonce' => $nonce,
      'X-LINE-Authorization'       => $sig,
    ];
  }
}
