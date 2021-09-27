<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use DB;

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
}
