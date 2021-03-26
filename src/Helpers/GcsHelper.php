<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Str;
use Storage;

class GcsHelper
{
  public static function getUploadSignedUrlByNameAndPath($file_name, $file_path, $contentType = 'image/svg+xml')
  {
    $type = strtolower(explode('.', $file_name)[count(explode('.', $file_name)) - 1]);
    if ($type == 'svg') {
      $contentType = 'image/svg+xml';
    } else if ($type == 'ico' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
      $contentType = 'image/*';
    } else {
      $contentType = '*';
    }

    $disk        = Storage::disk('gcs');
    $random_path = self::getRandomPath();
    $path        = "{$file_path}/{$random_path}/{$file_name}";
    $url         = Storage::disk('gcs')->getAdapter()->getBucket()->object($path)->beginSignedUploadSession([
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
      return str_replace("/{$bucket}/", "", $parse['path']);
    }
  }

  public static function getSignedUrl($file_path)
  {
    $url = Storage::disk('gcs')->getAdapter()->getBucket()->object($file_path)
      ->signedUrl(now()->addMinutes(15));
    return $url;
  }
}
