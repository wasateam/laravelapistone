<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use DB;
use Wasateam\Laravelapistone\Helpers\StrHelper;
use Wasateam\Laravelapistone\Models\ShopOrder;

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
      $exist     = $model::where('invite_no', $invite_no)->first();
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

  public static function setUserExportHeadingsShopSum($headings)
  {
    if (config('stone.shop.order_type')) {
      foreach (config('stone.shop.order_type') as $type_key => $_type) {
        $headings[] = $_type['title'] . '消費總額';
      }
    }
    return $headings;
  }

  public static function setUserExportHeadingsShopCount($headings)
  {
    if (config('stone.shop.order_type')) {
      foreach (config('stone.shop.order_type') as $type_key => $_type) {
        $headings[] = $_type['title'] . '消費次數';
      }
    }
    return $headings;
  }

  public static function setUserExportMapShopSum($map, $user)
  {
    if (config('stone.shop.order_type')) {
      foreach (config('stone.shop.order_type') as $type_key => $_type) {
        $order_price_sum = ShopOrder::where('user_id', $user->id)
          ->where('order_type', $type_key)
          ->whereIn('status', ['established', 'return-part-apply'])
          ->sum('order_price');
        $return_price_sum = ShopOrder::where('user_id', $user->id)
          ->where('order_type', $type_key)
          ->whereIn('status', ['established', 'return-part-apply'])
          ->sum('return_price');
        $map[] = $order_price_sum - $return_price_sum;
      }
    }
    return $map;
  }

  public static function setUserExportMapShopCount($map, $user)
  {
    if (config('stone.shop.order_type')) {
      foreach (config('stone.shop.order_type') as $type_key => $_type) {
        $count = ShopOrder::where('user_id', $user->id)
          ->where('order_type', $type_key)
          ->whereIn('status', ['established', 'return-part-apply'])
          ->count();
        $map[] = $count;
      }
    }
    return $map;
  }

  public static function getUserInvitedCount($user)
  {
    if (!$user->invite_no) {
      return;
    }
    return ShopOrder::where('invite_no', $user->invite_no)->count();
  }
}
