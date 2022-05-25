<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Helpers\StrHelper;
use Wasateam\Laravelapistone\Modules\Otp\App\Models\Otp;

class OtpHelper
{
  public static function getAuthOtp($user_id)
  {
    Otp::userActive($user_id)
      ->update([
        'is_active' => 0,
      ]);

    $minites         = config('stone.auth.mobile.otp.minutes') ? config('stone.auth.mobile.otp.minutes') : 10;
    $otp             = new Otp;
    $otp->content    = StrHelper::generateRandomString(6, '0123456789');
    $otp->is_active  = 1;
    $otp->usage      = 'auth';
    $otp->user_id    = $user_id;
    $otp->expired_at = \Carbon\Carbon::now()->addMinutes($minites);
    $otp->save();
    return $otp->content;
  }

  public static function checkOtp($user_id, $content)
  {
    $otp = Otp::userActive($user_id)
      ->first();
    if ($otp && $otp->content == $content) {
      $otp->is_active = 0;
      $otp->save();
      return true;
    } else {
      return false;
    }
  }
}
