<?php
namespace Wasateam\Laravelapistone\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TimeCast implements CastsAttributes
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
    if ($value) {
      return \Carbon\Carbon::parse("{$value}");
    } else {
      return null;
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
    if ($value) {
      return \Carbon\Carbon::parse("{$value}");
    } else {
      return null;
    }
  }
}
