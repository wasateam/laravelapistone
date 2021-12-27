<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;

class TimeHelper
{
  // convert country to timezone
  public static function getTimeZone($country_code)
  {
    if ($country_code == 'tw') {
      return 'Asia/Taipei';
    } else if ($country_code == 'my') {
      return 'Asia/Kolkata';
    } else {
      return 'UTC';
    }
  }

  public static function setTimeFromHrMinStr($datetime, $hr_min_str)
  {
    $hr_and_min = self::getHrAndMinFromHrMinStr($hr_min_str);
    $_datetime  = Carbon::parse($datetime);
    $_datetime->set('hour', $hr_and_min[0]);
    $_datetime->set('minute', $hr_and_min[1]);
    return $_datetime;
  }

  public static function getHrAndMinFromHrMinStr($hr_min_str)
  {
    return str_split($hr_min_str, 2);
  }
}
