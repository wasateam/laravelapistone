<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Wasateam\Laravelapistone\Helpers\CartHelper;
use Carbon\Carbon;

class EcpayController extends Controller
{
  public function get_inpay_merchant_init(Request $request)
  {
    if (!$request->has('shop_cart_products')) {
      return response()->json([
        'message' => 'shop_cart_products is required.',
      ], 400);
    }
    $shop_cart_products = $request->shop_cart_products;

    $order_price = CartHelper::getOrderPrice($shop_cart_products);
    $order_product_names = CartHelper::getOrderProductNames($shop_cart_products);
    
    $user             = Auth::user();
    $MerchantMemberID = config('stone.auth.uuid') ? $user->uuid : $user->id;
    $pay_data         = EcpayHelper::getInpayInitData([
      "OrderInfo"         => [
        "MerchantTradeNo"   => EcpayHelper::newMerchantTradeNo(),
        "MerchantTradeDate" => Carbon::now()->format('Y/m/d H:i:s'),
        "TotalAmount"       => $order_price,
        "ReturnURL"         => config('stone.thrid_party_payment.ecpay_inpay.insite_order_return_url'),
        "TradeDesc"         => "Trade",
        "ItemName"          => $order_product_names,
      ],
      "ConsumerInfo" => [
        "MerchantMemberID" => $MerchantMemberID,
        "Email"            => $request->has('orderer_email') ? $request->email : "",
        "Phone"            => $request->has('orderer_tel') ? $request->email : "",
        "Name"             => $request->has('orderer') ? $request->email : "",
      ],
    ]);
    $token = EcpayHelper::getMerchantToken($pay_data);
    return response()->json([
      'token'           => $token,
      'MerchantTradeNo' => $pay_data['OrderInfo']['MerchantTradeNo'],
    ], 200);
  }

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
}
