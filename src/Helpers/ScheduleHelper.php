<?php

namespace Wasateam\Laravelapistone\Helpers;

/**
 *
 */
class ScheduleHelper
{

  public static function stoneWork()
  {
    if (config('stone.shop')) {
      if (config('stone.shop.pay_expire')) {
        \Log::info('ok');
        \Wasateam\Laravelapistone\Jobs\CheckShopOrderPayExpireJob::dispatch();
      }
    }
    if (config('stone.service_store')) {
      if (config('stone.service_store.appointment')) {
        if (config('stone.service_store.appointment.notify')) {
          if (config('stone.service_store.appointment.notify.today_appointments')) {
            \Wasateam\Laravelapistone\Jobs\CheckServiceStoreAppointmentsNotifyTodayJob::dispatch();
          }
          if (config('stone.service_store.appointment.notify.tomorrow_appointments')) {
            \Wasateam\Laravelapistone\Jobs\CheckServiceStoreAppointmentsNotifyTomorrowJob::dispatch();
          }
        }
      }
    }
  }
}
