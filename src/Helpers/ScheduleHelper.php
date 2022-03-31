<?php

namespace Wasateam\Laravelapistone\Helpers;

/**
 *
 */
class ScheduleHelper
{

  public static function stoneWork()
  {
    if ('stone.shop') {
      if ('stone.shop.pay_expire') {
        \Wasateam\Laravelapistone\Jobs\CheckShopOrderPayExpireJob::dispatch();
      }
    }
    if ('stone.service_store') {
      if ('stone.service_store.appointment') {
        if ('stone.service_store.appointment.notify') {
          if ('stone.service_store.appointment.notify.today_appointments') {
            \Wasateam\Laravelapistone\Jobs\CheckServiceStoreAppointmentsNotifyTodayJob::dispatch();
          }
          if ('stone.service_store.appointment.notify.tomorrow_appointments') {
            \Wasateam\Laravelapistone\Jobs\CheckServiceStoreAppointmentsNotifyTomorrowJob::dispatch();
          }
        }
      }
    }
  }
}
