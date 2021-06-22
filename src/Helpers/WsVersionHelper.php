<?php

namespace Wasateam\Laravelapistone\Helpers;

class WsVersionHelper
{
  public static function getVersion($request, $version_name = null, $model_name = null, $resource = null, $order_by = null, $model = null)
  {
    $last_version = [];
    if ($request[$version_name . 's']) {
      $item_arr     = array_map('intval', explode(',', $request[$version_name . 's']));
      $last_version = $model::whereHas($version_name . 's', function ($query) use ($item_arr) {
        return $query->whereIn('id', $item_arr);
      })->where($model_name . '_id', $resource->id)->latest($order_by)->get();
      $last_version = count($last_version) ? $last_version[0] : null;
    } else {
      $last_version = $model::where($model_name . '_id', $resource->id)->latest($order_by)->get();
      $last_version = count($last_version) ? $last_version[0] : null;
    }
    return $last_version;
  }
}
