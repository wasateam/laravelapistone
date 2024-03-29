<?php
namespace Wasateam\Laravelapistone\Casts;

use Auth;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Wasateam\Laravelapistone\Helpers\StorageHelper;

class SignedUrlAuthCast implements CastsAttributes
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
    if (env('SIGNED_URL_MODE') == 'gcs') {
      return StorageHelper::getGcsSignedUrl($value);
    } else {
      return StorageHelper::getSignedUrlByStoreValue($value, 'idmatch', $this->signed_time);
    }
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
    if (env('SIGNED_URL_MODE') == 'gcs') {
      return StorageHelper::getGcsStoreValue($value);
    } else {
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
}
