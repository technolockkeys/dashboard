<?php

namespace App\Mail;

use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    use SetMailConfigurations;
    public  $user;
    /**
     *
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->setMailConfigurations();;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $this->setMailConfigurations();;
        return $this->view('email.payment_reminder',compact('user'));
    }
}
