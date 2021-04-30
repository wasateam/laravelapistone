<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Str;
use Storage;

class GcsHelper
{
  public static function copyObject($oriPath, $newPath, $oriBucket = null, $newBucket = null)
  {
    if (!$oriBucket) {
      $oriBucket = Storage::disk('gcs')->getAdapter()->getBucket();
    }
    if (!$newBucket) {
      $newBucket = Storage::disk('gcs')->getAdapter()->getBucket();
    }

    $oriObject = $oriBucket->object($oriPath);

    $oriObject->copy($newBucket, [
      'name' => $newPath,
    ]);
    return;
  }

  public static function getUploadSignedUrlByNameAndPath($file_name, $file_path, $contentType = '*')
  {
    $type = strtolower(explode('.', $file_name)[count(explode('.', $file_name)) - 1]);
    if ($type == 'svg') {
      $contentType = 'image/svg+xml';
    } else if ($type == 'ico' || $type == 'jpg' || $type == 'jpeg' || $type == 'png' || $type == 'gif') {
      $contentType = 'image/*';
    } else {
      $contentType = '*/*';
    }

    $disk        = Storage::disk('gcs');
    $random_path = self::getRandomPath();
    $path        = "{$file_path}/{$random_path}/{$file_name}";
    $object      = Storage::disk('gcs')->getAdapter()->getBucket()->object($path);
    $url         = $object->beginSignedUploadSession([
      'contentType' => $contentType,
    ]);
    return response()->json($url, 200);
  }

  public static function getRandomPath()
  {
    return time() . Str::random(5);
  }

  public static function getStoreValue($url)
  {
    if (!$url) {
      return null;
    } else {
      $parse  = parse_url($url);
      $stone  = config('stone');
      $bucket = config('stone.storage.gcs.bucket');
      $store  = str_replace("/{$bucket}/", "", $parse['path']);
      return urldecode($store);
    }
  }

  public static function getSignedUrl($file_path)
  {
    $url = Storage::disk('gcs')->getAdapter()->getBucket()->object($file_path)
      ->signedUrl(now()->addMinutes(15));
    return $url;
  }

  public static function makeUrlPublic($url)
  {
    $path   = self::getStoreValue($url);
    $object = Storage::disk('gcs')->getAdapter()->getBucket()->object($path);
    $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);
    return;
  }
}
