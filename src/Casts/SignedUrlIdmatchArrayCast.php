<?php
namespace Wasateam\Laravelapistone\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Wasateam\Laravelapistone\Helpers\StorageHelper;

class SignedUrlIdmatchArrayCast implements CastsAttributes
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
      $options = StorageHelper::getOptionsByStoreValue($value_item, 'idmatch');
      if (!$options || $model->id != $options['model_id']) {
        $_value[$value_key] = null;
      } else {
        $_value[$value_key] = StorageHelper::getSignedUrlByStoreValue($value_item, 'idmatch');
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
      $_value[$value_key] = StorageHelper::getSignedUrlStoreValue($value_item);
    }
    return json_encode($_value);
  }
}
