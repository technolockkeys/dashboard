<?php

namespace App\Mail;

use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductAlertsMail extends Mailable implements ShouldQueue
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
        $this->subject = $subject;
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setMailConfigurations();

        return $this->subject($this->subject)->view('backend.mails.product_quantity', ['details' => $this->details]);
    }
}
