<?php

namespace Wasateam\Laravelapistone\Helpers;

class EmailHelper
{
  public static function mail_send_newsleopard($view, $view_data, $subject, $from_name, $from_address, $recipients)
  {
    $content  = view($view, $view_data)->render();
    $response = Http::withHeaders([
      'x-api-key' => env('MAIL_API_KEY'),
    ])->post(env('MAIL_API_DOMAIN') . '/v1/messages', [
      "subject"     => $subject,
      "fromName"    => $from_name,
      "fromAddress" => $from_address,
      "content"     => $content,
      "recipients"  => $recipients,
    ]);
  }
}
