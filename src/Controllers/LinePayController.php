<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\LinePayHelper;

/**
 * @group LinePay LINE Pay 相關動作
 *
 * @authenticated
 */
class LinePayController extends Controller
{

  /**
   * Init 付款初始化
   */
  public function payment_init(Request $request)
  {
    if (!$request->has('shop_cart_products')) {
      throw new \Wasateam\Laravelapistone\Exceptions\ParamRequiredException('shop_cart_products');
    }
    if ($request->has('mode') && $request->mode == 'test') {
      $confirm_url = env('WEB_URL') . '/ws_test/linepay/payment/confirm';
      $cancel_url  = env('WEB_URL') . '/ws_test/linepay/payment/cancel';
      return LinePayHelper::payment_request();
    } else {
      return LinePayHelper::payment_request();
    }
  }

  /**
   * 確認付款
   */
  public function payment_confirm($transaction_id)
  {
    LinePayHelper::payment_confirm($transaction_id);
  }

  /**
   * 取消付款
   */
  public function payment_cancel($transaction_id)
  {
    LinePayHelper::payment_cancel($transaction_id);
  }
}
