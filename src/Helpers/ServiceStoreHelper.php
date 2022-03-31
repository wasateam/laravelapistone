<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Helpers\TimeHelper;
use Wasateam\Laravelapistone\Models\ServiceStore;

class ServiceStoreHelper
{
  public static function getServiceStoreTime($time_str, $service_store_id)
  {
    $time          = TimeHelper::getTimeFromTimeStr($time_str);
    $service_store = ServiceStore::find($service_store_id);
    if (!$service_store) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('$service_store');
    }
    $timezone = $service_store->country_code ? TimeHelper::getTimeZone($service_store->country_code) : 'UTC';
    \Log::info($timezone);
    return TimeHelper::getUTCTimeFromTimezone($time, $timezone);
  }
}
