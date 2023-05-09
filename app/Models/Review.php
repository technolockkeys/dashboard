<?php

namespace App\Models;

use App\Mail\ThanksMail;
use App\Traits\NotificationTrait;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Review extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;
    use NotificationTrait;
    use LogsActivity;


    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $model->uuid = Order::generateUUID(10);
            $receivers['admins'] = Admin::query()->where('status', 1)->get();

            $name = auth('api')->check() ? auth('api')->user()->name : auth('seller')->user()->name;
            $data = [
                'title' => 'New Review Created',
                'body' => $name . ': ' . $model->comment
            ];

            $model->sendNotification($receivers, 'new_order', $data, '/', Review::class, $model->id,);

            $bcc = empty(json_decode(get_setting('review_notifications_receivers'))) ? [] : json_decode(get_setting('review_notifications_receivers'));
            $data = [
                'title' => trans('backend.notifications.thanks_for_review'),
                'content' => $model->comment,
                'product' => Product::query()->find($model->product_id),
                'button' => trans('backend.notifications.visit_product')
            ];
            if (auth('api')->check()) {
                $user = auth('api')->user();
                if (!empty($user->seller_id)) {
                    $seller = Seller::query()->where('id', $user->seller_id)->first();
                    if (!empty($seller)) {
                        $bcc[] = $seller->email;
                    }
                }
                Mail::to(auth('api')->user())->bcc($bcc)->queue(new ThanksMail(trans('backend.notifications.thanks_for_review'), $data));
            }
        });

    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
