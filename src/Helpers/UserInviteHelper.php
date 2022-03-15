<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Models\ShopOrder;
use Wasateam\Laravelapistone\Models\User;

class UserInviteHelper
{
  public static function check($invite_no, $user)
  {
    $target_user = User::where('invite_no', $invite_no)
      ->whereNull('deleted_at')->first();
    if (!$target_user) {
      return false;
    }
    if ($target_user->id == $user->id) {
      return false;
    }
    $exist_shop_order = ShopOrder::where('user_id', $user->id)
      ->where('pay_status', '!=', 'not-paid')
      ->whereNull('deleted_at')
      ->where('invite_no', $invite_no)
      ->first();
    if ($exist_shop_order) {
      return false;
    }
    return true;
  }
}
