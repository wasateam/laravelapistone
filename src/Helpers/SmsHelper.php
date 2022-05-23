<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Http;

class SmsHelper
{
  public static function send($message, $mobile, $mobile_country_code)
  {
    if (config('stone.sms')) {
      if (config('stone.sms.service') == 'every8d') {
      }
    }
  }
}
