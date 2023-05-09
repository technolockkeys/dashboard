<?php

namespace App\Mail;

use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendStatementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, SetMailConfigurations;


    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, )
    {
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
        $wallets = $user->wallet()->where('status', 'approve')->with('order')->get();

        if(!$wallets){
            return response()->error([ trans('backend.user.user_doesnot_have_dept')]);
        }
        $pdf = new \Mpdf\Mpdf();

        $view = view('invoice.account_statement', compact('wallets', 'user'))->render();

        $pdf->WriteHTML(utf8_encode($view));

        $name = 'user' . '_' . $user->uuid . '_' . \Carbon\Carbon::now() . '.pdf';
        $subject =  'account statement for :'.$user->uuid;

        $content = $pdf->Output($name, 'S');

        $this->setMailConfigurations();
        $view2 = view('email.account_statement', compact('wallets', 'user'))->render();

        return $this->subject($subject )->html($view2)->attachData($content, $name, [
            'mime' => 'application/pdf',
        ]);

    }
}
