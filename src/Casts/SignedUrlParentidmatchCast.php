<?php
namespace Wasateam\Laravelapistone\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Wasateam\Laravelapistone\Helpers\StorageHelper;

class SignedUrlParentidmatchCast implements CastsAttributes
{

  public function __construct($signed_time = 15)
  {
    $this->signed_time = $signed_time;
  }

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
    return StorageHelper::getSignedUrlByStoreValue($value, 'parentidmatch', $this->signed_time);
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
    if (!isset($value)) {
      return null;
    }
    return StorageHelper::getSignedUrlStoreValue($value);
  }
}
