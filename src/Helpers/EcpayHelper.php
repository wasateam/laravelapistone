<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

class EcpayHelper
{
  # 取得加密檔案
  public static function getEncryptData($data, $type = 'payment')
  {
    $invoice_mode = config('stone.invoice.mode');
    if ($type == 'invoice' && $invoice_mode == 'dev') {
      $hash_key = 'ejCk326UnaZWKisg';
      $hash_iv  = 'q9jcZX8Ib9LM8wYk';
    } else {
      $hash_key = config('stone.thrid_party_payment.ecpay_inpay.hash_key');
      $hash_iv  = config('stone.thrid_party_payment.ecpay_inpay.hash_iv');
    }
    $data_json    = json_encode($data);
    $data_encode  = urlencode($data_json);
    $data_encrypt = openssl_encrypt($data_encode, 'aes-128-cbc', $hash_key, $options = 0, $hash_iv);
    return $data_encrypt;
  }

  # 取得解密檔案
  public static function getDecryptData($data_encrypt, $type = 'payment')
  {
    $invoice_mode = config('stone.invoice.mode');
    if ($type == 'invoice' && $invoice_mode == 'dev') {
      $hash_key = 'ejCk326UnaZWKisg';
      $hash_iv  = 'q9jcZX8Ib9LM8wYk';
    } else {
      $hash_key = config('stone.thrid_party_payment.ecpay_inpay.hash_key');
      $hash_iv  = config('stone.thrid_party_payment.ecpay_inpay.hash_iv');
    }
    $data_decrypt = openssl_decrypt($data_encrypt, 'aes-128-cbc', $hash_key, $options = 0, $hash_iv);
    $data_decode  = urldecode($data_decrypt);
    return json_decode($data_decode);
  }

  # 取得商品Token，準備站內付
  public static function getMerchantToken($data)
  {
    $mode         = config('stone.thrid_party_payment.mode');
    $data_encrypt = self::getEncryptData($data);
    if ($mode == 'dev') {
      $post_url = 'https://ecpg-stage.ecpay.com.tw/Merchant/GetTokenbyTrade';
    } else {
      $post_url = "https://ecpg.ecpay.com.tw/Merchant/GetTokenbyTrade";
    }

    $res = Http::withHeaders([])->post($post_url, [
      "MerchantID" => config('stone.thrid_party_payment.ecpay_inpay.merchant_id'),
      "RqHeader"   => [
        "Timestamp" => Carbon::now()->timestamp,
        "Revision"  => "1.3.15",
      ],
      "Data"       => $data_encrypt,
    ]);
    if ($res->status() == '200') {
      $res_json = $res->json();
      $res_data = self::getDecryptData($res_json['Data']);
      return $res_data->Token;
    }
  }

  # 取得站內付初始化資料，準備開始站內付
  public static function getInpayInitData($data = [], $order_type)
  {
    $initData = [
      "MerchantID"        => config('stone.thrid_party_payment.ecpay_inpay.merchant_id'),
      "RememberCard"      => 1,
      "PaymentUIType"     => 2,
      "ChoosePaymentList" => "1,2,3,4,5",
      "OrderInfo"         => [
        "MerchantTradeNo"   => ShopHelper::newShopOrderNo(),
        "MerchantTradeDate" => Carbon::now()->format('Y/m/d H:i:s'),
        "TotalAmount"       => 100,
        "ReturnURL"         => config('stone.thrid_party_payment.ecpay_inpay.insite_order_return_url'),
        "TradeDesc"         => "Trade",
        "ItemName"          => "ProductA, ProductB",
      ],
      "CardInfo"          => [
        "OrderResultURL"    => config('stone.thrid_party_payment.ecpay_inpay.cardinfo.order_return_url'),
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
        "MerchantMemberID" => "1",
        "Email"            => "customer@email.com",
        "Phone"            => "0912345678",
        "Name"             => "Test",
      ],
    ];
    if (array_key_exists('ConsumerInfo', $data)) {
      $initData['ConsumerInfo'] = $data['ConsumerInfo'];
    }
    if (array_key_exists('OrderInfo', $data)) {
      $initData['OrderInfo'] = $data['OrderInfo'];
    }
    return $initData;
  }

  # 站內付送出，建立付費動作
  public static function createPayment($PayToken, $MerchantTradeNo)
  {
    $mode         = config('stone.thrid_party_payment.mode');
    $data_encrypt = self::getEncryptData([
      "MerchantID"      => config('stone.thrid_party_payment.ecpay_inpay.merchant_id'),
      "PayToken"        => $PayToken,
      "MerchantTradeNo" => $MerchantTradeNo,
    ]);
    if ($mode == 'dev') {
      $post_url = 'https://ecpg-stage.ecpay.com.tw/Merchant/CreatePayment';
    } else {
      $post_url = "https://ecpg.ecpay.com.tw/Merchant/CreatePayment";
    }

    $res = Http::withHeaders([])->post($post_url, [
      "MerchantID" => config('stone.thrid_party_payment.ecpay_inpay.merchant_id'),
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
      // return $res_data->Token;
    }

  }

  # 建立發票
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

    if ($res->status() == '200') {
      $res_json = $res->json();
      $res_data = self::getDecryptData($res_json['Data'], 'invoice');
      return $res_data->InvoiceNo;
    }
  }

  public static function newRelateNumber()
  {
    $time = Carbon::now()->timestamp;
    $str  = Str::random(8);
    return "{$time}{$str}";
  }

  public static function getInvoicePostData($data)
  {
    $post_data = [
      "MerchantID"         => config('stone.invoice.ecpay.merchant_id'),
      "RelateNumber"       => self::newRelateNumber(),
      "CustomerID"         => "",
      "CustomerIdentifier" => "",
      "CustomerName"       => "",
      "CustomerAddr"       => "",
      // "CustomerName"       => "綠界科技股份有限公司",
      // "CustomerAddr"       => "106 台北市南港區發票一街 1 號 1 樓",
      "CustomerPhone"      => "",
      "CustomerEmail"      => "test@ecpay.com.tw",
      "ClearanceMark"      => "1",
      "Print"              => "0",
      "Donation"           => "0",
      "LoveCode"           => "",
      "CarrierType"        => "",
      "CarrierNum"         => "",
      "TaxType"            => "",
      // "TaxType"            => "",
      "SalesAmount"        => 100,
      "InvoiceRemark"      => "發票備註",
      "InvType"            => "07",
      "vat"                => "1",
      "Items"              => [
        [
          "ItemSeq"     => 1,
          "ItemName"    => "item01",
          "ItemCount"   => 1,
          "ItemWord"    => "件",
          "ItemPrice"   => 50,
          "ItemTaxType" => "1",
          "ItemAmount"  => 50,
          "ItemRemark"  => "item01_desc",
        ],
      ],
    ];

    foreach ($data as $key => $value) {
      $post_data[$key] = $data[$key];
    }

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

    if ($res->status() == '200') {
      $res_json = $res->json();
      $res_data = self::getDecryptData($res_json['Data'], 'invoice');
    }
  }

  public static function getDelayInvoicePostData($data)
  {
    return [
      "MerchantID"         => "2000132",
      "RelateNumber"       => self::newRelateNumber(),
      "CustomerID"         => "",
      "CustomerIdentifier" => "",
      "CustomerName"       => "綠界科技股份有限公司",
      "CustomerAddr"       => "106 台北市南港區發票一街 1 號 1 樓",
      "CustomerPhone"      => "",
      "CustomerEmail"      => "test@ecpay.com.tw",
      "ClearanceMark"      => "1",
      "Print"              => "1",
      "Donation"           => "0",
      "LoveCode"           => "",
      "CarrierType"        => "",
      "CarrierNum"         => "",
      "TaxType"            => "1",
      "SalesAmount"        => 100,
      "InvoiceRemark"      => "發票備註",
      "InvType"            => "07",
      "vat"                => "1",
      "DelayFlag"          => "1",
      "DelayDay"           => "3",
      "Items"              => [
        [
          "ItemSeq"     => 1,
          "ItemName"    => "item01",
          "ItemCount"   => 1,
          "ItemWord"    => "件",
          "ItemPrice"   => 50,
          "ItemTaxType" => "1",
          "ItemAmount"  => 50,
          "ItemRemark"  => "item01_desc",
        ],
        [
          "ItemSeq"     => 2,
          "ItemName"    => "item02",
          "ItemCount"   => 1,
          "ItemWord"    => "個",
          "ItemPrice"   => 20,
          "ItemTaxType" => "1",
          "ItemAmount"  => 20,
          "ItemRemark"  => "item02_desc",
        ],
        [
          "ItemSeq"     => 3,
          "ItemName"    => "item03",
          "ItemCount"   => 3,
          "ItemWord"    => "粒",
          "ItemPrice"   => 10,
          "ItemTaxType" => "1",
          "ItemAmount"  => 30,
          "ItemRemark"  => "item03_desc",
        ],
      ],
      // "MerchantID"         => config('stone.invoice.ecpay.merchant_id'),
      // 'RelateNumber'       => self::newRelateNumber(),
      // 'CustomerID'         => '123123',
      // 'CustomerIdentifier' => '',
      // 'CustomerName'       => '',
      // // 'CustomerIdentifier' => '12341234',
      // // 'CustomerName'       => 'companyyyyy',
      // 'CustomerAddr'       => '',
      // 'CustomerPhone'      => '',
      // 'CustomerEmail'      => 'hello@wasateam.com',
      // 'ClearanceMark'      => '',
      // 'Print'              => '0',
      // 'Donation'           => '0',
      // // 'LoveCode'           => '',
      // 'CarrierType'        => '',
      // 'CarrierNum'         => '',
      // 'TaxType'            => '1',
      // // 'SpecialTaxType'     => '',
      // 'SalesAmount'        => '333',
      // 'InvoiceRemark'      => 'Remark Hereeee',
      // 'Items'              => [
      //   [
      //     "ItemSeq"     => '123123',
      //     "ItemName"    => 'Product AAA',
      //     "ItemCount"   => '3',
      //     "ItemWord"    => '個',
      //     "ItemPrice"   => '111',
      //     "ItemTaxType" => '1',
      //     "ItemAmount"  => '333',
      //     "ItemRemark"  => 'Product Remarkkkk',
      //   ],
      // ],
      // 'InvType'            => '07',
      // 'vat'                => '1',
    ];
  }

  public static function getInvoiceItemsFromShopCartProducts($shop_cart_products)
  {
    $items = [];
    foreach ($shop_cart_products as $shop_cart_product) {
      $amount  = ShopHelper::getOrderProductAmountPrice($shop_cart_product);
      $items[] = [
        // "ItemSeq"     => 1,
        "ItemName"    => $shop_cart_product['name'],
        "ItemCount"   => $shop_cart_product['count'],
        "ItemWord"    => "件",
        "ItemPrice"   => $shop_cart_product['discount_price'] ? $shop_cart_product['discount_price'] : $shop_cart_product['price'],
        "ItemTaxType" => "1",
        "ItemAmount"  => $amount,
        "ItemRemark"  => "",
      ];
    }
    return $items;
  }
}
