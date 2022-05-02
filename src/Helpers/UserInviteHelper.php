<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Models\ShopOrder;
use Wasateam\Laravelapistone\Models\User;

class UserInviteHelper
{
  public static function check($invite_no, $user, $neglect_shop_order_ids = null)
  {
    if (!$invite_no) {
      return false;
    }
    $target_user = User::where('invite_no', $invite_no)
      ->whereNull('deleted_at')->first();
    if (!$target_user) {
      return false;
    }
    if ($target_user->id == $user->id) {
      return false;
    }
    $snap = ShopOrder::where('user_id', $user->id)
      ->where('pay_status', '!=', 'not-paid')
      ->whereNull('deleted_at')
      ->where('invite_no', $invite_no)
      ->whereIn('status',
        [
          'established',
          'not-established',
          'return-part-apply',
          'return-part-complete',
          'complete',
        ]);
    if ($neglect_shop_order_ids) {
      $snap->whereNotIn('id', $neglect_shop_order_ids);
    }
    $exist_shop_order = $snap->first();
    if ($exist_shop_order) {
      return false;
    }
    return true;
  }
}
