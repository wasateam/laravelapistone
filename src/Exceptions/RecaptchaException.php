<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class RecaptchaException extends Exception
{
  public function __construct()
  {
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
      'message' => 'recaptcha fail.',
    ], 400);
  }
}
