<?php

namespace Wasateam\Laravelapistone\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceStoreTomorrowAppointments extends Notification
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
    return [
      'database',
      'mail',
    ];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
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
    ];
  }
}
