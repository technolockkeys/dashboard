<?php

namespace App\Mail;

use App\Models\Brand;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PincodeMail extends Mailable
{
    use Queueable, SerializesModels;

    private Order $order;
    private string $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->order = Order::find($id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (empty($this->order)) {
            return;
        }
        $order = $this->order;
        $note = json_decode($order->note, true);

        $brand = Brand::find($note['brand']);
        return $this->view('backend.mails.pincode', compact('order', 'brand' ,'note'));
    }
}
