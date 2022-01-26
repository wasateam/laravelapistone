<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class FieldNotMatchException extends Exception
{
  public function __construct($field = null, $target = null)
  {
    $this->field  = $field;
    $this->target = $target;
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
      'message' => 'field not match.',
    ];
    if ($this->field) {
      $response_json['field'] = $this->field;
    }
    if ($this->target) {
      $response_json['target'] = $this->target;
    }
    return response()->json($response_json, 400);
  }
}
