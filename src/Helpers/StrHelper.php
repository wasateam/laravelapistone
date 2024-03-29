<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class StrHelper
{
  public static function generateRandomString($length = 10, $characters = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $type = "default")
  {
    $charactersLength = strlen($characters);
    $randomString     = '';
    if ($type == 'default') {
      for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
    } else if ($type == 'unixlast6') {
      if ($length < 9) {
        throw new \Wasateam\Laravelapistone\Exceptions\StringGenerateException('length not enough.');
      }
      for ($i = 0; $i < 2; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      $now_unix = Carbon::now()->timestamp;
      $randomString .= Str::substr($now_unix, 6, 4);
      for ($i = 0; $i < $length - 8; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
    }
    return $randomString;
  }

  public static function encodeString($str, $type)
  {
    if (!$str) {
      return $str;
    }
    if ($type == 'name') {
      $_str = Str::substr($str, 0, 1);
      for ($i = 1; $i < iconv_strlen($str) - 1; $i++) {
        $_str .= '*';
      }
      $_str .= Str::substr($str, iconv_strlen($str) - 1, 1);
    }
    if ($type == 'tel') {
      $_str    = $str;
      $_str[4] = '*';
      $_str[5] = '*';
      $_str[6] = '*';
    }
    return $_str;
  }

  public static function strEncodeAES($string, $hash_key, $hasg_iv)
  {
    return openssl_encrypt($string, 'aes-128-cbc', $hash_key, 0, $hasg_iv);
  }

  public static function strDecodeAES($string, $hash_key, $hasg_iv)
  {
    return openssl_decrypt($string, 'aes-128-cbc', $hash_key, 0, $hasg_iv);
  }
}
