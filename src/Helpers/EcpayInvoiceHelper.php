<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Wasateam\Laravelapistone\Helpers\ShopHelper;

class EcpayInvoiceHelper
{
  public static function getEncryptData($data)
  {
    $hash_key     = config('stone.invoice.ecpay.hash_key');
    $hash_iv      = config('stone.invoice.ecpay.hash_iv');
    $data_json    = json_encode($data);
    $data_encode  = urlencode($data_json);
    $data_encrypt = openssl_encrypt($data_encode, 'aes-128-cbc', $hash_key, $options = 0, $hash_iv);
    return $data_encrypt;
  }

  public static function getDecryptData($data_encrypt)
  {
    $hash_key     = config('stone.invoice.ecpay.hash_key');
    $hash_iv      = config('stone.invoice.ecpay.hash_iv');
    $data_decrypt = openssl_decrypt($data_encrypt, 'aes-128-cbc', $hash_key, $options = 0, $hash_iv);
    $data_decode  = urldecode($data_decrypt);
    return json_decode($data_decode);
  }

  public static function newRelateNumber()
  {
    $time = Carbon::now()->timestamp;
    $str  = Str::random(8);
    return "{$time}{$str}";
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
      $ItemTaxType = "3";
      if ($shop_cart_product->shop_product &&
        $shop_cart_product->shop_product->tax
      ) {
        $ItemTaxType = 1;
      }
      $items[] = [
        // "ItemSeq"     => 1,
        "ItemName"    => $shop_cart_product['name'],
        "ItemCount"   => $shop_cart_product['count'],
        "ItemWord"    => "件",
        "ItemPrice"   => $shop_cart_product_price,
        "ItemTaxType" => $ItemTaxType,
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
      "ItemTaxType" => "3",
      "ItemAmount"  => $shop_order->campaign_deduct * -1,
      "ItemRemark"  => "",
    ];
    $items[] = [
      "ItemName"    => '紅利折抵',
      "ItemCount"   => 1,
      "ItemWord"    => "個",
      "ItemPrice"   => $shop_order->bonus_points_deduct * -1,
      "ItemTaxType" => "3",
      "ItemAmount"  => $shop_order->bonus_points_deduct * -1,
      "ItemRemark"  => "",
    ];
    $items[] = [
      "ItemName"    => '邀請碼折抵',
      "ItemCount"   => 1,
      "ItemWord"    => "個",
      "ItemPrice"   => $shop_order->invite_no_deduct * -1,
      "ItemTaxType" => "3",
      "ItemAmount"  => $shop_order->invite_no_deduct * -1,
      "ItemRemark"  => "",
    ];
    $items[] = [
      "ItemName"    => '刷退金額',
      "ItemCount"   => 1,
      "ItemWord"    => "項",
      "ItemPrice"   => $shop_order->return_price * -1,
      "ItemTaxType" => "3",
      "ItemAmount"  => $shop_order->return_price * -1,
      "ItemRemark"  => "",
    ];
    return $items;
  }

  public static function getTaxType($shop_order)
  {
    $is_tax_1 = 1;
    $is_tax_3 = 1;

    foreach ($shop_order->shop_order_shop_products as $shop_order_shop_product) {
      if (!$shop_order_shop_product->shop_product) {
        continue;
      }
      if ($shop_order_shop_product->shop_product->tax) {
        $is_tax_3 = 0;
      } else {
        $is_tax_1 = 0;
      }
    }
    if ($is_tax_1) {
      return '1';
    } else if ($is_tax_3) {
      return '3';
    } else {
      return '9';
    }
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
      "NotifyURL"          => config('stone.invoice.notify_url'),
    ];
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
    $Items,
    $shopOrderNo
  ) {
    $post_data = [
      "MerchantID"         => config('stone.invoice.ecpay.merchant_id'),
      "RelateNumber"       => $shopOrderNo,
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

    $mode = config('stone.invoice.mode');
    if ($mode == 'dev') {
      $post_data['CustomerName']       = '測試仔';
      $post_data['CustomerAddr']       = '台北市中山區民權東路十段99號地下7樓';
      $post_data['CustomerPhone']      = '0900000000';
      $post_data['CustomerEmail']      = 'hello@wasateam.com';
    }

    return $post_data;
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
      \Log::info($res_json);
      throw new \Wasateam\Laravelapistone\Exceptions\EcpayInvoiceException('createInvoice', null, null, $res_json['TransCode'], $res_json['TransMsg']);
    }
    $res_data = self::getDecryptData($res_json['Data'], 'invoice');
    if ($res_data->RtnCode != '1') {
      if ($res_data->RtnCode == 5070357) {
        return (object) ([
          'code' => 5070,
        ]);
      } else {
        \Log::info(json_encode($res_data));
        throw new \Wasateam\Laravelapistone\Exceptions\EcpayInvoiceException('createInvoice', $res_data->RtnCode, $res_data->RtnMsg);
      }
    }
    return (object) ([
      'code' => 1,
      'data' => $res_data,
    ]);
  }
}
