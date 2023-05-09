<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Seller;
use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, SetMailConfigurations;

    public $emails;
    public $name;
    public $subject;
    public $Mailfrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $name, string $Mailfrom = null)
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->Mailfrom = $Mailfrom;
    }


    public function build()
    {

        $this->setMailConfigurations();
        $name = $this->name;
        $title = $this->subject;
        if (!empty($this->Mailfrom)) {
            $seller = Seller::query()->where('email', $this->Mailfrom)->first();
            $this->from($this->Mailfrom, 'TLK Feedback ' . (!empty($seller?->name) ? '|' . $seller->name : ""));
        }
        $view = view('seller.after_sales.email', compact('name', 'title'))->render();

        return $this->subject($this->subject)->html($view);
    }
}
