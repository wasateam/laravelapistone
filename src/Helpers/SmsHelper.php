<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Services\SmsEvery8dService;

class SmsHelper
{
  public static function send($subject, $message, $targets)
  {
    if (config('stone.sms')) {
      if (config('stone.sms.service') == 'every8d') {
        SmsEvery8dService::send(
          $subject,
          $message,
          $targets
        );
      }
    }
  }

  public static function sendAuthOtp($otp, $user)
  {
    $minites = config('stone.auth.mobile.otp.minutes') ? config('stone.auth.mobile.otp.minutes') : 10;
    $target  = "+" . $user->mobile_country_code . $user->mobile;
    self::send(
      '登入驗證碼',
      "您的一次性驗證碼為：{$otp}，請於{$minites}分鐘內輸入驗證。",
      $target
    );
  }
}
