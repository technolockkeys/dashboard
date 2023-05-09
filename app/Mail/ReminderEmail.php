<?php

namespace App\Mail;

use App\Models\Cart;
use App\Models\Compare;
use App\Models\Product;
use App\Models\Wishlist;
use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReminderEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, SetMailConfigurations;

    public $details;
    public $subject;
    public $typeMail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $details , $typeMail )
    {
        $this->setMailConfigurations();
        $this->subject = $subject;
        $this->details = $details;
        $this->typeMail = $typeMail ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setMailConfigurations();

        $user = $this->details['user'];
        $products = [];
        $productIds = [];
        $image = asset('/');
        switch ($this->typeMail) {
            case 'compared_products':
                $productIds = Compare::query()->where('user_id', $user->id)->pluck('product_id');
                $image= asset('mail/compare.png');
                break;
            case "carts":
                $productIds = Cart::query()->where('user_id', $user->id)->pluck('product_id');
                $image= asset('mail/cart.png');
                break;
            case "wishlists":
                $productIds = Wishlist::query()->where('user_id', $user->id)->pluck('product_id');
                $image= asset('mail/wishlist.png');
                break;
        }
        if (!empty($productIds))
            $products = Product::query()->whereIn('id', $productIds)->get();
        $type = $this->typeMail;

        try {

            return $this->subject($this->subject)->view('email.reminder', ['details' => $this->details, 'products' => $products, 'type' => $type, 'image' => $image, 'user' => $user]);
        }catch (\Exception $exception){
            Log::info("exception : " .$exception->getMessage());
        }
    }
}
