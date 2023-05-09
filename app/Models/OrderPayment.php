<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;
    protected $table = 'orders_payments';
    public static $created = 'created';
    public static $captured = 'captured';
    public static $denied = 'denied';
    public static $refund = 'refund';
    public static $pending = 'pending';
    public static $voided = 'voided';
    //payment method   ..
    public static $stripe = 'stripe';
    public static $paypal = 'paypal';
    public static $stripe_link = 'stripe_link';
    public static $transfer = 'transfer';
    public static $wallet = 'wallet';

    protected $fillable=[
        'user_id',
'card_id',
'order_id',
'amount',
'payment_details',
'status',
'payment_method',
'stripe_url',
'files',
    ];


    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function orders()
    {
        return $this->hasOne(OrderPayment::class);
    }

}
