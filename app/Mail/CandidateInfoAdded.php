<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class CandidateInfoAdded extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateInfo;
    public $title;
    public $subject;
    public $greetings;
    public $signature;
    public $text = ''; // Add this line
    public  $htmlString = '';
    public  $with = '';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($candidateInfo, $subject, $greetings, $signature , $title)
    {
        $this->candidateInfo = $candidateInfo;
        $this->subject = $subject;
        $this->greetings = $greetings;
        $this->signature = $signature;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */


    public function build()
    {
        return $this->subject($this->subject)
            ->view('Mail.candidate_info_added')
            ->with([
                'candidateInfo' => $this->candidateInfo,
                'greetings' => $this->greetings,
                'signature' => $this->signature,
                'title' => $this->title,
            ]);
    }
}
