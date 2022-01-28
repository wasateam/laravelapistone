<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

/**
 * @group Shop 商城相關動作
 *
 * @authenticated
 */
class ShopController extends Controller
{

  /**
   * Get Inpay Merchant Init 取得站內付初始化資訊
   *
   */
  public function get_ecpay_inpay_merchant_init(Request $request)
  {
    if (!$request->has('shop_cart_products')) {
      throw new \Wasateam\Laravelapistone\Exceptions\ParamRequiredException('shop_cart_products');
    }
    $shop_cart_products = $request->shop_cart_products;

    $trade_date          = Carbon::now()->format('Y/m/d H:i:s');
    $order_price         = ShopHelper::getOrderAmount($shop_cart_products);
    $order_product_names = ShopHelper::getOrderProductNames($shop_cart_products);
    $order_type          = ShopHelper::getOrderType($shop_cart_products);

    $user    = Auth::user();
    $user_id = config('stone.auth.uuid') && $user->uuid ? $user->uuid : $user->id;
    if (config('stone.shop.custom_shop_order')) {
      $shop_order_no = \App\Helpers\AppHelper::newShopOrderNo($order_type);
    } else {
      $shop_order_no = ShopHelper::newShopOrderNo();
    }

    $orderer_email = $request->has('orderer_email') ? $request->orderer_email : "";
    $orderer_tel   = $request->has('orderer_tel') ? $request->orderer_tel : "";
    $orderer       = $request->has('orderer') ? $request->orderer : "";

    $pay_data = EcpayHelper::getInpayInitData($shop_order_no, $trade_date, $order_price, $order_product_names, $user_id, $orderer_email, $orderer_tel, $orderer);

    $token = EcpayHelper::getMerchantToken($pay_data);
    return response()->json([
      'token'           => $token,
      'MerchantTradeNo' => $pay_data['OrderInfo']['MerchantTradeNo'],
    ], 200);
  }

  /**
   * Inpay Create Payment 站內付付款動作
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
    EcpayHelper::createPayment($request->PayToken, $request->MerchantTradeNo);
  }

  /**
   *  Callbacl Ecpay inpay order 站內付訂單回存狀態
   *
   */
  public function callback_ecpay_inpay_order(Request $request)
  {
    $res = EcpayHelper::getDecryptData($request->Data);
    if ($res->RtnCode != 1) {
      return '1|OK';
    }
    $shop_order = EcpayHelper::updateShopOrderFromEcpayOrderCallbackRes($request);
    return '1|OK';
  }
}
