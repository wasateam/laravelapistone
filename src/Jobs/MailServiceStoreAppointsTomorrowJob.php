<?php

namespace Wasateam\Laravelapistone\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MailServiceStoreAppointsTomorrowJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $mail;
  public $service_store_id;
  public $date;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($mail, $service_store_id, $date)
  {
    $this->mail             = $mail;
    $this->service_store_id = $service_store_id;
    $this->date             = $date;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    \Wasateam\Laravelapistone\Helpers\ServiceStoreHelper::MailServiceStoreAppointsTomorrow($this->mail, $this->service_store_id, $this->date
    );
  }
}
