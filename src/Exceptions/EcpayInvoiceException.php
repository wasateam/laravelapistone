<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class EcpayInvoiceException extends Exception
{
  public function __construct(
    $action = null,
    $rtn_code = null,
    $rtn_message = null,
    $trans_code = null,
    $trans_message = null
  ) {
    $this->action        = $action;
    $this->trans_code    = $trans_code;
    $this->trans_message = $trans_message;
    $this->rtn_code      = $rtn_code;
    $this->rtn_message   = $rtn_message;
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
      'action' => $this->action,
    ];
    if (config('app.debug')) {
      $response_json['trans_code']    = $this->trans_code;
      $response_json['trans_message'] = $this->trans_message;
      $response_json['rtn_code']      = $this->rtn_code;
      $response_json['rtn_message']   = $this->rtn_message;
    }
    return response()->json($response_json, 400);
  }
}
