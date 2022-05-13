<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Str;

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

  public static function getFormatedUrl($url, $tohttps = false, $tolocalhost = false, $toweb = false)
  {
    if ($tohttps) {
      if (
        (str_contains(config('stone.web_url'), 'https://') && str_contains(config('stone.app_url'), 'https://') && str_contains($url, 'http://')) ||
        (str_contains(config('stone.cms_url'), 'https://') && str_contains(config('stone.app_url'), 'https://') && str_contains($url, 'http://'))
      ) {
        $url = Str::replace('http', 'https', $url);
      }
    }
    if ($tolocalhost) {
      if (
        (str_contains(config('stone.web_url'), 'http://localhost') && str_contains(config('stone.app_url'), 'http://localhost')) ||
        (str_contains(config('stone.cms_url'), 'http://localhost') && str_contains(config('stone.app_url'), 'http://localhost'))
      ) {
        $url = Str::replace('127.0.0.1', 'localhost', $url);
      }
    }
    if ($toweb) {
      $url = Str::replace(config('stone.app_url') . '/api', config('stone.web_url'), $url);
    }
    return $url;
  }

}
