<?php

namespace Wasateam\Laravelapistone\Helpers;

use Kreait\Firebase\Messaging\CloudMessage;

class FcmHelper
{
  public static function sendMesssage($title, $body, $data, $tokens)
  {
    $messaging    = app('firebase.messaging');
    $notification = \Kreait\Firebase\Messaging\Notification::fromArray([
      'title' => $title,
      'body'  => $body,
    ]);
    $message = CloudMessage::new ()->withNotification($notification)->withData($data);
    $report  = $messaging->sendMulticast($message, $tokens);
    // if ($report->hasFailures()) {
    //   foreach ($report->failures()->getItems() as $failure) {
    //     echo $failure->error()->getMessage() . PHP_EOL;
    //   }
    // }
  }
}
