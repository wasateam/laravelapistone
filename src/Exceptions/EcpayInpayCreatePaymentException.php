<?php

namespace Wasateam\Laravelapistone\Exceptions;

use Exception;

class EcpayInpayCreatePaymentException extends Exception
{
  public function __construct($response = null)
  {
    $this->response = $response;
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
      'message' => 'createPayment error.',
    ];
    return response()->json($response_json, 400);
  }
}
