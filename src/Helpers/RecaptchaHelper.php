<?php

namespace Wasateam\Laravelapistone\Helpers;

class RecaptchaHelper
{
  public static function verify($token)
  {
    $secret_key = env('GOOGLE_RECAPTCHA_SECRETKEY');
    $recaptcha  = new ReCaptcha($secret_key);
    $resp       = $recaptcha
      ->setScoreThreshold(0.5)
      ->verify($token);
    if ($resp->isSuccess()) {
      return true;
    } else {
      return false;
    }
  }
}
