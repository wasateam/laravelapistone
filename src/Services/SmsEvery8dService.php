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
    $url      = 'https://api.e8d.tw/API21/HTTP/SendSMS.ashx';
    $response = Http::asForm()
      ->withHeaders([
        "Content-Type" => "application/x-www-form-urlencoded",
      ])->post($url, [
      'UID'  => config('stone.sms.every8d.UID'),
      'PWD'  => config('stone.sms.every8d.PWD'),
      'SB'   => $subject,
      'MSG'  => $message,
      'DEST' => $dest,
    ]);
    if (
      $response->status() != 200 ||
      str_contains($response, '-99')
    ) {
      throw new \Wasateam\Laravelapistone\Exceptions\GeneralException('every8d send error.');
    }

  }

}
