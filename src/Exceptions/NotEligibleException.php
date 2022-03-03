<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class NotEligibleException extends Exception
{
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
      'message' => 'not eligible',
    ], 400);
  }
}
