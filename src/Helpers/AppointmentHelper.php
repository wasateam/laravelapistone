<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Wasateam\Laravelapistone\Helpers\TimeHelper;
use Wasateam\Laravelapistone\Models\Appointment;
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
      $date                     = $appointment->date;
      $start_time               = TimeHelper::setTimeFromCountryCode($appointment->start_time, $appointment->country_code);
      $end_time                 = TimeHelper::setTimeFromCountryCode($appointment->end_time, $appointment->country_code);
      $user_name                = $appointment->user ? $appointment->user->name : null;
      $service_store_name       = $appointment->service_store ? $appointment->service_store->name : null;
      $_formated_appointments[] = [
        'date'               => $date,
        'start_time'         => $start_time,
        'end_time'           => $end_time,
        'user_name'          => $user_name,
        'service_store_name' => $service_store_name,
      ];
    }
    return $_formated_appointments;
  }
}
