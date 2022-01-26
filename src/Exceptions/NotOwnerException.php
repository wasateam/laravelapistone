<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class NotOwnerException extends Exception
{
  public function __construct($model = null, $id = null)
  {
    $this->model = $model;
    $this->id    = $id;
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
      'message' => ':(',
    ];
    return response()->json($response_json, 400);
  }
}
