<?php

namespace Wasateam\Laravelapistone\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Wasateam\Laravelapistone\Models\Appointment;

class ServiceStoreAppointmentCancelNotifyMailJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $email;
  public $appointment_id;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($email, $appointment_id)
  {
    $this->email          = $email;
    $this->appointment_id = $appointment_id;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $appointment = Appointment::find($appointment_id);
    Mail::to($this->email)->send(new \Wasateam\Laravelapistone\Mail\ServiceStoreAppointmentsCancel(
      $appointment
    ));
  }
}
