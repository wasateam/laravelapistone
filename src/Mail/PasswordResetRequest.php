<?php

namespace Wasateam\Laravelapistone\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetRequest extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($url)
  {
    $this->url = $url;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $format_url = \Str::replace(config('stone.app_url') . '/api', config('stone.web_url'), $this->url);
    return $this->subject('密碼重置請求')->view('wasa.mail.reset_password_request')->with([
      'url' => $format_url,
    ]);
  }
}
