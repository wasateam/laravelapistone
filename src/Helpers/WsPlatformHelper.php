<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class WsPlatformHelper
{

  public static function index($url, $id = null, $params = [], $request = null)
  {
    if ($id) {
      $url = $url . $id;
    }
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->get(config('app.platform_url') . $url, $request->all());
    return $res->json();
  }

  public static function store($url, $id = null, $request = null)
  {
    if ($id) {
      $url = $url . $id;
    }
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->post(config('app.platform_url') . $url, $request->all());
    return $res->json();
  }

  public static function show($url, $id = null)
  {
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->get(config('app.platform_url') . $url . $id);
    return $res->json();
  }

  public static function update($url, $id = null, $request = null)
  {
    if ($id) {
      $url = $url . $id;
    }
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->patch(config('app.platform_url') . $url, $request->all());
    return $res->json();
  }

  public static function upload($url, $filename = null)
  {
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->put(config('app.platform_url') . $url . $filename);
    return $res->json();
  }

  public static function delete($url, $id = null)
  {
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->delete(config('app.platform_url') . $url . $id);
    return $res->json();
  }

  public static function find_by_ids($url, $id = null, $request = null)
  {
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->get(config('app.platform_url') . $url, [
      "ids" => $request->ids,
    ]);
    return $res->json();
  }

  public static function getSingle($url, $id, $params = [])
  {
    if (!$id) {
      return;
    }
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->get(config('app.platform_url') . $url . $id);
    return $res->json();
  }

  public static function getMultiple($url, $app_id = null, $app_model_name = null, $table_name = null, $platform_model_name = null, $params = [])
  {
    $datas    = DB::table($table_name)->where("{$app_model_name}_id", $app_id)->get();
    $data_ids = [];
    $model_id = $platform_model_name . '_id';
    foreach ($datas as $data) {
      $data_ids[] = $data->$model_id;
    }
    $res = HTTP::withHeaders([
      'app_token' => config('app.app_token'),
    ])->get(config('app.platform_url') . $url, ["ids" => collect($data_ids)->implode(',')]);
    return $res->json();
  }
}
