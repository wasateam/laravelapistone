<?php

namespace Wasateam\Laravelapistone\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewAppointmentNotifyJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $appointment;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($appointment)
  {
    $this->appointment = $appointment;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    \Wasateam\Laravelapistone\Helpers\AppointmentHelper::newAppointmentNotify($this->appointment);
  }
}
