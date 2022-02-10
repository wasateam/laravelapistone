<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class EcpayException extends Exception
{
  public function __construct($action = null, $rtn_code = null, $rtn_message = null)
  {
    $this->action   = $action;
    $this->rtn_code = $rtn_code;
    $this->rtn_message  = $rtn_message;
  }

  /**
   * Report the exception.
   *
   * @return bool|null
   */
  public function report()
  {
    //
    return false;
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
      'action'   => $this->action,
      'rtn_code' => $this->rtn_code,
      'rtn_message'  => $this->rtn_message,
    ];
    return response()->json($response_json, 400);
  }
}
