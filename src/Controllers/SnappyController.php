<?php

namespace Wasateam\Laravelapistone\Controllers;

use App\Http\Controllers\Controller;

class SnappyController extends Controller
{
  public function test()
  {
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML('<h1>Test</h1>');
    return $pdf->inline();
  }
}
