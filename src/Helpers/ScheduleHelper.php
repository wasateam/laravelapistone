<?php

namespace Wasateam\Laravelapistone\Helpers;

/**
 *
 */
class ScheduleHelper
{

  public static function stoneWork()
  {
    \Log::info('stoneWork');

    if ('stone.shop.pay_expire') {
      \Wasateam\Laravelapistone\Jobs\CheckShopOrderPayExpireJob::dispatch();
    }
  }
}
