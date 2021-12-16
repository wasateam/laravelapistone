<?php

namespace Wasateam\Laravelapistone\Helpers;

class TimeHelper
{
  //convert country to timezone
  public static function getTimeZone($country_code)
  {
    if ($country_code == 'tw') {
      return 'Asia/Taipei';
    }
  }
}
