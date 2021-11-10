<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EcpayHelper
{
  public static function getEncryptData($data)
  {
    $hash_key     = config('stone.thrid_party_payment.ecpay_inpay.hash_key');
    $hash_iv      = config('stone.thrid_party_payment.ecpay_inpay.hash_iv');
    $data_json    = json_encode($data);
    $data_encode  = urlencode($data_json);
    $data_encrypt = openssl_encrypt($data_encode, 'aes-128-cbc', $hash_key, $options = 0, $hash_iv);
    return $data_encrypt;
  }

  public static function getDecryptData($data_encrypt)
  {
    $hash_key     = config('stone.thrid_party_payment.ecpay_inpay.hash_key');
    $hash_iv      = config('stone.thrid_party_payment.ecpay_inpay.hash_iv');
    $data_decrypt = openssl_decrypt($data_encrypt, 'aes-128-cbc', $hash_key, $options = 0, $hash_iv);
    $data_decode  = urldecode($data_decrypt);
    return json_decode($data_decode);
  }

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
        "Revision"  => "1.0.0",
      ],
      "Data"       => $data_encrypt,
    ]);
    if ($res->status() == '200') {
      $res_json = $res->json();
      $res_data = self::getDecryptData($res_json['Data']);
      return $res_data->Token;
    }
  }

  public static function newMerchantTradeNo()
  {
    $time = Carbon::now()->timestamp;
    $str  = Str::random(8);
    return "{$time}{$str}";
  }

  public static function getInpayInitData()
  {

    return [
      "MerchantID"        => config('stone.thrid_party_payment.ecpay_inpay.merchant_id'),
      "RememberCard"      => 1,
      "PaymentUIType"     => 2,
      "ChoosePaymentList" => "1,2,3,4,5",
      "OrderInfo"         => [
        "MerchantTradeNo"   => self::newMerchantTradeNo(),
        "MerchantTradeDate" => Carbon::now()->format('Y/m/d H:i:s'),
        "TotalAmount"       => 100,
        "ReturnURL"         => config('stone.thrid_party_payment.ecpay_inpay.insite_order_return_url'),
        "TradeDesc"         => "Cool Trade",
        "ItemName"          => "ProductA, ProductB",
      ],
      "CardInfo"          => [
        "OrderResultURL"    => config('stone.thrid_party_payment.ecpay_inpay.cardinfo.3d_order_return_url'),
        "CreditInstallment" => "3",
      ],
      "ATMInfo"           => [
        "ExpireDate" => 3,
      ],
      "ConsumerInfo"      => [
        "MerchantMemberID" => "test123456",
        "Email"            => "customer@email.com",
        "Phone"            => "0912345678",
        "Name"             => "Test",
        "CountryCode"      => "158",
      ],
    ];
  }

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
        "Revision"  => "1.0.0",
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
}
