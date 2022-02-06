<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class FieldRequiredException extends Exception
{
  public function __construct($field)
  {
    $this->field = $field;
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
      'message' => $this->field . ' required.',
    ];
    return response()->json($response_json, 400);
  }
}
