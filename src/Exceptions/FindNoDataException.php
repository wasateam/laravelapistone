<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class FindNoDataException extends Exception
{
  public function __construct($model = null, $check_value = null, $check_field = 'id')
  {
    $this->model       = $model;
    $this->check_value = $check_value;
    $this->check_field = $check_field;
  }

  /**
   * Report the exception.
   *
   * @return bool|null
   */
  public function report()
  {
    //
  }

  /**
   * Render the exception as an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function render($request)
  {
    $response_json = [
      'message' => 'find no data.',
    ];
    if ($this->check_value) {
      $response_json[$this->check_field] = $this->check_value;
    }
    // if ($this->model) {
      $response_json['model'] = $this->model;
    // }
    return response()->json($response_json, 400);
  }
}
