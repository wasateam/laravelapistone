<?php

namespace Wasateam\Laravelapistone\Helpers;

/**
 *
 */
class ScheduleHelper
{

  public static function stoneWork()
  {
    if ('stone.shop.pay_expire') {
      \Wasateam\Laravelapistone\Jobs\CheckShopOrderPayExpireJob::dispatch();
    }
  }
}
