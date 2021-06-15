<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Mail;
use Wasateam\Laravelapistone\Mail\PasswordResetRequest;

class EmailHelper
{
  public static function mail_send_surenotify($view, $view_data, $subject, $from_name, $from_address, $recipients)
  {

    $content  = view($view, $view_data)->render();
    $response = Http::withHeaders([
      'x-api-key' => config('stone.mail.api_key'),
    ])->post(config('stone.mail.api_domain') . '/v1/messages', [
      "subject"     => $subject,
      "fromName"    => $from_name,
      "fromAddress" => $from_address,
      "content"     => $content,
      "recipients"  => $recipients,
    ]);
  }

  public static function password_reset_request($url, $email)
  {
    if (config('stone.mail.service') == 'stmp') {
      Mail::to($user->email)->send(new PasswordResetRequest($url));
    } else if (config('stone.mail.service') == 'surenotify') {
      self::mail_send_surenotify('wasa.mail.reset_password_request', [
        'url' => $url,
      ], '密碼重置請求', config('mail.from.name'), config('mail.from.address'), [
        "address" => $email,
      ]);
    }
  }
}
