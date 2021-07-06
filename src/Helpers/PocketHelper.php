<?php

namespace Wasateam\Laravelapistone\Helpers;

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
}
