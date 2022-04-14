<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Mail;
use Wasateam\Laravelapistone\Helpers\AppointmentHelper;
use Wasateam\Laravelapistone\Helpers\TimeHelper;
use Wasateam\Laravelapistone\Models\Appointment;
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
    return TimeHelper::getUTCTimeFromTimezone($time, $timezone);
  }

  public static function getServiceStoreAppointmentsDaily($date, $service_store_id)
  {
    $service_store = ServiceStore::find($service_store_id);
    if (!$service_store) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('$service_store');
    }
    $appointments = Appointment::where('service_store_id', $service_store_id)
      ->whereDate('date', $date)
      ->get();
    return $appointments;
  }

  public static function getServiceStoreAppointmentsDailyCreated($date, $service_store_id)
  {
    $service_store = ServiceStore::find($service_store_id);
    if (!$service_store) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('$service_store');
    }
    $appointments = Appointment::where('service_store_id', $service_store_id)
      ->whereDate('created_at', $date)
      ->get();
    return $appointments;
  }

  public static function getServiceStoreAppointmentsDailyAppointed($date, $service_store_id)
  {
    $service_store = ServiceStore::find($service_store_id);
    if (!$service_store) {
      throw new \Wasateam\Laravelapistone\Exceptions\FieldRequiredException('$service_store');
    }
    $appointments = Appointment::where('service_store_id', $service_store_id)
      ->whereDate('date', $date)
      ->get();
    return $appointments;
  }

  public static function CheckServiceStoreAppointmentsNotifyToday()
  {
    $now = \Carbon\Carbon::now();
    if (config('stone.service_store.appointment.notify.today_appointments.default')) {
      $notify_time = \Carbon\Carbon::parse(config('stone.service_store.appointment.notify.today_appointments.default'));
      if ($now >= $notify_time) {
        $service_stores = ServiceStore::whereNull('today_appointments_notify_time')
          ->where(function ($query) use ($now) {
            $query->whereDate('today_appointments_notify_at', '<', $now);
            $query->orWhereNull('today_appointments_notify_at');
          })
          ->get();

        foreach ($service_stores as $service_store) {
          if ($service_store->notify_emails) {
            foreach ($service_store->notify_emails as $notify_email) {
              \Wasateam\Laravelapistone\Jobs\MailServiceStoreAppointsTodayJob::dispatch($notify_email['email'], $service_store->id, $now);
            }
            $service_store->today_appointments_notify_at = $now;
            $service_store->save();
          }
        }
      }
    }
  }

  public static function CheckServiceStoreAppointmentsNotifyTomorrow()
  {
    $now      = \Carbon\Carbon::now();
    $tomorrow = \Carbon\Carbon::now()->addDays(1);
    if (config('stone.service_store.appointment.notify.tomorrow_appointments.default')) {
      $notify_time = \Carbon\Carbon::parse(config('stone.service_store.appointment.notify.tomorrow_appointments.default'));
      if ($now >= $notify_time) {
        $service_stores = ServiceStore::whereNull('tomorrow_appointments_notify_time')
          ->where(function ($query) use ($now) {
            $query->whereDate('tomorrow_appointments_notify_at', '<', $now);
            $query->orWhereNull('tomorrow_appointments_notify_at');
          })
          ->get();
        foreach ($service_stores as $service_store) {
          if ($service_store->notify_emails) {
            foreach ($service_store->notify_emails as $notify_email) {
              \Wasateam\Laravelapistone\Jobs\MailServiceStoreAppointsTomorrowJob::dispatch($notify_email['email'], $service_store->id, $tomorrow);
            }
            $service_store->tomorrow_appointments_notify_at = $now;
            $service_store->save();
          }
        }
      }
    }
  }

  public static function MailServiceStoreAppointsToday($mail, $service_store_id, $date)
  {
    $appointments          = self::getServiceStoreAppointmentsDailyCreated($date, $service_store_id);
    $formated_appointments = AppointmentHelper::getFormatedAppointmentsForTable($appointments);
    $service_store         = ServiceStore::find($service_store_id);

    Mail::to($mail)->send(new \Wasateam\Laravelapistone\Mail\ServiceStoreAppointmentsToday(
      $service_store,
      $formated_appointments
    ));
  }

  public static function MailServiceStoreAppointsTomorrow($mail, $service_store_id, $date)
  {
    $appointments          = self::getServiceStoreAppointmentsDailyAppointed($date, $service_store_id);
    $formated_appointments = AppointmentHelper::getFormatedAppointmentsForTable($appointments);
    $service_store         = ServiceStore::find($service_store_id);

    Mail::to($mail)->send(new \Wasateam\Laravelapistone\Mail\ServiceStoreAppointmentsTomorrow(
      $service_store,
      $formated_appointments
    ));
  }
}
