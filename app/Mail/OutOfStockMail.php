<?php

namespace App\Mail;

use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OutOfStockMail extends Mailable implements ShouldQueue
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
        $type='out_of_stock';
        $user = $this->details['user'];
        $products = $this->details['products'];


        return $this->subject($this->subject)->view('email.reminder', ['details' => $this->details ,'type'=>$type ,'products'=>$products ,'user'=>$user]);
    }
}
