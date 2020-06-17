<?php
namespace App\Casts;

use Wasateam\Laravelapistone\Helpers\StorageHelper;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class SignedUrlWithParentCast implements CastsAttributes
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
      return null;
    }
    return StorageHelper::get_signed_url_by_link($value, true);
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
      return null;
    }
    return StorageHelper::get_store_data_by_url($value, true);
  }
}
