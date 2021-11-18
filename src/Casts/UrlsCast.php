<?php
namespace Wasateam\Laravelapistone\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class UrlsCast implements CastsAttributes
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
    return json_decode($value, true);
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
    if (config('stone.post_encode')) {
      $_value = base64_decode($value);
      error_log($_value);
    } else {
      $_value = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    return $_value;
  }
}
