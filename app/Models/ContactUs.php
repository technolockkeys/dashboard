<?php

namespace App\Models;

use App\Mail\ContactUsMail;
use App\Mail\ThanksMail;
use App\Mail\TicketAdminMail;
use App\Traits\NotificationTrait;
use App\Traits\SerializeDateTrait;
use App\Traits\SetMailConfigurations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class ContactUs extends Model
{
    use HasFactory;
    use SoftDeletes;
    use NotificationTrait;
    use SerializeDateTrait;
    use SetMailConfigurations;
    protected $table = 'contact_us';

    protected $fillable = ['name', 'email', 'subject', 'message','model_type' ,'model_id'];

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $receivers['admins'] = Admin::query()->where('status', 1)->get();

            $details['contact'] = $model;
            $details['title'] = $model->subject;
            $details['content'] = $model->message;
            $details['button'] = 'Show request';
            $details['product'] = null;
            if (!empty($model->model_id))
            {
                $details['product'] = Product::where('sku',$model->model_id)->first();
             }
            $model->setMailConfigurations();
             Mail::to(json_decode(get_setting('contact_us_notifications_receivers')))
                ->later(0, new ContactUsMail('New contact us ', $details));
            $data = [
                'title' => trans('backend.notifications.contact_us'),
                'body' => trans('backend.notifications.contact_us_body', ['body' => $model->message])
            ];
            $dataUser = [
                'title' => trans('backend.notifications.thanks_for_contact_us'),
                'content' => trans('backend.notifications.we_will_contact_you_as_soon_as_possible'),
                'product' => null,
                'button' => trans('backend.notifications.visit_product')
            ];

            $model->setMailConfigurations();
            Mail::to($model->email)->later(0, new ThanksMail(trans('backend.notifications.thanks_for_contactus'), $dataUser));


            $model->sendNotification($receivers, 'order_is_paid', $data, 'contact_us', ContactUs::class);

        });
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }


}
