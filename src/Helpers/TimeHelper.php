<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Wasateam\Laravelapistone\Helpers\SpareHelper;

class TimeHelper
{
  public static function getTimeZoneFromCountryCode($country_code = null)
  {
    $country_code_timezone = SpareHelper::countryCodeTimeZone();
    return ($country_code && $country_code_timezone[$country_code]) ? $country_code_timezone[$country_code] : 'UTC';
  }

  public static function setTimeFromCountryCode($time, $country_code)
  {
    $timezone = self::getTimeZoneFromCountryCode($country_code);
    return self::getTimeFromTimezone($time, $timezone);
  }

  public static function getTimeFromTimezone($time, $timezone, $format = 'H:i:s')
  {
    $_time = \Carbon\Carbon::parse($time);
    $_time->setTimezone($timezone);
    return $_time->format($format);
  }

  public static function getTimeFromCountryCode($datetime, $country_code = 'tw', $format = 'H:i:s')
  {
    $_time    = \Carbon\Carbon::parse($datetime);
    $timezone = self::getTimeZoneFromCountryCode($country_code);
    $_time->setTimezone($timezone);
    return $_time->format($format);
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

  public static function getTimeFromTimeStr($time_str)
  {
    $time_hr_and_min = self::getHrAndMinFromHrMinStr($time_str);
    return $time_hr_and_min[0] . ':' . $time_hr_and_min[1] . ':00';
  }

  public static function timeFixFromStrToTime($time)
  {
    $time  = Carbon::parse($time);
    $_time = Carbon::now();
    $_time->set('hour', $time->format('i'));
    $_time->set('minute', $time->format('s'));
    $_time->set('second', 0);
    return $_time;
  }

  public static function getUTCTimeFromTimezone($time, $timezone)
  {
    $time = \Carbon\Carbon::parse($time, $timezone);
    $time->setTimezone('UTC');
    return $time;
  }

  public static function getUTCTimeFromTimezoneStartOfDay($time, $timezone)
  {
    $time = \Carbon\Carbon::parse($time, $timezone)->startOfDay();
    $time->setTimezone('UTC');
    return $time;
  }

  public static function getUTCTimeFromTimezoneEndOfDay($time, $timezone)
  {
    $time = \Carbon\Carbon::parse($time, $timezone)->endOfDay();
    $time->setTimezone('UTC');
    return $time;
  }

  public static function getWeekDayNameFromDatetime($datetime)
  {
    $_datetime = \Carbon\Carbon::parse($datetime);
    $weekday   = $_datetime->dayOfWeek;
    $week_map  = [
      0 => 'sun',
      1 => 'mon',
      2 => 'tue',
      3 => 'wed',
      4 => 'thu',
      5 => 'fri',
      6 => 'sat',
    ];
    return $week_map[$weekday];
  }
}
