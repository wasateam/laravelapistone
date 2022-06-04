<?php

namespace Wasateam\Laravelapistone\Helpers;

use Carbon\Carbon;
use Wasateam\Laravelapistone\Models\InvoiceJob;

class InvoiceHelper
{
  public static function checkDelayInvoice()
  {
    InvoiceJob::where('status', 'waiting')
      ->whereDate('invoice_date', '<=', \Carbon\Carbon::now())
      ->update([
        'status' => 'queue',
      ]);
    $models = InvoiceJob::where('status', 'queue')
      ->get();
    foreach ($models as $model) {
      \Wasateam\Laravelapistone\Jobs\CreateDelayInvoiceJob::dispatch($model);
    }
  }
}
