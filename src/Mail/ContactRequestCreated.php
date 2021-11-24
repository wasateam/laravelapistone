<?php

namespace Wasateam\Laravelapistone\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactRequestCreated extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($contact_request)
  {
    $this->contact_request = $contact_request;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $fields = config('stone.contact_request.fields');
    return $this->subject('New Contact Request')->view('wasa.mail.contact_request')->with([
      'contact_request' => $this->contact_request,
      'fields'          => $fields,
    ]);
  }
}
