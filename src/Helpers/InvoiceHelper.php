<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Wasateam\Laravelapistone\Models\InvoiceJob;

class InvoiceHelper
{
  public static function checkDelayInvoice()
  {
    $models = InvoiceJob::where('status', 'waiting')
      ->whereDate('invoice_date', '<=', \Carbon\Carbon::now())
      ->get();
    foreach ($models as $model) {
      \Wasateam\Laravelapistone\Jobs\CreateDelayInvoiceJob::dispatch($model);
    }
  }
}
