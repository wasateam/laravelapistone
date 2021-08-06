<?php

namespace Wasateam\Laravelapistone\Channels;

use Illuminate\Notifications\Notification;
use Wasateam\Laravelapistone\Helpers\FcmHelper;

class FCMChannel
{
  /**
   * Send the given notification.
   *
   * @param  mixed  $notifiable
   * @param  \Illuminate\Notifications\Notification  $notification
   * @return void
   */
  public function send($notifiable, Notification $notification)
  {
    $noti = $notification->toFCM($notifiable);
    FcmHelper::sendMesssage($noti['title'], $noti['body'], $noti['data'], $notifiable->device_tokens);
  }
}
