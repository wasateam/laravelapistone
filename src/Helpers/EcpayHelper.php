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
      if ($res_data->RtnCode != '1') {
        throw new \Wasateam\Laravelapistone\Exceptions\EcpayException('getMerchantToken', $res_data->RtnCode, $res_data->RtnMsg);
      }
      return $res_data->Token;
    }
  }

  public static function getInpayInitData($shop_order_no, $trade_date, $order_price, $order_product_names, $user_id, $orderer_email, $orderer_tel, $orderer)
  {
    $pay_ways = [];

    if (config('stone.third_party_payment.ecpay_inpay.pay_way')) {
      if (config('stone.third_party_payment.ecpay_inpay.pay_way.credit_card')) {
        $pay_ways[] = 1;
      }
      if (config('stone.third_party_payment.ecpay_inpay.pay_way.credit_card_installment')) {
        $pay_ways[] = 2;
      }
      if (config('stone.third_party_payment.ecpay_inpay.pay_way.atm')) {
        $pay_ways[] = 3;
      }
      if (config('stone.third_party_payment.ecpay_inpay.pay_way.supermarket_code')) {
        $pay_ways[] = 4;
      }
      if (config('stone.third_party_payment.ecpay_inpay.pay_way.supermarket_barcode')) {
        $pay_ways[] = 5;
      }
    }

    $pay_ways_str = implode(',', $pay_ways);

    $initData = [
      "MerchantID"        => config('stone.third_party_payment.ecpay_inpay.merchant_id'),
      "RememberCard"      => 1,
      "PaymentUIType"     => 2,
      "ChoosePaymentList" => $pay_ways_str,
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
    return $res_data;
  }

  public static function updateShopOrderFromEcpayPaymentRes($payment_res)
  {
    $shop_order = ShopOrder::where('no', $payment_res->OrderInfo->MerchantTradeNo)->first();
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order', $payment_res->OrderInfo->MerchantTradeNo, 'no');
    }
    $shop_order->ecpay_merchant_id = $payment_res->MerchantID;
    if (isset($payment_res->OrderInfo->TradeStatus)) {
      if ($payment_res->OrderInfo->TradeStatus == 1) {
        $shop_order->pay_at      = Carbon::now();
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
    return $shop_order;
  }

  public static function createInvoice($data)
  {
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
    $DelayDay = 0,
    $Tsr
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
      "Tsr"                => $Tsr,
      // "NotifyURL"          => config('stone.invoice.notify_url'),
      'NotifyURL'          => 'https://laravelapiservice.showroom.wasateam.com/api/test/request_check',
    ];
  }

  public static function getInvoiceItemsFromShopOrder($shop_order)
  {
    $shop_cart_products = $shop_order->shop_order_shop_products;
    $items              = [];
    foreach ($shop_cart_products as $shop_cart_product) {
      $amount                  = ShopHelper::getOrderProductAmountPrice($shop_cart_product);
      $shop_cart_product_price = 0;
      if (isset($shop_cart_product->shop_product_spec)) {
        $shop_cart_product_price = config('stone.shop.discount_price') && $shop_cart_product->shop_product_spec['discount_price'] ? $shop_cart_product->shop_product_spec['discount_price'] : $shop_cart_product->shop_product_spec['price'];
      } else {
        $shop_cart_product_price = config('stone.shop.discount_price') && $shop_cart_product['discount_price'] ? $shop_cart_product['discount_price'] : $shop_cart_product['price'];
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
    $items[] = [
      "ItemName"    => '運費',
      "ItemCount"   => 1,
      "ItemWord"    => "件",
      "ItemPrice"   => $shop_order->freight,
      "ItemTaxType" => "1",
      "ItemAmount"  => $shop_order->freight,
      "ItemRemark"  => "",
    ];
    $items[] = [
      "ItemName"    => '活動折抵',
      "ItemCount"   => 1,
      "ItemWord"    => "個",
      "ItemPrice"   => $shop_order->campaign_deduct * -1,
      "ItemTaxType" => "1",
      "ItemAmount"  => $shop_order->campaign_deduct * -1,
      "ItemRemark"  => "",
    ];
    $items[] = [
      "ItemName"    => '紅利折抵',
      "ItemCount"   => 1,
      "ItemWord"    => "個",
      "ItemPrice"   => $shop_order->bonus_points_deduct * -1,
      "ItemTaxType" => "1",
      "ItemAmount"  => $shop_order->bonus_points_deduct * -1,
      "ItemRemark"  => "",
    ];
    return $items;
  }

  public static function updateShopOrderFromEcpayOrderCallbackRes($res)
  {
    $res_data   = self::getDecryptData($res['Data']);
    $shop_order = ShopOrder::where('no', $res_data->OrderInfo->MerchantTradeNo)->first();
    if (!$shop_order) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('shop_order', $res_data->OrderInfo->MerchantTradeNo, 'no');
    }
    $shop_order->ecpay_merchant_id = $res_data->MerchantID;
    if (isset($res_data->SimulatePaid)) {
      if ($res_data->SimulatePaid == 1) {
        $shop_order->pay_at      = Carbon::now();
        $shop_order->pay_status  = 'sumulate-paid';
        $shop_order->status      = 'established';
        $shop_order->ship_status = 'unfulfilled';
      }
    }
    if (isset($res_data->OrderInfo->TradeStatus)) {
      if ($res_data->OrderInfo->TradeStatus == 1) {
        $shop_order->pay_at      = Carbon::now();
        $shop_order->pay_status  = 'paid';
        $shop_order->status      = 'established';
        $shop_order->ship_status = 'unfulfilled';
      } else if ($res_data->OrderInfo->TradeStatus == 0) {
        $shop_order->pay_status  = 'not-paid';
        $shop_order->status      = 'not-established';
        $shop_order->ship_status = null;
      }
    }
    $shop_order->ecpay_trade_no   = $res_data->OrderInfo->TradeNo;
    $shop_order->pay_type         = $res_data->OrderInfo->PaymentType;
    $shop_order->ecpay_charge_fee = $res_data->OrderInfo->ChargeFee;
    if (isset($res_data->CVSInfo)) {
      $shop_order->csv_pay_from    = $res_data->CVSInfo->PayFrom;
      $shop_order->csv_payment_no  = $res_data->CVSInfo->PaymentNo;
      $shop_order->csv_payment_url = $res_data->CVSInfo->PaymentURL;
    }
    if (isset($res_data->BarcodeInfo)) {
      $shop_order->barcode_pay_from = $res_data->BarcodeInfo->PayFrom;
    }
    if (isset($res_data->ATMInfo)) {
      $shop_order->atm_acc_bank = $res_data->ATMInfo->ATMAccBank;
      $shop_order->atm_acc_no   = $res_data->ATMInfo->ATMAccNo;
    }
    if (isset($res_data->CardInfo)) {
      $shop_order->card_auth_code            = $res_data->CardInfo->AuthCode;
      $shop_order->card_gwsr                 = $res_data->CardInfo->Gwsr;
      $shop_order->card_process_at           = $res_data->CardInfo->ProcessDate;
      $shop_order->card_amount               = $res_data->CardInfo->Amount;
      $shop_order->card_pre_six_no           = $res_data->CardInfo->Card6No;
      $shop_order->card_last_four_no         = $res_data->CardInfo->Card4No;
      $shop_order->card_stage                = $res_data->CardInfo->Stage;
      $shop_order->card_stast                = $res_data->CardInfo->Stast;
      $shop_order->card_staed                = $res_data->CardInfo->Staed;
      $shop_order->card_red_dan              = $res_data->CardInfo->RedDan;
      $shop_order->card_red_de_amt           = $res_data->CardInfo->RedDeAmt;
      $shop_order->card_red_ok_amt           = $res_data->CardInfo->RedOkAmt;
      $shop_order->card_red_yet              = $res_data->CardInfo->RedYet;
      $shop_order->card_period_type          = $res_data->CardInfo->PeriodType;
      $shop_order->card_frequency            = $res_data->CardInfo->Frequency;
      $shop_order->card_exec_times           = $res_data->CardInfo->ExecTimes;
      $shop_order->card_period_amount        = $res_data->CardInfo->PeriodAmount;
      $shop_order->card_total_success_times  = $res_data->CardInfo->TotalSuccessTimes;
      $shop_order->card_total_success_amount = $res_data->CardInfo->TotalSuccessAmount;
    }
    $shop_order->save();
    return $shop_order;
  }
}
