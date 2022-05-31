<?php

namespace Wasateam\Laravelapistone\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDelayInvoiceJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $invoice_job;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($invoice_job)
  {
    $this->invoice_job = $invoice_job;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    if ($this->invoice_job->shop_order->status == 'cancel-complete') {
      return;
    }

    try {
      \Wasateam\Laravelapistone\Helpers\ShopHelper::createInvoice($this->invoice_job->shop_order);
      $this->invoice_job->status = 'invoiced';
      $this->invoice_job->save();
    } catch (\Throwable $th) {
      $this->invoice_job->status = 'fail';
      $this->invoice_job->save();
    }
  }
}
