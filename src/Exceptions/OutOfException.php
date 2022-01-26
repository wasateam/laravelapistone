<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class OutOfException extends Exception
{
  public function __construct($target, $model = null, $id = null)
  {
    $this->target = $target;
    $this->model  = $model;
    $this->id     = $id;
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
      'message' => 'out of ' . $this->target . '.',
    ];
    if ($this->id) {
      $response_json['id'] = $this->id;
    }
    if ($this->model) {
      $response_json['model'] = $this->model;
    }
    return response()->json($response_json, 400);
  }
}
