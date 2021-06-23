<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Str;

class S3Helper
{
  public static function getUploadSignedUrlByNameAndPath($file_name, $file_path, $contentType = '*', $is_public = false)
  {
    $random_path = self::getRandomPath();
    $path        = "{$file_path}/{$random_path}/{$file_name}";
    $client      = self::getClient();
    $options     = [
      'Bucket' => config('filesystems.disks.s3.bucket'),
      'Key'    => $path,
    ];
    if ($is_public) {
      $options['ACL'] = 'public-read';
    }
    $cmd          = $client->getCommand('PutObject', $options);
    $request      = $client->createPresignedRequest($cmd, '+15 minutes');
    $presignedUrl = (string) $request->getUri();
    return response()->json($presignedUrl, 200);
  }

  public static function getRandomPath()
  {
    return time() . Str::random(5);
  }

  public static function getClient()
  {
    $client = new \Aws\S3\S3Client([
      'version'     => 'latest',
      'region'      => config('filesystems.disks.s3.region'),
      'credentials' => [
        'key'    => config('filesystems.disks.s3.key'),
        'secret' => config('filesystems.disks.s3.secret'),
      ],
    ]);
    return $client;
  }
}
