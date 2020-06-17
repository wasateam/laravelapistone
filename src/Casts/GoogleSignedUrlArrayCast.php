<?php
namespace Wasateam\Laravelapistone\Casts;

use Wasateam\Laravelapistone\Helpers\StorageHelper;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class GoogleSignedUrlArrayCast implements CastsAttributes
{
  public function get($model, $key, $value, $attributes)
  {
    if (!$value) {
      return [];
    }
    $_value  = $value;
    if (is_string($_value)) {
      $to_json = json_decode($_value);
      if (json_last_error() === 0) {
        $_value = $to_json;
      } else {
        return [];
      }
    }
    foreach ($_value as $value_key => $value_item) {
      $_value[$value_key] = StorageHelper::get_google_file_signed_url(StorageHelper::get_file_path(urldecode($value_item)));
    }
    return $_value;
  }

  public function set($model, $key, $value, $attributes)
  {
    if (is_null($value)) {
      return;
    }
    $_value = $value;
    if (is_string($_value)) {
      $to_json = json_decode($_value);
      if (json_last_error() === 0) {
        $_value = $to_json;
      } else {
        $_value = [];
      }
    }
    foreach ($_value as $value_key => $value_item) {
      $_value[$value_key] = StorageHelper::get_pure_url($value_item);
    }
    return json_encode($_value);
  }
}
