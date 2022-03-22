<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class GeneralException extends Exception
{
  public function __construct($message)
  {
    $this->message = $message;
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
      'message' => $this->message,
    ];
    return response()->json($response_json, 400);
  }
}
