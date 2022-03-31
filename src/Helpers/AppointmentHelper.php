<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Wasateam\Laravelapistone\Models\Appointment;
use Wasateam\Laravelapistone\Models\ServiceStore;
use Wasateam\Laravelapistone\Notifications\AppointmentRemind;
use Wasateam\Laravelapistone\Helpers\TimeHelper;

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
}
