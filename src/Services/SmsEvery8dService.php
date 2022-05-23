<?php

namespace Wasateam\Laravelapistone\Services;

use Illuminate\Support\Facades\Http;

class SmsEvery8dService
{

  public function __construct()
  {
  }

  public function get_token()
  {
    $url      = 'https://api.e8d.tw/API21/HTTP/ConnectionHandler.ashx';
    $response = Http::post($url, [
      'HandlerType' => 3,
      'VerifyType'  => 1,
      'UID'         => config('stone.sms.every8d.UID'),
      'PWD'         => config('stone.sms.every8d.PWD'),
    ]);
    if ($response->status() != 200) {
      throw new \Wasateam\Laravelapistone\Exceptions\GeneralException('every8d get token error.');
    }
    $res_json = $response->json();
    if (!$res_json['Result']) {
      throw new \Wasateam\Laravelapistone\Exceptions\GeneralException('every8d get token error.');
    }
    return $res_json['Msg'];
  }

  public function send($subject, $message, $dest)
  {
    $token = self::get_token();

    // $response = Http::withHeaders([
    //   'Authorization' => "Bearer {$token}",
    // ])

    $url = 'https://api.e8d.tw/API21/HTTP/SendSMS.ashx';
    // \Log::info(config('stone.sms.every8d.UID'));
    // \Log::info(config('stone.sms.every8d.PWD'));
    \Log::info($token);
    \Log::info($subject);
    \Log::info($message);
    \Log::info($dest);
    $response = Http::withHeaders([
      'Authorization' => "Bearer {$token}",
      "Content-Type"  => "application/x-www-form-urlencoded",
    ])->post($url, [
      // 'UID'  => config('stone.sms.every8d.UID'),
      // 'PWD'  => config('stone.sms.every8d.PWD'),
      'SB'   => $subject,
      'MSG'  => $message,
      'DEST' => $dest,
    ]);
    \Log::info($response);
    if ($response->status() != 200) {
      throw new \Wasateam\Laravelapistone\Exceptions\GeneralException('every8d send error.');
    }
    $res_json = $response->json();
    if (!$res_json['Result']) {
      throw new \Wasateam\Laravelapistone\Exceptions\GeneralException('every8d send error.');
    }

  }

}
