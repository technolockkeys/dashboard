<?php

namespace App\Mail;

use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOfferMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, SetMailConfigurations;

    public $details;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $details)
    {
        $this->details = $details;
        $this->subject = $subject;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setMailConfigurations();

        return $this->subject($this->subject)->view('email.offer', ['details' => $this->details]);
    }
}
