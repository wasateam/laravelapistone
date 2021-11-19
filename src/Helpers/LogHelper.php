<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Models\WebLog;

class LogHelper
{
  public static function createWebLog($user_id = null, $action = null, $target = null, $target_id = null, $ip = null, $remark = null)
  {
    if (!config('stone.log.is_active')) {
      return;
    }
    $log          = new WebLog;
    $log->user_id = $user_id;
    $log->payload = [
      'userModelName' => 'user',
      'action'        => $action,
      'target'        => $target,
      'target_id'     => $target_id,
      'ip'            => $ip,
      'remark'        => $remark,
    ];
    $log->save();
  }
}
