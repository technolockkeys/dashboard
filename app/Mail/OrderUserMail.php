<?php

namespace App\Mail;

use App\Models\Address;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrdersProducts;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserWallet;
use App\Traits\OrderTrait;
use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class OrderUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, SetMailConfigurations, OrderTrait;

    public $details;
    public $subject;
    public $type;
    public $option;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $details, $type, array $option = null)
    {
        $this->subject = $subject;
        $this->details = $details;
        $this->type = $type;
        $this->option = $option;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $image = asset("mail/67611522142640957.png");
        $data_statement = [];
        $this->setMailConfigurations();
        $order = $this->details['order'];
        $order = Order::find($order->id);
        $seller = !empty($order->seller_id) ? Seller::find($order->seller_id) : null;
        $pdf = new \Mpdf\Mpdf();
        $currency = null;
        $currency = Currency::find($order->currency_id);
        $products = $order->order_products()->whereNUll('orders_products.parent_id')->withTrashed()->get();
        $address = Address::find($order->address_id);
        $user = User::where('id', $order->user_id)->withTrashed()->first();
        $qr = $this->generateQrCode($order->uuid);
        $view = view('invoice.invoice', compact('order', 'user', 'products', 'address', 'qr', 'currency'))->render();
        $pdf->WriteHTML(utf8_encode($view));
        $name = 'order' . '_' . $order->uuid . '_' . \Carbon\Carbon::now() . '.pdf';
        $subject = trans('backend.order.order_number', ['num' => $order->uuid]);
        if ($order->type == Order::$proforma) {
            $subject = trans('backend.order.proforma_number', ['num' => $order->uuid]);
        }
        $content = $pdf->Output($name, 'S');
        $products = OrdersProducts::query()->where('order_id', $order->id)->whereNUll('orders_products.parent_id')->get();

        if ($this->type == 'create' && $order->type != Order::$proforma) {
            $subject = "Your request has been received";
        } else if ($this->type == 'processing' && $order->type != Order::$proforma) {
            $subject = "Your request is in processing status";
        } else if ($this->type == 'failed' && $order->type != Order::$proforma) {
            $subject = "Your request is in failed status";
        } else if ($this->type == 'completed' && $order->type != Order::$proforma) {
            $subject = "Your request is in completed status";
        } else if ($this->type == 'statement' && !empty($this->option) && !empty($this->option['payment_recode'])) {
            $statement_content = [];
            $subject = "Payment";
            $statement_id = $this->option['payment_recode'];
            $userWallet = UserWallet::query()->where('id', $statement_id)->first();

            if (!empty($userWallet)) {
                if (!empty($userWallet->files)) {
                    foreach (json_decode($userWallet->files) as $item) {
                        $item = str_replace('//', '/', $item);
                        $item_name = explode('/', $item);
                        $statement_content[] = ['path' => \Storage::disk('public')->path($item), 'src' => $item, "mime" => \Storage::disk('public')->mimeType($item), 'name' => end($item_name)];

                    }
                }
                $data_statement['amount'] = \currency($userWallet->amount);
                $data_statement['type'] = $userWallet->type;
                $data_statement['balance_order'] = \currency(UserWallet::query()->where('order_id', $userWallet->order_id)
                    ->where('status', UserWallet::$approve)->sum('amount'));
                $image = asset('mail/invoice.png');
            }


        } elseif ($this->type == 'feedback') {
            $image = asset('mail/feedback.png');
        }

        $type = $this->type;
        $result = $this->subject($subject)->view('email.order', ['details' => $this->details, 'seller' => $seller, 'order' => $order, 'type' => $type, 'image' => $image, 'data_statement' => $data_statement, 'products' => $products]);
        if ($this->type != "feedback") {
            $result = $result->attachData($content, $name, [
                'mime' => 'application/pdf',
            ]);
        } elseif (!empty($seller->email)) {
            $this->from($seller->email);
        }
        if (!empty($statement_content)) {
            foreach ($statement_content as $attach) {

                if (\File::exists($attach['path']))
                    $result->attachData(\Illuminate\Support\Facades\File::get($attach['path']), $attach['name'], [
                        'mime' => $attach['mime'],
                    ]);;
            }
        }
        return $result;

    }
}
