<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Wasateam\Laravelapistone\Helpers\EcpayHelper;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;

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

    $order_price         = ShopHelper::getOrderAmount($shop_cart_products);
    $order_product_names = ShopHelper::getOrderProductNames($shop_cart_products);
    $order_type          = ShopHelper::getOrderType($shop_cart_products);

    $user             = Auth::user();
    $MerchantMemberID = config('stone.auth.uuid') && $user->uuid ? $user->uuid : $user->id;
    if (config('stone.shop.custom_shop_order')) {
      $shop_order_no = \App\Helpers\AppHelper::newShopOrderNo($order_type);
    } else {
      $shop_order_no = ShopHelper::newShopOrderNo();
    }
    $pay_data = EcpayHelper::getInpayInitData([
      "OrderInfo"    => [
        "MerchantTradeNo"   => $shop_order_no,
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
    ], $order_type);
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

  public function callback_ecpay_inpay_order(Request $request)
  {
    $res = EcpayHelper::getDecryptData($request->Data);
    if ($res->RtnMsg != 1) {
      return '1|OK';
    }
    $shop_order = ShopOrder::where('no', $res->OrderInfo->MerchantTradeNo);
    if (!$shop_order) {
      return;
    }
    $shop_order->ecpay_merchant_id = $res->MerchantID;
    if ($res->SimulatePaid == 1) {
      $shop_order->pay_status = 'sumulate-paid';
    }
    if ($res->OrderInfo->TradeStatus == 1) {
      $shop_order->pay_status = 'paid';
    } else if ($res->OrderInfo->TradeStatus == 0) {
      $shop_order->pay_status = 'not-paid';
    }
    $shop_order->ecpay_trade_no            = $res->OrderInfo->TradeNo;
    $shop_order->pay_type                  = $res->OrderInfo->PaymentType;
    $shop_order->ecpay_charge_fee          = $res->OrderInfo->ChargeFee;
    $shop_order->csv_pay_from              = $res->CVSInfo->PayFrom;
    $shop_order->csv_payment_no            = $res->CVSInfo->PaymentNo;
    $shop_order->csv_payment_url           = $res->CVSInfo->PaymentURL;
    $shop_order->barcode_pay_from          = $res->BarcodeInfo->PayFrom;
    $shop_order->atm_acc_bank              = $res->ATMInfo->ATMAccBank;
    $shop_order->atm_acc_no                = $res->ATMInfo->ATMAccNo;
    $shop_order->card_auth_code            = $res->CardInfo->AuthCode;
    $shop_order->card_gwsr                 = $res->CardInfo->Gwsr;
    $shop_order->card_process_at           = $res->CardInfo->ProcessDate;
    $shop_order->card_amount               = $res->CardInfo->Amount;
    $shop_order->card_pre_six_no           = $res->CardInfo->Card6No;
    $shop_order->card_last_four_no         = $res->CardInfo->Card4No;
    $shop_order->card_stage                = $res->CardInfo->Stage;
    $shop_order->card_stast                = $res->CardInfo->Stast;
    $shop_order->card_staed                = $res->CardInfo->Staed;
    $shop_order->card_red_dan              = $res->CardInfo->RedDan;
    $shop_order->card_red_de_amt           = $res->CardInfo->RedDeAmt;
    $shop_order->card_red_ok_amt           = $res->CardInfo->RedOkAmt;
    $shop_order->card_red_yet              = $res->CardInfo->RedYet;
    $shop_order->card_period_type          = $res->CardInfo->PeriodType;
    $shop_order->card_frequency            = $res->CardInfo->Frequency;
    $shop_order->card_exec_times           = $res->CardInfo->ExecTimes;
    $shop_order->card_period_amount        = $res->CardInfo->PeriodAmount;
    $shop_order->card_total_success_times  = $res->CardInfo->TotalSuccessTimes;
    $shop_order->card_total_success_amount = $res->CardInfo->TotalSuccessAmount;
    $shop_order->save();
    return '1|OK';
  }
}
