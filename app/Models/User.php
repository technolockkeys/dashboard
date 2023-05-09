<?php

namespace App\Models;

use App\Mail\WelcomeMail;
use App\Notifications\TwoFactorCodeNotification;
use App\Traits\MediaUploadingTrait;
use App\Traits\SerializeDateTrait;
use App\Traits\SetMailConfigurations;
use \DateTimeInterface;
use App\Notifications\VerifyUserNotification;
use App\Traits\Auditable;
use Carbon\Carbon;
use Google\Exception;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    use Notifiable;
    use Auditable;
    use HasFactory;
    use SerializeDateTrait;
    use MediaUploadingTrait;
    use LogsActivity;
    use SetMailConfigurations;

    public static $searchable = [
        'name',
        'email',
    ];

    protected $hidden = [
        'remember_token',
        'two_factor_code',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'two_factor_expires_at',
    ];

    protected $fillable = [
        'name',
        'uuid',
        'email',
        'email_verified_at',
        'verified',
        'verified_at',
        'verification_token',
        'password',
        'two_factor',
        'two_factor_code',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'auth_token',
        'two_factor_expires_at',
        'stripe_cust_id',
        'facebook_id',
        'google_id'

    ];

    public static function boot()
    {
        parent::boot();

        $lastUserId = User::withTrashed()->select('id')->orderBy('id', 'desc')->first();
        self::creating(function ($model) use ($lastUserId) {
            $uuid = 'TLKC' . (2200501 + ($lastUserId ?->id));
            $model->uuid = $uuid;
            $model->verification_code =  Str::random(40);
        });
        self::created(function ($model) {
            $model->setMailConfigurations();
//        dd(\Config::get('mail'));
            $model->sendEmailVerificationNotification();
            $data = [
                'title' => trans('backend.notifications.welcome'),
                'content' => trans('backend.notifications.message'),
                'user' => $model,
                'button' => trans('backend.notifications.visit_website')
            ];
            Mail::to($model)->queue(new WelcomeMail(trans('backend.notifications.welcome'), $data));

            try {
                if (!empty($model->email) && !empty(get_setting('token_sender_email'))) {
                    $client = new \GuzzleHttp\Client();
                    $json = [
                        "email" => $model->email,
                        "firstname" => $model->name,
                        "phone" => $model->phone,
                        "groups" => ["API"],
                    ];


                    $response = $client->post(
                        'https://api.sender.net/v2/subscribers',
                        [
                            'headers' => [
                                'Authorization' => 'Bearer ' . get_setting('token_sender_email'),
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                            ],
                            'json' => $json
                        ]
                    );
                    $body = $response->getBody();
                }
            } catch (\Exception $exception) {

            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallet()
    {
        return $this->hasMany(UserWallet::class)->with('order');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class)->with(['country']);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }


    public function get_default_address()
    {
        return $this->addresses()->where('is_default', 1)->first();
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function coupon_usage()
    {
        return $this->hasMany(CouponUsages::class);
    }

    public function total_purchase_value()
    {
        return $this->orders()->sum('total');
    }

    public function wallet_create_by()
    {
        return $this->morphOne(UserWallet::class, 'create_by');
    }

    public function add_to_last_visited(Product $product)
    {
        if ($this->last_visited()->count() >= 8) {
            $last_product = $this->last_visited()->first();
            $this->last_visited()->detach($last_product->id);
        }
        return $this->last_visited()->syncWithoutDetaching($product);
    }

    public function last_visited(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'last_visited_products');
    }

    public function compared_products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'compares')->withTimestamps();
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function outOfStock(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'out_of_stocks', 'user_id', 'product_id')->withTimestamps();
    }

    public function whatsnew(): HasMany
    {
        return $this->hasMany(WhatNew::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function reviewReply()
    {
        return $this->morphOne(ReviewReply::class, 'userable');
    }

    public function sendEmailVerificationNotification()
    {
         $this->notify(new VerifyUserNotification($this));
    }

}
