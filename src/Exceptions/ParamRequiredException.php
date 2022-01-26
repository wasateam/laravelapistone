<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class ParamRequiredException extends Exception
{
  public function __construct($param = null)
  {
    $this->param = $param;
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
      'message' => 'Param ' . $this->param . ' required.',
    ];
    return response()->json($response_json, 400);
  }
}
