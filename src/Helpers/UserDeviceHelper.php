<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Models\UserDeviceModifyRecord;

class UserDeviceHelper
{
  public static function user_device_record($action, $user_device, $user)
  {
    $user_device_modify_record                 = new UserDeviceModifyRecord;
    $user_device_modify_record->action         = $action;
    $user_device_modify_record->user_device_id = $user_device->id;
    $user_device_modify_record->user_id        = $user->id;
    $user_device_modify_record->save();
  }
}
