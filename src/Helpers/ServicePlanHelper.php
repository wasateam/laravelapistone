<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;

class ServicePlanHelper
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

  public static function getServicePlanUsingRecordsFromAcumatica($acumaticaDetails, $user, $pin)
  {
    $service_plan_using_records = [];
    foreach ($acumaticaDetails as $acumaticaDetail) {
      if (
        $acumaticaDetail['CustomerID']['value'] == $user->customer_id &&
        $acumaticaDetail['PinCode']['value'] == $pin
      ) {
        $service_plan_using_records[] = [
          'name'         => $acumaticaDetail['ServiceDescription']['value'],
          'total_count'  => $acumaticaDetail['LimitedCount']['value'],
          'remain_count' => $acumaticaDetail['RemainingCount']['value'],
          'due_date'     => $acumaticaDetail['EndDate']['value'],
        ];
      }
    }
    return $service_plan_using_records;
  }
}
