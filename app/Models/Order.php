<?php

namespace App\Models;

use App\Mail\OrderMail;
use App\Mail\OrderSellerMail;
use App\Mail\OrderUserMail;
use App\Mail\ProductAlertsMail;
use App\Mail\SendCouponNotification;
use App\Mail\SendOfferMail;
use App\Traits\EraningsTrait;
use App\Traits\GeneratedCodeTrait;
use App\Traits\NotificationTrait;
use App\Traits\OrderTrait;
use App\Traits\RandomCodeGeneratorTrait;
use App\Traits\SerializeDateTrait;
use App\Traits\SetMailConfigurations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Self_;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;
    use GeneratedCodeTrait;
    use NotificationTrait;
    use SetMailConfigurations;
    use RandomCodeGeneratorTrait;
    use LogsActivity;
    use EraningsTrait;

    protected $fillable = ['status', 'user_id', 'address_id', 'payment_method',
        'payment_status', 'total', 'shipping', 'status', 'has_coupon', 'coupon_value', 'is_free_shipping',
        'type', 'feedback', 'feedback_date', 'feedback_send_email', 'tracking_number',
        'shipment_value', 'shipment_description'
    ];
    protected $table = 'orders';

    //region types
    public static $order = 'order';
    public static $proforma = 'proforma';
    public static $pin_code = 'pin_code';
    //endregion

    //region payment status
    public static $payment_status_unpaid = 'unpaid';
    public static $payment_status_paid = 'paid';
    public static $payment_status_failed = 'failed';
    //endregion

    //region payment method
    public static $stripe = 'stripe';
    public static $paypal = 'paypal';
    public static $stripe_link = 'stripe_link';
    public static $transfer = 'transfer';
    //endregion

    //region status
    public static $on_hold = 'on_hold';
    public static $pending_payment = 'pending_payment';
    public static $processing = 'processing';
    public static $completed = 'completed';
    public static $canceled = 'canceled';
    public static $failed = 'failed';
    public static $waiting = 'waiting';
    public static $refunded = 'refunded';
    //endregion

    //region shipping method
    public static $DHL = 'dhl';
    public static $Aramex = 'aramex';
    public static $FedEx = 'fedex';
    public static $UPS = 'ups';
    //endregion
    public static $statusN = null;


    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            $order = Order::find($model->id);
            if (!empty($order))
                self::$statusN = $order->status;
        });
        self::saved(function ($model) {
            if (!empty($model->user_id)) {

                if (in_array($model->status, [self::$canceled, self::$refunded, self::$completed, self::$processing, self::$failed]) && $model->status != self::$statusN) {
                    $receivers['admins'] = Admin::query()->where('status', 1)->get();
                    $receivers['seller'] = Seller::query()->where('status', 1)->where('id', $model->seller_id)->get();
                    $type = 'order_' . $model->status;
                    $title = 'Order ' . $model->status;
                    $details['products'] = $model->order_products;
                    $details['title'] = 'Order ' . $model->status;
                    $details['content'] = $model->user->name . '`s order has been ' . $model->status;
                    $details['order'] = $model;
                    $details['button'] = 'Show order';
                    $data = [
                        'title' => $title,
                        'body' => $model->user->name . '`s Order has been ' . $model->status
                    ];
                    $model->sendNotification($receivers, $type, $data, '/', Order::class, $model->id,);
                    $bcc_emails = [];
                    if (!get_setting('order_notifications_receivers')) {
                        $bcc_emails = json_decode(get_setting('order_notifications_receivers'));
                     }
                    if (!empty($model->seller_id)) {
                        $seller = Seller::find($model->seller_id);
                        if (!empty($seller) && !empty($seller->email)) {
                            $bcc_emails[] = $seller->email;
                        }
                    }

                    $details['content'] = 'your order has been successfully ' . $model->status;
                    try {
                        if (in_array($model->status, [self::$completed, self::$failed,self::$processing]) && $model->status != self::$statusN) {

                            $type = 'processing';
                            if ($model->status == self::$completed) {
                                $type = 'completed';
                            }
                            if ($model->status == self::$failed) {
                                $type = 'failed';
                            }
                            Mail::to($model->user->email)
                                ->bcc($bcc_emails)
                                ->queue(new OrderUserMail($title, $details, $type));
                        }

                    } catch (\Exception $exception) {
                        \Log::error("Saved Order Mail Exception : " . $exception->getMessage());
                    }
                }
            }

        });
    }



    public function tickets(): MorphMany
    {
        return $this->morphMany(Ticket::class, 'model');
    }


    public static function statuses()
    {
        return [
            'on_hold' => trans('backend.order.on_hold'),
            'pending_payment' => trans('backend.order.pending_payment'),
            'processing' => trans('backend.order.processing'),
            'completed' => trans('backend.order.completed'),
//            'failed' => trans('backend.order.failed'),
            'refunded' => trans('backend.order.refunded'),
//            'canceled' => trans('backend.order.canceled'),
        ];
    }

    public static function shipping_methods()
    {
        return ['DHL' => trans('backend.order.dhl'),
            'Aramex' => trans('backend.order.aramex'),
            'UPS' => trans('backend.order.ups'),
            'FedEx' => trans('backend.order.fedex')
        ];
    }

    public static function payment_methods()
    {
        return [
            'stripe' => trans('seller.orders.stripe'),
            'paypal' => trans('seller.orders.paypal'),
            'stripe_link' => trans('seller.orders.stripe_link'),
            'transfer' => trans('seller.orders.transfer')
        ];
    }

    public static function payment_statuses()
    {
        return ['paid' => trans('backend.order.paid'), 'unpaid' => trans('backend.order.unpaid')];
    }

    public static function types()
    {
        return ['proforma' => trans('backend.order.proforma'), 'order' => trans('backend.order.order')];
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class)->with(['city', 'country']);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }


    public function seller_manager()
    {
        return $this->belongsTo(Seller::class, 'seller_manager_id');
    }

    public function products()
    {
        return $this->belongsTo(OrdersProducts::class, 'id', 'order_id')->with('product');
    }

    public function order_products()
    {
        $q = $this->belongsToMany(Product::class, 'orders_products', 'order_id', 'product_id')
            ->whereNull('orders_products.deleted_at')
            ->whereNull('parent_id')
            ->withPivot([
                'id',
                'quantity',
                'parent_id',
                'bundles_products_id',
                'weight',
                'price',
                'has_package',
                'shipping_cost',
                'parent_id',
                'attributes',
                'coupon_discount',
                'original_price',
                'serial_number',
                'package_price',
                'color_id']);

        return $q;
    }

    public function order_payment(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }
  public function user_wallet(): HasMany
    {
        return $this->hasMany(UserWallet::class);
    }

    public function card_information()
    {
        return OrderPayment::select('orders_payments.*', 'cards.last_four', 'cards.brand')
            ->join('cards', 'orders_payments.card_id', 'cards.id')
            ->where('order_id', $this->id)
            ->first();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
