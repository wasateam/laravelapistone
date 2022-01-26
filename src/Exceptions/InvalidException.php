<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class InvalidException extends Exception
{
  public function __construct($target = null)
  {
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
      'message' => 'Invalid ' . $this->target . '.',
    ];
    return response()->json($response_json, 400);
  }
}
