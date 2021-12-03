<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\URL;
use Storage;
use Str;

class StorageHelper
{
  public static function getGoogleUploadSignedUrlByNameAndPath($file_name, $file_path, $contentType = 'image/*')
  {
    try {
      $disk        = Storage::disk('gcs');
      $random_path = self::getRandomPath();
      $path        = "{$file_path}/{$random_path}/{$file_name}";
      $url         = Storage::disk('gcs')->getAdapter()->getBucket()->object($path)->beginSignedUploadSession([
        'contentType' => $contentType,
      ]);
      return response()->json([
        "put_url" => $url,
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'get signed url error.',
      ]);
    }
  }

  public static function getGcsStoreValue($url)
  {
    if (!$url) {
      return null;
    } else {
      $parse  = parse_url($url);
      $bucket = env('GOOGLE_CLOUD_STORAGE_BUCKET');
      return str_replace("/{$bucket}/", "", $parse['path']);
    }
  }

  public static function getGcsSignedUrl($file_path, $signed_time = 15)
  {
    $url = Storage::disk('gcs')->getAdapter()->getBucket()->object($file_path)
      ->signedUrl(now()->addMinutes($signed_time));
    return $url;
  }

  public static function getRandomPath()
  {
    return time() . Str::random(5);
  }

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
        'model' => $store_value_arr[0],
        'type'  => $store_value_arr[1],
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
    } else if ($signed_type == 'parentidmatch') {
      if (count($store_value_arr) != 6) {
        return null;
      }
      return [
        'parent'    => $store_value_arr[0],
        'parent_id' => $store_value_arr[1],
        'model'     => $store_value_arr[2],
        'type'      => $store_value_arr[3],
        'repo'      => $store_value_arr[4],
        'name'      => $store_value_arr[5],
      ];
    }
  }

  public static function getSignedUrlByStoreValue($store_value, $signed_type = "general", $signed_time = 15)
  {
    if (!$store_value) {
      return null;
    }
    $options = self::getOptionsByStoreValue($store_value, $signed_type);
    if (!$options) {
      return null;
    } else {
      return URL::temporarySignedRoute(
        "file_{$signed_type}", now()->addMinutes($signed_time), $options);
    }
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

  public static function getLocalData($path)
  {
    // if(Storage::disk('local')->ex)
    if (Storage::disk('local')->exists($path)) {
      return Storage::disk('local')->get($path);
    } else {
      return null;
    }
  }
}
