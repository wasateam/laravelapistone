<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Models\UserDeviceModifyRecord;
use Wasateam\Laravelapistone\Models\UserServicePlan;

class UserDeviceHelper
{
  public static function user_device_record($action, $user_device, $user)
  {
    $service_plan = UserServicePlan::where('user_id', $user->id)->latest()->first();

    $user_device_modify_record                  = new UserDeviceModifyRecord;
    $user_device_modify_record->action          = $action;
    $user_device_modify_record->user_device_id  = $user_device->id;
    $user_device_modify_record->user_id         = $user->id;
    $user_device_modify_record->service_plan_id = $service_plan ? $service_plan->id : null;
    $user_device_modify_record->save();
  }
}
