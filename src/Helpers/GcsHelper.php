<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Str;
use Storage;

class GcsHelper
{
  public static function getGcsUploadSignedUrlByNameAndPath($file_name, $file_path, $contentType = 'image/*')
  {
    // try {
    $disk        = Storage::disk('gcs');
    $random_path = self::getRandomPath();
    $path        = "{$file_path}/{$random_path}/{$file_name}";
    $url         = Storage::disk('gcs')->getAdapter()->getBucket()->object($path)->beginSignedUploadSession([
      'contentType' => $contentType,
    ]);
    return response()->json($url, 200);
    // } catch (\Throwable $th) {
    //   return response()->json([
    //     'message' => 'get signed url error.',
    //   ]);
    // }
  }

  public static function getRandomPath()
  {
    return time() . Str::random(5);
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
}
