<?php

namespace Wasateam\Laravelapistone\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Wasateam\Laravelapistone\Helpers\AppointmentHelper;
use Wasateam\Laravelapistone\Helpers\LangHelper;
use Wasateam\Laravelapistone\Models\Appointment;

class ServiceStoreAppointmentsCancel extends Mailable
{
  use Queueable, SerializesModels;
  public $appointment_id;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($appointment_id)
  {
    $this->appointment_id = $appointment_id;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $appointment          = Appointment::find($this->appointment_id);
    $formated_appointment = AppointmentHelper::getFormatedAppointmentForTable($appointment);
    $service_store        = $appointment->service_store;
    $lang                 = LangHelper::getLangFromCountryCode($service_store->country_code);
    $title                = __('wasateam::messages.預約已取消', [], $lang);
    $subject              = "{$service_store->name}-{$title}";
    $link                 = config('stone.cms_url') . '/appointment/' . $this->appointment_id;

    return $this->subject($subject)->view('wasateam::wasa.mail.service_store_appointment_cancel')->with([
      'service_store'        => $service_store,
      'formated_appointment' => $formated_appointment,
      'link'                 => $link,
      'lang'                 => $lang,
    ]);
  }
}
