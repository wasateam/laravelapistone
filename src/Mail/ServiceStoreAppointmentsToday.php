<?php

namespace Wasateam\Laravelapistone\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Wasateam\Laravelapistone\Helpers\LangHelper;

class ServiceStoreAppointmentsToday extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($service_store, $formated_appointments)
  {
    $this->service_store         = $service_store;
    $this->formated_appointments = $formated_appointments;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $service_store = $this->service_store;
    $lang          = LangHelper::getLangFromCountryCode($service_store->country_code);
    $title   = __('wasateam::messages.本日收到新預約', [], $lang);
    $subject = "{$service_store->name}-{$title}";
    $link    = env('CMS_URL') . '/appointment';
    return $this->subject($subject)->view('wasateam::wasa.mail.service_store_appointments_today')->with([
      'service_store'         => $this->service_store,
      'formated_appointments' => $this->formated_appointments,
      'link'                  => $link,
      'lang'                  => $lang,
    ]);
  }
}
