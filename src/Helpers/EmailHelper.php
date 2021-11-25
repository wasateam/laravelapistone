<?php

namespace Wasateam\Laravelapistone\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Wasateam\Laravelapistone\Mail\ContactRequestAutoReply;
use Wasateam\Laravelapistone\Mail\ContactRequestCreated;
use Wasateam\Laravelapistone\Mail\PasswordResetRequest;
use Wasateam\Laravelapistone\Mail\Test;
use Wasateam\Laravelapistone\Models\GeneralContent;

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
      ], '密碼重置請求', config('mail.from.name'), config('mail.from.address'),
        [
          [
            "address" => $email,
          ],
        ]
      );
    }
  }

  public static function notify_contact_request($contact_request, $mails)
  {
    if (config('stone.mail.service') == 'smtp') {
      Mail::to($mails)->send(new ContactRequestCreated($contact_request));
    } else if (config('stone.mail.service') == 'surenotify') {
      $recipients = [];
      foreach ($mails as $mail) {
        $recipients[] = [
          "address" => $email,
        ];
      }
      self::mail_send_surenotify('wasa.mail.contact_request', [
        'contact_request' => $contact_request,
      ], 'New Contact Request', config('mail.from.name'), config('mail.from.address'), $recipients);
    }
  }

  public static function sending_test($email)
  {
    if (config('stone.mail.service') == 'stmp') {
      Mail::to($email)->send(new Test());
    } else if (config('stone.mail.service') == 'surenotify') {
      self::mail_send_surenotify('wasa.mail.test', [
        'aa' => 'aa',
      ], '測試測試', config('mail.from.name'), config('mail.from.address'),
        [
          [
            "address" => $email,
          ],
        ]
      );
    }
  }

  public static function contact_request_auto_reply($contact_request)
  {
    $snap = GeneralContent::where('name', 'contact-request-auto-reply');
    if ($contact_request->country_code) {
      $snap = $snap->where('country_code', $contact_request->country_code);
    } else {
      $snap = $snap->whereNull('country_code');
    }
    $auto_reply = $snap->first();
    if (!$auto_reply) {
      return;
    }

    if (config('stone.mail.service') == 'smtp') {
      Mail::to($contact_request->email)->send(new ContactRequestAutoReply($auto_reply));
    } else if (config('stone.mail.service') == 'surenotify') {
      $recipients = [
        [
          "address" => $contact_request->email,
        ],
      ];
      self::mail_send_surenotify('wasa.mail.contact_request_auto_reply', [
        'auto_reply'=>$auto_reply
      ], '', config('mail.from.name'), config('mail.from.address'), $recipients);
    }
  }
}
