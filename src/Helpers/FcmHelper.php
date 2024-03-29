<?php

namespace Wasateam\Laravelapistone\Helpers;

use Kreait\Firebase\Messaging\CloudMessage;

class FcmHelper
{
  public static function sendMesssage($title, $body, $data, $tokens)
  {
    if (!$tokens || !count($tokens)) {
      return;
    }
    $messaging    = app('firebase.messaging');
    $notification = \Kreait\Firebase\Messaging\Notification::fromArray([
      'title' => $title,
      'body'  => $body,
    ]);
    $message = CloudMessage::new ()->withNotification($notification)->withData($data);
    $report  = $messaging->sendMulticast($message, $tokens);
  }
}
