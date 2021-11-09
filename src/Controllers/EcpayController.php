<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Illuminate\Http\Request;

class EcpayController extends Controller
{
  public function get_inpay_merchant_init()
  {
    $pay_data = EcpayHelper::getInpayInitData();
    $token    = EcpayHelper::getMerchantToken($pay_data);
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
