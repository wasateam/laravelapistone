<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

/**
 * @group Ecpay 綠界站內付相關動作
 * 
 * orderer 訂購人
 * orderer_tel 訂購人電話
 * orderer_email 訂購人信箱
 * shop_cart_products 訂單商品
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

  //  @Q@ dev mode get fail
  public function get_inpay_merchant_init(Request $request)
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
