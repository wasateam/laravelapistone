<?php

namespace Wasateam\Laravelapistone\Helpers;

class RecaptchaHelper
{
  public static function verify($token)
  {
    $secret_key = '6LfJoTocAAAAAK4YNur38dl_u6FmwjEs_9PaxU7v';
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
