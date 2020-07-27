<?php
namespace Wasateam\Laravelapistone\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Wasateam\Laravelapistone\Helpers\StorageHelper;

class SignedUrlGeneralArrayCast implements CastsAttributes
{
  /**
   * Cast the given value.
   *
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @param  string  $key
   * @param  mixed  $value
   * @param  array  $attributes
   * @return array
   */
  public function get($model, $key, $value, $attributes)
  {
    if (!$value) {
      return [];
    }
    $_value = [];
    if (is_string($value)) {
      $to_json = json_decode($value);
      if (json_last_error() === 0) {
        $value = $to_json;
      } else {
        return [];
      }
    }
    foreach ($value as $value_key => $value_item) {
      $_display_value = StorageHelper::getSignedUrlByStoreValue($value_item, 'general');
      if ($_display_value) {
        $_value[$value_key] = $_display_value;
      }
    }
    return $_value;
  }

  /**
   * Prepare the given value for storage.
   *
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @param  string  $key
   * @param  array  $value
   * @param  array  $attributes
   * @return string
   */
  public function set($model, $key, $value, $attributes)
  {
    if (!$value) {
      return [];
    }
    $_value = $value;
    if (is_string($_value)) {
      $to_json = json_decode($_value);
      if (json_last_error() === 0) {
        $_value = $to_json;
      } else {
        return [];
      }
    }
    foreach ($_value as $value_key => $value_item) {
      $_store_value = StorageHelper::getSignedUrlStoreValue($value_item);
      if ($_store_value) {
        $_value[$value_key] = $_store_value;
      }
    }
    return json_encode($_value);
  }
}
