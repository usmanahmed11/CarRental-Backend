<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function envelope()
    {
        return new Envelope(
            subject: $this->data['subject'],
            cc: $this->data['cc'],
            bcc: $this->data['bcc'],
        );
    }
    public function content()
    {
        return new Content(
            view: 'Mail.custom',
            with: ['greetings' => $this->data['greetings'], 'signature' => $this->data['signature']],

        );
    }
}
