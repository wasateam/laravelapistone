<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;

class UserServicePlanHelper
{
  public static function setExpiredAt($user_service_plan, $user)
  {
    $service_plan = $user_service_plan->service_plan;
    if ($service_plan->period_month) {
      $user_service_plan->expired_at = Carbon::now()->addMonths($service_plan->period_month);
      $user_service_plan->save();
      $user->subscribe_start_at = Carbon::now();
      $user->subscribe_end_at   = Carbon::now()->addMonths($service_plan->period_month);
      $user->save();
    }
  }
}
