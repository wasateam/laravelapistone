<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Wasateam\Laravelapistone\Helpers\ServiceStoreHelper;
use Wasateam\Laravelapistone\Helpers\TimeHelper;
use Wasateam\Laravelapistone\Models\Appointment;
use Wasateam\Laravelapistone\Models\ServiceStore;
use Wasateam\Laravelapistone\Notifications\AppointmentRemind;

class AppointmentHelper
{
  public static function appointmentNotify()
  {
    if (config('stone.appointment')) {
      if (config('stone.appointment.notify')) {
        $before_hours = config('stone.appointment.notify.before_houres') ? config('stone.appointment.notify.before_houres') : 1;
        $start_time   = Carbon::now();
        $end_time     = Carbon::now()->addHours($before_hours);
        $appointments = Appointment::whereTime('start_at', '<=', $end_time)
          ->whereTime('start_at', '>=', $start_time)
          ->whereNull('notify_at')
          ->whereNotNull('user_id')
          ->get();
        foreach ($appointments as $appointment) {
          $appointment->user->notify(new AppointmentRemind($appointment));
          $appointment->notify_at = Carbon::now();
          $appointment->save();
        }
      }
    }
  }

  public static function getFormatedAppointmentsForTable($appointments)
  {
    $_formated_appointments = [];
    foreach ($appointments as $appointment) {
      $_formated_appointments[] = self::getFormatedAppointmentForTable($appointment);
    }
    return $_formated_appointments;
  }

  public static function getFormatedAppointmentForTable($appointment)
  {
    $date                  = $appointment->date;
    $start_time            = TimeHelper::setTimeFromCountryCode($appointment->start_time, $appointment->country_code);
    $end_time              = TimeHelper::setTimeFromCountryCode($appointment->end_time, $appointment->country_code);
    $user_name             = $appointment->user ? $appointment->user->name : null;
    $service_store_name    = $appointment->service_store ? $appointment->service_store->name : null;
    $_formated_appointment = [
      'date'               => $date,
      'start_time'         => $start_time,
      'end_time'           => $end_time,
      'user_name'          => $user_name,
      'service_store_name' => $service_store_name,
    ];
    return $_formated_appointment;
  }

  public static function appointableAvailableCheckFromRequest($request)
  {
    $service_store = ServiceStore::find($request->service_store);
    if (!$service_store) {
      throw new \Wasateam\Laravelapistone\Exceptions\FindNoDataException('service_store');
    }
    ServiceStoreHelper::AppointmentAvaliableCheck($service_store, $request->start_time, $request->end_time, $request->date);
  }

  public static function appointmentCancelNotify($appointment)
  {
    if (
      config('stone.service_store') &&
      config('stone.service_store.appointment') &&
      config('stone.service_store.appointment.notify') &&
      config('stone.service_store.appointment.notify.cancel')
    ) {
      $service_store = $appointment->service_store;
      if ($service_store->notify_emails) {
        foreach ($service_store->notify_emails as $notify_email) {
          Mail::to($notify_email['email'])
            ->queue(new \Wasateam\Laravelapistone\Mail\ServiceStoreAppointmentsCancel($appointment->id));
        }
      }
    }
  }
}
