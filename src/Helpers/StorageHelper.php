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

  public static function getRandomPath()
  {
    return time() . Str::random(5);
  }

  public static function get_google_file_signed_url($file_path)
  {
    $url = Storage::disk('gcs')->getAdapter()->getBucket()->object($file_path)
      ->signedUrl(now()->addMinutes(15));
    return $url;
  }

  public static function get_file_path($url)
  {
    return str_replace(env('GOOGLE_CLOUD_STORAGE_PATH') . "/" . env('GOOGLE_CLOUD_STORAGE_BUCKET') . "/", '', $url);
  }

  public static function get_pure_url($url)
  {
    $parse_url = parse_url($url);
    return $parse_url['scheme'] . '://' . $parse_url['host'] . $parse_url['path'];
  }

  public static function get_signed_url_info_by_store_data($store_file, $with_parent = false)
  {
    $arr  = explode('/', $store_file);
    $info = collect();
    if ($with_parent) {
      $info->parent    = $arr[0];
      $info->parent_id = $arr[1];
      $info->type      = $arr[2];
      $info->repo      = $arr[3];
      $info->name      = $arr[4];
    } else {
      $info->type = $arr[0];
      $info->repo = $arr[1];
      $info->name = $arr[2];
    }
    return $info;
  }

  public static function get_signed_url($repo, $filename, $type, $parent = null, $parent_id = null)
  {
    $options = [
      'type' => $type,
      'repo' => $repo,
      'name' => $filename,
    ];
    if ($parent) {
      $options['parent']    = $parent;
      $options['parent_id'] = $parent_id;
      return URL::temporarySignedRoute(
        'file_show_with_parent', now()->addMinutes(15), $options);
    } else {
      return URL::temporarySignedRoute(
        'file_show', now()->addMinutes(15), $options);
    }
  }

  public static function get_signed_url_by_link($link, $with_parent = false)
  {
    $info = self::get_signed_url_info_by_store_data($link, $with_parent);
    if ($with_parent) {
      $value = self::get_signed_url($info->repo, $info->name, $info->type, $info->parent, $info->parent_id);
    } else {
      $value = self::get_signed_url($info->repo, $info->name, $info->type);
    }
    return $value;
  }

  public static function get_store_data_by_url($url, $with_parent = false)
  {
    $parse_url = parse_url($url);
    $arr       = explode('/', $parse_url['path']);
    if ($with_parent) {
      $value = "{$arr[3]}/{$arr[4]}/{$arr[5]}/{$arr[6]}/{$arr[7]}";
    } else {
      $value = "{$arr[3]}/{$arr[4]}/{$arr[5]}";
    }
    return $value;
  }
}
