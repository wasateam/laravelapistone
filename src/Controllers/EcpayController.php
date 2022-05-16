<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;

/**
 * @group Ecpay 綠界站內付相關動作
 *
 * orderer 訂購人
 * orderer_tel 訂購人電話
 * orderer_email 訂購人信箱
 * shop_cart_products 訂單商品
 * PayToken 來自於綠界站內附的 PayToken
 * MerchantTradeNo 來自於 Get Inpay Merchant Init 產生的 MerchantTradeNo
 *
 * @authenticated
 */
class EcpayController extends Controller
{

  /**
   * Get Inpay Merchant Init 取得站內付初始化資訊
   *
   * @bodyParam shop_cart_products object 購物車商品 Example: [{"id":194,"name":"凱文試試看","count":6,"price":50,"discount_price":80,"order_type":"next-day","shop_product":{"id":21,"order_type":"next-day","name":"凱文試試看","spec":"超大份","price":50,"discount_price":80,"show_weight_capacity":0,"tax":"1","stock_count":76,"max_buyable_count":20,"uuid":"c6770757-ae22-4fe4-8a47-aaf29075585e"},"shop_product_spec":null}]
   * @bodyParam orderer string 訂購者 Example:orderer_name
   * @bodyParam orderer_tel string 訂購者電話 Example:0900-000-000
   * @bodyParam orderer_email string 訂購者mail Example:aa@aa.com
   *
   */

  public function get_inpay_merchant_init(Request $request)
  {
    if (!$request->has('shop_order')) {
      throw new \Wasateam\Laravelapistone\Exceptions\ParamRequiredException('shop_order');
    }
    $shop_order = $request->shop_order;
    $shop_order = ShopOrder::find($shop_order);
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order');
    }

    $shop_order = ShopHelper::setShopOrderNo($shop_order);

    $trade_date  = Carbon::now()->setTimezone('Asia/Taipei')->format('Y/m/d H:i:s');
    $order_price = $shop_order->order_price;
    // $order_price         = ShopHelper::getOrderAmount($shop_order->shop_order_shop_products);
    $order_product_names = ShopHelper::getOrderProductNames($shop_order->shop_order_shop_products);

    $user    = Auth::user();
    $user_id = config('stone.auth.uuid') && $user->uuid ? $user->uuid : $user->id;

    $pay_data = EcpayHelper::getInpayInitData($shop_order, $trade_date, $order_price, $order_product_names, $user_id, $shop_order->orderer_email, $shop_order->orderer_tel, $shop_order->orderer);
    $token    = EcpayHelper::getMerchantToken($pay_data);
    \Log::info('get_inpay_merchant_init');
    \Log::info($pay_data);
    return response()->json([
      'token'           => $token,
      'MerchantTradeNo' => $pay_data['OrderInfo']['MerchantTradeNo'],
      'origin'          => $pay_data,
    ], 200);
  }

  /**
   * Inpay Create Payment 站內付付款動作
   *
   * @bodyParam PayToken string No-example
   * @bodyParam MerchantTradeNo string No-example
   *
   */
  public function inpay_create_payment(Request $request)
  {
    if (!$request->has('PayToken')) {
      return response()->json([
        "message" => "PayToken is required.",
      ], 400);
    }
    if (!$request->has('MerchantTradeNo')) {
      return response()->json([
        "message" => "MerchantTradeNo is required.",
      ], 400);
    }
    $payment_res = EcpayHelper::createPayment($request->PayToken, $request->MerchantTradeNo);
    if (config('stone.third_party_payment.ecpay_inpay.threed')) {
      return response()->json([
        'ThreeDInfo' => $payment_res->ThreeDInfo,
        'origin'     => $payment_res,
      ]);
    } else {
      $shop_order = EcpayHelper::updateShopOrderFromEcpayPaymentRes($payment_res);
    }
  }

  /**
   *  (忽略) Callbacl Ecpay inpay order 站內付訂單回存狀態，僅供給綠界使用
   *
   */
  public function callback_ecpay_inpay_order(Request $request)
  {
    \Log::info('callback_ecpay_inpay_order');
    \Log::info($request->Data);
    $res = EcpayHelper::getDecryptData($request->Data);
    \Log::info(json_encode($res));
    if ($res->RtnCode != 1) {
      return '1|OK';
    }
    $shop_order = EcpayHelper::updateShopOrderFromEcpayOrderCallbackRes($request);
    return '1|OK';
  }

  /**
   *  (忽略) Callbacl Ecpay invoice notify 站內付訂單回存狀態，僅供給綠界使用
   *
   */
  public function callback_invoice_notify(Request $request)
  {
    \Log::info('callback_invoice_notify');
    \Log::info($request->all());
  }
}
