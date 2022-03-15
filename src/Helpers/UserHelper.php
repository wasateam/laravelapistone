<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use DB;
use Wasateam\Laravelapistone\Helpers\StrHelper;

class UserHelper
{
  public static function expiredToken($user_id)
  {
    // change token not expired to expired

    $now = Carbon::now();
    DB::table('oauth_access_tokens')->where('user_id', $user_id)->where('expires_at', '>', $now)->update([
      'revoked' => true,
    ]);
  }

  public static function generateInviteNo($user, $model)
  {
    $try_count = 0;
    while ($try_count < 10) {
      $invite_no = StrHelper::generateRandomString(12, '23456789ABCDEFGHJKLMNPQRSTUVWXYZ', $type = 'unixlast6');
      $exist = $model::where('invite_no', $invite_no)->first();
      if (!$exist) {
        $user->invite_no = $invite_no;
        $user->save();
        break;
      } else {
        $try_count++;
      }
    }
    return $user;
  }
}
