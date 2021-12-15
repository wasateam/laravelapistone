<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class FindNoUserServicePlanException extends Exception
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
      'message' => 'find no user service plan.',
    ], 400);
  }
}
