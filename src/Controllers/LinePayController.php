<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\LinePayHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;

/**
 * @group LinePay LINE Pay 相關動作
 *
 * shop_order 訂單
 */
class LinePayController extends Controller
{

  /**
   * Init 付款初始化
   * mode 模式 string Example:test
   * shop_order 訂單 ID id Example:1
   * source 來源 string Example: web,app
   * deep_link Deep Link for App Example: wasa://hahaha/aaaa
   *
   */
  public function payment_init(Request $request)
  {
    if (!$request->has('shop_order')) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('shop_order');
    }
    $shop_order = ShopOrder::find($request->shop_order);
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }
    $user = Auth::user();
    if ($shop_order->user->id != $user->id) {
      throw new \Wasateam\Laravelapistone\Exceptions\NotOwnerException('shop_order', $request->shop_order);
    }

    $shop_order = ShopHelper::setShopOrderNo($shop_order);

    $amount   = $shop_order->order_price;
    $packages = LinePayHelper::getLinePayPackageProductsFromShopOrder($shop_order);

    if ($request->has('mode') && $request->mode == 'test') {
      $confirm_url = env('WEB_URL') . '/ws_test/line_pay/confirm';
      $cancel_url  = env('WEB_URL') . '/ws_test/line_pay/cancel';
      $res         = LinePayHelper::payment_request(
        $amount,
        'TWD',
        $shop_order->no,
        $packages,
        $request->source,
        $confirm_url,
        $cancel_url
      );
    } else {
      $res = LinePayHelper::payment_request(
        $amount,
        'TWD',
        $shop_order->no,
        $packages,
        $request->source,
      );
    }

    if ($request->has('deep_link')) {
      LinePayHelper::createTransactionIdDeepLink($res['info']['transactionId'], $request->deep_link);
    }

    return response()->json([
      'web'           => $res['info']['paymentUrl']['web'],
      'app'           => $res['info']['paymentUrl']['app'],
      'transactionId' => strval($res['info']['transactionId']),
      'orderId'       => $shop_order->no,
    ], 200);
  }

  /**
   * 確認付款
   */
  public function payment_confirm(Request $request)
  {
    if (!$request->has('transaction_id')) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('transaction_id');
    }
    if (!$request->has('order_id')) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('order_id');
    }
    $shop_order = ShopOrder::where('no', $request->order_id)->first();
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }
    $shop_order = LinePayHelper::payment_confirm($request->transaction_id, $shop_order);

    return response()->json($shop_order, 200);
  }

  /**
   * 確認付款 for APP
   */
  public function payment_app_confirm(Request $request)
  {
    if (!$request->has('transaction_id')) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('transaction_id');
    }
    if (!$request->has('order_id')) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('order_id');
    }
    $shop_order = ShopOrder::where('no', $request->order_id)->first();
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }
    $shop_order = LinePayHelper::payment_confirm($request->transaction_id, $shop_order);

    $deep_link = LinePayHelper::getDeepLinkFromTransactionId($request->transaction_id);

    return response()->json($deep_link, 200);
  }

  /**
   * 取消付款
   */
  public function payment_cancel(Request $request)
  {
    LinePayHelper::payment_cancel($transaction_id);
  }
}
