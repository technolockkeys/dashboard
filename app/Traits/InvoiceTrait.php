<?php

namespace App\Traits;

use App\Models\Address;
use App\Models\AuditLog;
use App\Models\Currency;
use App\Models\Order;
use App\Models\User;
use Google\Exception;
use Illuminate\Database\Eloquent\Model;

trait InvoiceTrait
{
    function PrintInvoicePDF($id)
    {
        $pdf = new \Mpdf\Mpdf();
        $pdf->charset_in = 'iso-8859-4';
        $order = Order::findOrFail($id);
        $products = $order->order_products()->whereNull('orders_products.parent_id')->withTrashed()->get();

        $address = Address::find($order->address_id);
        $user = User::where('id', $order->user_id)->withTrashed()->first();

        $qr = $this->generateQrCode($order->uuid);
        if ($order->currency_id == 1 || $order->currency_id == null) {
            $currency = Currency::query()->where('id', 1)->first();
        } else {
            $currency = Currency::query()->where('id', $order->currency_id)->first();
        }
        $view = view('invoice.invoice', compact('order', 'user', 'products', 'address', 'qr', 'currency'));
        $pdf->writeHTML($view);

        $name = 'order' . '_' . $order->uuid . '_' . \Carbon\Carbon::now() . '.pdf';
        $pdf->Output($name, 'D');
    }

    function sendByEmail($order_id, $email = null)
    {
        try {
            $order = \App\Models\Order::query()->where('id', $order_id)->first();
            $user = \App\Models\User::query()->where('id', $order->user_id)->first();
            if (!empty($user->email)) {
                if (!empty($email)) {
                    $user->email = $email;
                }
                \Mail::to($user->email)->queue(new \App\Mail\SendInvoideOrder($order_id));
            }
            return redirect()->back()->with('success', trans('backend.global.sent_successfully'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
