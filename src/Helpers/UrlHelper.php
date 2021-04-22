<?php

namespace Wasateam\Laravelapistone\Helpers;

class UrlHelper
{
  public static function getCleanUrl($url)
  {
    if (!$url) {
      return null;
    } else {
      $parse = parse_url($url);
      return $parse['scheme'] . '://' . $parse['host'] . '/' . $parse['path'];
      // return str_replace("/{$bucket}/", "", $parse['path']);
    }
  }
}
