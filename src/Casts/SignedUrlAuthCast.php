<?php
namespace Wasateam\Laravelapistone\Casts;

use Auth;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Wasateam\Laravelapistone\Helpers\StorageHelper;

class SignedUrlAuthCast implements CastsAttributes
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
    return StorageHelper::getSignedUrlByStoreValue($value, 'idmatch');
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
    $store_value = StorageHelper::getSignedUrlStoreValue($value, 'idmatch');
    $user        = Auth::user();
    $options     = StorageHelper::getOptionsByStoreValue($store_value, 'idmatch');
    if (!$options || $user->id != $options['model_id']) {
      return null;
    } else {
      return $store_value;
    }
  }
}
