<?php

namespace Wasateam\Laravelapistone\Helpers;

use Wasateam\Laravelapistone\Models\PocketImage;
use Wasateam\Laravelapistone\Models\PocketImageVersion;

class PocketHelper
{
  public static function get_pocket_url($pocket_image)
  {
    $last_version = ($pocket_image && isset($pocket_image->last_version)) ? $pocket_image->last_version : null;
    if (!$last_version) {
      return null;
    } else {
      if ($last_version->signed) {
        return $last_version->signed_url;
      } else {
        return $last_version->url;
      }
    }
  }

  public static function create_pocket_image($url, $signed_url = null, $name, $signed)
  {
    $pocket_image = new PocketImage;
    $pocket_image->save();
    $pocket_image_version = new PocketImageVersion;
    if (config('stone.post_encode')) {
      $pocket_image_version->url = base64_encode($url);
    } else {
      $pocket_image_version->url = $url;
    }
    if ($signed_url) {
      if (config('stone.post_encode')) {
        $pocket_image_version->signed_url = base64_encode($signed_url);
      } else {
        $pocket_image_version->signed_url = $signed_url;
      }
    }
    $pocket_image_version->name            = $name;
    $pocket_image_version->signed          = $signed;
    $pocket_image_version->pocket_image_id = $pocket_image->id;
    $pocket_image_version->save();

  }
}
