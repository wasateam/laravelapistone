<?php

namespace Wasateam\Laravelapistone\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactRequestAutoReply extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($auto_reply)
  {
    $this->auto_reply = $auto_reply;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->subject($this->auto_reply->content['title'])->view('wasa.mail.contact_request_auto_reply')->with([
      'content' => $this->auto_reply->content['content'],
    ]);
  }
}
