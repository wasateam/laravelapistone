<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class AcumaticaException extends Exception
{

  public function __construct(
    $message = 'acumatica error.'
  ) {
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
    return response()->json([
      'type'    => 'acumatica',
      'message' => $this->message,
    ], 400);
  }
}
