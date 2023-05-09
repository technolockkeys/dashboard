<?php

namespace App\Mail;

use App\Models\Address;
use App\Models\Currency;
use App\Models\Order;
use App\Models\User;
use App\Traits\OrderTrait;
use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendInvoideOrder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, SetMailConfigurations, OrderTrait;

    //order id
    public $order;


    public function __construct($order_id)
    {
        $this->order = $order_id;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setMailConfigurations();
//        //order
//        $order = Order::query()->find($this->order);
//        //user
//        $user = User::query()->where('id', $order->user_id)->first();
//        $view=view('invoice.mail')->render();
//        $view =  view('seller.after_sales.email' ,compact('name','title'))->render();
        $pdf = new \Mpdf\Mpdf();
        $pdf->charset_in = 'iso-8859-4';
        $order = Order::findOrFail($this->order);
        $products = $order->order_products()->whereNUll('orders_products.parent_id')->withTrashed()->get();
        $address = Address::find($order->address_id);
        $user = User::where('id', $order->user_id)->withTrashed()->first();
        $currency = Currency::find($order->currency_id);
        $qr = $this->generateQrCode($order->uuid);
        $view = view('invoice.invoice', compact('order', 'user', 'products', 'address', 'qr', 'currency'))->render();
        $pdf->WriteHTML($view);
        $name = 'order' . '_' . $order->uuid . '_' . \Carbon\Carbon::now() . '.pdf';
        $subject = trans('backend.order.order_number', ['num' => $order->uuid]);
        if ($order->type == Order::$proforma) {
            $subject = trans('backend.order.proforma_number', ['num' => $order->uuid]);

        }
        $content = $pdf->Output($name, 'S');
        return $this->subject($subject)->html($view)->attachData($content, $name, [
            'mime' => 'application/pdf',
        ]);

//        ->attach($pdf->Output($name, 'D'));
    }
}
