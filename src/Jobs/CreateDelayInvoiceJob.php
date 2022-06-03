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
      $res = \Wasateam\Laravelapistone\Helpers\ShopHelper::createInvoice($this->invoice_job->shop_order);
      if ($res->code == 1) {
        $this->invoice_job->status = 'invoiced';
        $this->invoice_job->save();
      } else if ($res->code == 6) {
        $this->invoice_job->status = 'ignore';
        $this->invoice_job->save();
      } else {
        $this->invoice_job->status  = 'fail';
        $this->invoice_job->err_msg = $res->msg;
        $this->invoice_job->save();
      }
    } catch (\Throwable $th) {
      throw $th;
      $this->invoice_job->status = 'fail';
      $this->invoice_job->save();
    }
  }
}
