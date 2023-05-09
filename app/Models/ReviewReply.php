<?php

namespace App\Models;

use App\Mail\ThanksMail;
use App\Traits\EraningsTrait;
use App\Traits\GeneratedCodeTrait;
use App\Traits\NotificationTrait;
use App\Traits\RandomCodeGeneratorTrait;
use App\Traits\SerializeDateTrait;
use App\Traits\SetMailConfigurations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Traits\LogsActivity;

class ReviewReply extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;
    use GeneratedCodeTrait;
    use NotificationTrait;
    use SetMailConfigurations;
    use RandomCodeGeneratorTrait;
    use EraningsTrait;

    protected $table = 'reviews_replies';

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $review = Review::find($model->review_id);
            $receivers['admins'] = Admin::query()->where('status', 1)->get();

            $name = auth('admin')->check() ? (auth('admin')->user()->name) : (auth('api')->check() ? auth('api')->user()->name : auth('seller')->user()->name);
            $data = [
                'title' => 'New Replay Review Created',
                'body' => $name . ': ' . $model->comment
            ];

            $model->sendNotification($receivers, 'new_order', $data, '/', Review::class, $model->id,);

            $bcc = empty(json_decode(get_setting('review_notifications_receivers'))) ? [] : json_decode(get_setting('review_notifications_receivers'));
            $data = [
                'title' => trans('backend.notifications.replay_review'),
                'content' => $review->comment . PHP_EOL . $model->comment,
                'product' => Product::query()->find($review->product_id),
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

    public function userable()
    {
        return $this->morphTo();
    }
}
