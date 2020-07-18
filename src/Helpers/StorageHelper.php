<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\URL;
use Storage;
use Str;

class StorageHelper
{
  public static function get_google_upload_signed_url_by_name_and_path($file_name, $file_path)
  {
    $disk        = Storage::disk('gcs');
    $random_path = self::getRandomPath();
    $path        = "{$file_path}/{$random_path}/{$file_name}";
    $url         = Storage::disk('gcs')->getAdapter()->getBucket()->object($path)->beginSignedUploadSession();
    return [
      'file_url' => env('GOOGLE_CLOUD_STORAGE_PATH') . "/" . env('GOOGLE_CLOUD_STORAGE_BUCKET') . "/" . "{$file_path}/{$random_path}/{$file_name}",
      'put_url'  => $url,
    ];
  }

  public static function get_google_file_signed_url($file_path)
  {
    $url = Storage::disk('gcs')->getAdapter()->getBucket()->object($file_path)
      ->signedUrl(now()->addMinutes(15));
    return $url;
  }

  public static function getRandomPath()
  {
    return time() . Str::random(5);
  }

  // public static function get_signed_url($repo, $filename, $model, $type, $parent = null, $parent_id = null)
  // {
  //   $options = [
  //     'model' => $model,
  //     'repo'  => $repo,
  //     'name'  => $filename,
  //     'type'  => $type,
  //   ];
  //   if ($parent) {
  //     $options['parent']    = $parent;
  //     $options['parent_id'] = $parent_id;
  //     return URL::temporarySignedRoute(
  //       'file_show_with_parent', now()->addMinutes(15), $options);
  //   } else {
  //     return URL::temporarySignedRoute(
  //       'file_general', now()->addMinutes(15), $options);
  //   }
  // }

  public static function getSignedUrlOptionsByUrl($url, $signed_type = "general")
  {
    $parse_url   = parse_url($url);
    $store_value = str_replace("/api/", "", $parse_url['path']);
    return self::getOptionsByStoreValue($store_value, $signed_type);
  }

  public static function getOptionsByStoreValue($store_value, $signed_type = "general")
  {
    $store_value_arr = explode('/', $store_value);
    if ($signed_type == 'general') {
      if (count($store_value_arr) != 4) {
        return null;
      }
      return [
        'type'  => $store_value_arr[0],
        'model' => $store_value_arr[1],
        'repo'  => $store_value_arr[2],
        'name'  => $store_value_arr[3],
      ];
    } else if ($signed_type == 'idmatch') {
      if (count($store_value_arr) != 5) {
        return null;
      }
      return [
        'model'    => $store_value_arr[0],
        'model_id' => $store_value_arr[1],
        'type'     => $store_value_arr[2],
        'repo'     => $store_value_arr[3],
        'name'     => $store_value_arr[4],
      ];
    }
  }

  public static function getSignedUrlByStoreValue($store_value, $signed_type = "general")
  {
    if (!$store_value) {
      return null;
    }
    $options = self::getOptionsByStoreValue($store_value, $signed_type);
    return URL::temporarySignedRoute(
      "file_{$signed_type}", now()->addMinutes(15), $options);
  }

  public static function getSignedUrlStoreValue($url)
  {
    if (!$url) {
      return null;
    }
    $parse_url = parse_url($url);
    if (!$parse_url || !$parse_url['path']) {
      return null;
    } else {
      return str_replace("/api/", "", $parse_url['path']);
    }
  }
}
