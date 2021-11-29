<?php

namespace Wasateam\Laravelapistone\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerify extends Mailable
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
    $format_url = str_replace(config('stone.app_url') . '/api', config('stone.web_url'), $this->url);
    return $this->subject(trans('email_verify'))->view('wasa.mail.email_verify')->with([
      'url' => $format_url,
    ]);
  }
}
