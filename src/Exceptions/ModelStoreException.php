<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class ModelStoreException extends Exception
{

  public function __construct(
    $model_name,
    $request,
    $message = 'store error.'
  ) {
    $this->message    = $message;
    $this->model_name = $model_name;
    $this->request    = $request;
  }

  /**
   * Report the exception.
   *
   * @return bool|null
   */
  public function report()
  {

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
      'type'       => 'model store',
      'message'    => $this->message,
      'model_name' => $this->model_name,
      'request'    => $this->request->all(),
    ], 400);
  }
}
