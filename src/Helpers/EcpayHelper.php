<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ShopHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;

class EcpayHelper
{
  public static function getEncryptData($data, $type = 'payment')
  {
    $invoice_mode = config('stone.invoice.mode');
    if ($type == 'invoice' && $invoice_mode == 'dev') {
      $hash_key = 'ejCk326UnaZWKisg';
      $hash_iv  = 'q9jcZX8Ib9LM8wYk';
    } else {
      $hash_key = config('stone.third_party_payment.ecpay_inpay.hash_key');
      $hash_iv  = config('stone.third_party_payment.ecpay_inpay.hash_iv');
    }
    $data_json    = json_encode($data);
    $data_encode  = urlencode($data_json);
    $data_encrypt = openssl_encrypt($data_encode, 'aes-128-cbc', $hash_key, $options = 0, $hash_iv);
    return $data_encrypt;
  }

  public static function getDecryptData($data_encrypt, $type = 'payment')
  {
    $invoice_mode = config('stone.invoice.mode');
    if ($type == 'invoice' && $invoice_mode == 'dev') {
      $hash_key = 'ejCk326UnaZWKisg';
      $hash_iv  = 'q9jcZX8Ib9LM8wYk';
    } else {
      $hash_key = config('stone.third_party_payment.ecpay_inpay.hash_key');
      $hash_iv  = config('stone.third_party_payment.ecpay_inpay.hash_iv');
    }
    $data_decrypt = openssl_decrypt($data_encrypt, 'aes-128-cbc', $hash_key, $options = 0, $hash_iv);
    $data_decode  = urldecode($data_decrypt);
    return json_decode($data_decode);
  }

  public static function getMerchantToken($data)
  {
    error_log('getMerchantToken');
    $mode         = env('THIRD_PARTY_PAYMENT_MODE');
    $data_encrypt = self::getEncryptData($data);
    if ($mode == 'dev') {
      $post_url = 'https://ecpg-stage.ecpay.com.tw/Merchant/GetTokenbyTrade';
    } else {
      $post_url = "https://ecpg.ecpay.com.tw/Merchant/GetTokenbyTrade";
    }

    $res = Http::withHeaders([])->post($post_url, [
      "MerchantID" => config('stone.third_party_payment.ecpay_inpay.merchant_id'),
      "RqHeader"   => [
        "Timestamp" => Carbon::now()->timestamp,
        "Revision"  => "1.3.15",
      ],
      "Data"       => $data_encrypt,
    ]);
    if ($res->status() == '200') {
      $res_json = $res->json();
      $res_data = self::getDecryptData($res_json['Data']);
      error_log(json_encode($res_data));
      if ($res_data->RtnCode != '1') {
        throw new \Wasateam\Laravelapistone\Exceptions\EcpayException('getMerchantToken', $res_data->RtnCode, $res_data->RtnMsg);
      }
      return $res_data->Token;
    }
  }

  public static function getInpayInitData($shop_order_no, $trade_date, $order_price, $order_product_names, $user_id, $orderer_email, $orderer_tel, $orderer)
  {
    $initData = [
      "MerchantID"        => config('stone.third_party_payment.ecpay_inpay.merchant_id'),
      "RememberCard"      => 1,
      "PaymentUIType"     => 2,
      "ChoosePaymentList" => "1,2,3,4,5",
      "OrderInfo"         => [
        "MerchantTradeNo"   => $shop_order_no,
        "MerchantTradeDate" => $trade_date,
        "TotalAmount"       => $order_price,
        "ReturnURL"         => config('stone.third_party_payment.ecpay_inpay.insite_order_return_url'),
        "TradeDesc"         => "Trade",
        "ItemName"          => $order_product_names,
      ],
      "CardInfo"          => [
        "OrderResultURL"    => config('stone.third_party_payment.ecpay_inpay.cardinfo.order_return_url'),
        "CreditInstallment" => "3",
      ],
      "ATMInfo"           => [
        "ExpireDate" => 3,
      ],
      "CVSInfo"           => [
        "StoreExpireDate" => 10080,
        "CVSCode"         => "CVS",
      ],
      "BarcodeInfo"       => [
        "StoreExpireDate" => 7,
      ],
      "ConsumerInfo"      => [
        "MerchantMemberID" => $user_id,
        "Email"            => $orderer_email,
        "Phone"            => $orderer_tel,
        "Name"             => $orderer,
      ],
    ];
    return $initData;
  }

  public static function createPayment($PayToken, $MerchantTradeNo)
  {
    error_log('createPayment');
    $mode         = env('THIRD_PARTY_PAYMENT_MODE');
    $data_encrypt = self::getEncryptData([
      "MerchantID"      => config('stone.third_party_payment.ecpay_inpay.merchant_id'),
      "PayToken"        => $PayToken,
      "MerchantTradeNo" => $MerchantTradeNo,
    ]);
    if ($mode == 'dev') {
      $post_url = 'https://ecpg-stage.ecpay.com.tw/Merchant/CreatePayment';
    } else {
      $post_url = "https://ecpg.ecpay.com.tw/Merchant/CreatePayment";
    }

    $res = Http::withHeaders([])->post($post_url, [
      "MerchantID" => config('stone.third_party_payment.ecpay_inpay.merchant_id'),
      "RqHeader"   => [
        "Timestamp" => Carbon::now()->timestamp,
        "Revision"  => "1.3.15",
      ],
      "Data"       => $data_encrypt,
    ]);
    if (!$res->status() == '200') {
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayException('createPayment', '-', 'status not 200');
    }
    $res_json = $res->json();
    $res_data = self::getDecryptData($res_json['Data']);
    if ($res_data->RtnCode != '1') {
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayException('createPayment', $res_data->RtnCode, $res_data->RtnMsg);
    }
    error_log(json_encode($res_data));
    return $res_data;
  }

  public static function updateShopOrderFromEcpayPaymentRes($payment_res)
  {
    error_log('updateShopOrderFromEcpayPaymentRes');
    $shop_order = ShopOrder::where('no', $payment_res->OrderInfo->MerchantTradeNo)->first();
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order', $payment_res->OrderInfo->MerchantTradeNo, 'no');
    }
    $shop_order->ecpay_merchant_id = $payment_res->MerchantID;
    if (isset($payment_res->OrderInfo->TradeStatus)) {
      if ($payment_res->OrderInfo->TradeStatus == 1) {
        $shop_order->pay_status  = 'paid';
        $shop_order->status      = 'established';
        $shop_order->ship_status = 'unfulfilled';
      } else if ($payment_res->OrderInfo->TradeStatus == 0) {
        $shop_order->pay_status  = 'not-paid';
        $shop_order->status      = 'not-established';
        $shop_order->ship_status = null;
      }
    }
    $shop_order->ecpay_trade_no   = $payment_res->OrderInfo->TradeNo;
    $shop_order->pay_type         = $payment_res->OrderInfo->PaymentType;
    $shop_order->ecpay_charge_fee = $payment_res->OrderInfo->ChargeFee;
    $shop_order->save();
    error_log('$shop_order');
    error_log(json_encode($shop_order));
    return $shop_order;
  }

  public static function createInvoice($data)
  {
    error_log('createInvoice');
    $mode         = config('stone.invoice.mode');
    $data_encrypt = self::getEncryptData($data, 'invoice');
    if ($mode == 'dev') {
      $post_url = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/Issue';
    } else {
      $post_url = "https://einvoice.ecpay.com.tw/B2CInvoice/Issue";
    }
    $res = Http::withHeaders([])->post($post_url, [
      "MerchantID" => config('stone.invoice.ecpay.merchant_id'),
      "RqHeader"   => [
        "Timestamp" => Carbon::now()->timestamp,
        "Revision"  => "1.3.15",
      ],
      "Data"       => $data_encrypt,
    ]);

    if (!$res->status() == '200') {
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayInvoiceException('createInvoice', '-', 'status not 200');
    }

    $res_json = $res->json();
    if ($res_json['TransCode'] != '1') {
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayInvoiceException('createInvoice', null, null, $res_json['TransCode'], $res_json['TransMsg']);
    }
    $res_data = self::getDecryptData($res_json['Data'], 'invoice');
    if ($res_data->RtnCode != '1') {
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayInvoiceException('createInvoice', $res_data->RtnCode, $res_data->RtnMsg);
    }
    return $res_data;
  }

  public static function newRelateNumber()
  {
    $time = Carbon::now()->timestamp;
    $str  = Str::random(8);
    return "{$time}{$str}";
  }

  // public static function getInvoicePostData($data)
  public static function getInvoicePostData(
    $CustomerID,
    $CustomerIdentifier,
    $CustomerName,
    $CustomerAddr,
    $CustomerPhone,
    $CustomerEmail,
    $Print = "0",
    $Donation = "0",
    $CarrierType,
    $CarrierNum,
    $TaxType,
    $SalesAmount,
    $Items
  ) {

    $post_data = [
      "MerchantID"         => config('stone.invoice.ecpay.merchant_id'),
      "RelateNumber"       => self::newRelateNumber(),
      "CustomerID"         => $CustomerID,
      "CustomerIdentifier" => $CustomerIdentifier,
      "CustomerName"       => $CustomerName,
      "CustomerAddr"       => $CustomerAddr,
      "CustomerPhone"      => $CustomerPhone,
      "CustomerEmail"      => $CustomerEmail,
      "ClearanceMark"      => "1",
      "Print"              => $Print,
      "Donation"           => $Donation,
      "LoveCode"           => "",
      "CarrierType"        => $CarrierType,
      "CarrierNum"         => $CarrierNum,
      "TaxType"            => $TaxType,
      "SalesAmount"        => $SalesAmount,
      "InvoiceRemark"      => "",
      "InvType"            => "07",
      "vat"                => "1",
      "Items"              => $Items,
    ];

    return $post_data;
  }

  public static function createDelayInvoice($data)
  {
    error_log('createDelayInvoice');
    $mode         = config('stone.invoice.mode');
    $data_encrypt = self::getEncryptData($data, 'invoice');
    if ($mode == 'dev') {
      $post_url = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/DelayIssue';
    } else {
      $post_url = "https://einvoice.ecpay.com.tw/B2CInvoice/DelayIssue";
    }
    $res = Http::withHeaders([])->post($post_url, [
      "MerchantID" => config('stone.invoice.ecpay.merchant_id'),
      "RqHeader"   => [
        "Timestamp" => Carbon::now()->timestamp,
        "Revision"  => "1.3.15",
      ],
      "Data"       => $data_encrypt,
    ]);

    if (!$res->status() == '200') {
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayInvoiceException('createDelayInvoice', '-', 'status not 200');
    }

    $res_json = $res->json();
    if ($res_json['TransCode'] != '1') {
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayInvoiceException('createDelayInvoice', null, null, $res_json['TransCode'], $res_json['TransMsg']);
    }
    $res_data = self::getDecryptData($res_json['Data'], 'invoice');
    if ($res_data->RtnCode != '1') {
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayInvoiceException('createDelayInvoice', $res_data->RtnCode, $res_data->RtnMsg);
    }
    return $res_data;
  }

  public static function getDelayInvoicePostData(
    $CustomerID,
    $CustomerIdentifier,
    $CustomerName,
    $CustomerAddr,
    $CustomerPhone,
    $CustomerEmail,
    $Print = "0",
    $Donation = "0",
    $CarrierType,
    $CarrierNum,
    $TaxType,
    $SalesAmount,
    $Items,
    $DelayDay = 0
  ) {
    return [
      "MerchantID"         => config('stone.invoice.ecpay.merchant_id'),
      "RelateNumber"       => self::newRelateNumber(),
      "CustomerID"         => $CustomerID,
      "CustomerIdentifier" => $CustomerIdentifier,
      "CustomerName"       => $CustomerName,
      "CustomerAddr"       => $CustomerAddr,
      "CustomerPhone"      => $CustomerPhone,
      "CustomerEmail"      => $CustomerEmail,
      "ClearanceMark"      => "1",
      "Print"              => $Print,
      "Donation"           => $Donation,
      "LoveCode"           => "",
      "CarrierType"        => $CarrierType,
      "CarrierNum"         => $CarrierNum,
      "TaxType"            => $TaxType,
      "SalesAmount"        => $SalesAmount,
      "InvoiceRemark"      => "",
      "InvType"            => "07",
      "vat"                => "1",
      "Items"              => $Items,
      "DelayFlag"          => "1",
      "DelayDay"           => $DelayDay,
      "PayType"            => '2',
      "PayAct"             => 'ECPAY',
      "NotifyURL"          => '',
    ];
  }

  public static function getInvoiceItemsFromShopCartProducts($shop_cart_products)
  {
    $items = [];
    foreach ($shop_cart_products as $shop_cart_product) {
      $amount                  = ShopHelper::getOrderProductAmountPrice($shop_cart_product);
      $shop_cart_product_price = 0;
      if (isset($shop_cart_product->shop_product_spec)) {
        $shop_cart_product_price = $shop_cart_product->shop_product_spec['discount_price'] ? $shop_cart_product->shop_product_spec['discount_price'] : $shop_cart_product->shop_product_spec['price'];
      } else {
        $shop_cart_product_price = $shop_cart_product['discount_price'] ? $shop_cart_product['discount_price'] : $shop_cart_product['price'];
      }
      $items[] = [
        // "ItemSeq"     => 1,
        "ItemName"    => $shop_cart_product['name'],
        "ItemCount"   => $shop_cart_product['count'],
        "ItemWord"    => "件",
        "ItemPrice"   => $shop_cart_product_price,
        "ItemTaxType" => "1",
        "ItemAmount"  => $amount,
        "ItemRemark"  => "",
      ];
    }
    return $items;
  }

  public static function updateShopOrderFromEcpayOrderCallbackRes($res)
  {
    error_log('updateShopOrderFromEcpayOrderCallbackRes');
    $shop_order = ShopOrder::where('no', $res->OrderInfo->MerchantTradeNo)->first();
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order', $res->OrderInfo->MerchantTradeNo, 'no');
    }
    $shop_order->ecpay_merchant_id = $res->MerchantID;
    if (isset($res->SimulatePaid)) {
      if ($res->SimulatePaid == 1) {
        $shop_order->pay_status  = 'sumulate-paid';
        $shop_order->status      = 'established';
        $shop_order->ship_status = 'unfulfilled';
      }
    }
    if (isset($res->OrderInfo->TradeStatus)) {
      if ($res->OrderInfo->TradeStatus == 1) {
        $shop_order->pay_status  = 'paid';
        $shop_order->status      = 'established';
        $shop_order->ship_status = 'unfulfilled';
      } else if ($res->OrderInfo->TradeStatus == 0) {
        $shop_order->pay_status  = 'not-paid';
        $shop_order->status      = 'not-established';
        $shop_order->ship_status = null;
      }
    }
    $shop_order->ecpay_trade_no   = $res->OrderInfo->TradeNo;
    $shop_order->pay_type         = $res->OrderInfo->PaymentType;
    $shop_order->ecpay_charge_fee = $res->OrderInfo->ChargeFee;
    if (isset($res->CVSInfo)) {
      $shop_order->csv_pay_from    = $res->CVSInfo->PayFrom;
      $shop_order->csv_payment_no  = $res->CVSInfo->PaymentNo;
      $shop_order->csv_payment_url = $res->CVSInfo->PaymentURL;
    }
    if (isset($res->BarcodeInfo)) {
      $shop_order->barcode_pay_from = $res->BarcodeInfo->PayFrom;
    }
    if (isset($res->ATMInfo)) {
      $shop_order->atm_acc_bank = $res->ATMInfo->ATMAccBank;
      $shop_order->atm_acc_no   = $res->ATMInfo->ATMAccNo;
    }
    if (isset($res->CardInfo)) {
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
    }
    $shop_order->save();
    return $shop_order;
  }
}
