<?php

namespace Wasateam\Laravelapistone\Helpers;

use Kreait\Firebase\Messaging\CloudMessage;

class FcmHelper
{
  public static function sendMesssage($title, $message, $data, $tokens)
  {
    $messaging    = app('firebase.messaging');
    $notification = \Kreait\Firebase\Messaging\Notification::fromArray([
      'title' => $title,
      'body'  => $message,
    ]);
    $message    = CloudMessage::new ()->withNotification($notification)->withData($data);
    $sendReport = $messaging->sendMulticast($message, $tokens);
  }
}
