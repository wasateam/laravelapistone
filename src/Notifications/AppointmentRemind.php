<?php

namespace Wasateam\Laravelapistone\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Wasateam\Laravelapistone\Helpers\TimeHelper;

class AppointmentRemind extends Notification
{
  use Queueable;

  protected $appointment;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($appointment)
  {
    $this->appointment = $appointment;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $appointment = $this->appointment;
    if (config('stone.country_code')) {
      $timezone = TimeHelper::getTimeZoneFromCountryCode($appointment->country_code);
      $time     = Carbon::parse($appointment->start_at)->timezone($timezone);
    } else {
      $time = Carbon::parse($appointment->start_at);
    }
    return (new MailMessage)
      ->view('wasateam::wasa.mail.appointment.remind', [
        'time'                  => $time->format('Y-m-d H:m:s'),
        'service_store_name'    => $appointment->service_store->name,
        'service_store_address' => $appointment->service_store->address,
      ]);
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      //
    ];
  }
}
