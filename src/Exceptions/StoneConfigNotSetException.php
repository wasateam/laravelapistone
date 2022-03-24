<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class StoneConfigNotSetException extends Exception
{
  public function __construct($config_name = null)
  {
    $this->config_name = $config_name;
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
      'message' => $this->config_name,
    ];
    return response()->json($response_json, 400);
  }
}
