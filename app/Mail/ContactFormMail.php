<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
  use Queueable, SerializesModels;

  public array $data;

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function build()
  {
    return $this
      ->from(config('mail.from.address'), config('mail.from.name'))
      ->replyTo($this->data['email'], $this->data['name'])
      ->subject($this->data['subject'])
      ->view('emails.contact')
      ->with([
        'name' => $this->data['name'],
        'email' => $this->data['email'],
        'subject' => $this->data['subject'],
        'body' => $this->data['message'],
      ]);
  }
}
