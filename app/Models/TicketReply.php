<?php

namespace App\Models;

use App\Mail\TicketAdminMail;
use App\Mail\TicketUserMail;
use App\Traits\Auditable;
use App\Traits\MediaUploadingTrait;
use App\Traits\NotificationTrait;
use App\Traits\SaveFilesTrait;
use App\Traits\SerializeDateTrait;
use App\Traits\SetMailConfigurations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TicketReply extends Model
{
    use SoftDeletes;
    use Notifiable;
    use Auditable;
    use HasFactory;
    use SerializeDateTrait;
    use NotificationTrait;
    use SetMailConfigurations;
    use MediaUploadingTrait;
    use LogsActivity;
    use SaveFilesTrait;

    protected $fillable = ['reply'];

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $receivers['admins'] = Admin::query()->where('status', 1)->get();

            $user = auth('api')->check() ? auth('api')->user() : auth('admin')->user();
            $data = [
                'title' => 'Reply on the ticket',
                'body' => $user->name . ' replied on ticket'
            ];

            if (get_setting('ticket_notifications'))
                $model->sendNotification($receivers, 'ticket_reply', $data, '/', Ticket::class, $model->id,);
            $details['ticket'] = $model->ticket;
            $details['title'] = $model->ticket->subject;
            $details['user'] = $user;
            $details['content'] = $model->details;
            $details['button'] = 'Show ticket';
            $model->setMailConfigurations();


            if (!empty($model->ticket->user->email)) {
                $bcc = json_decode(get_setting('ticket_notifications_receivers'));
                $user = $model->ticket->user;
                if (!empty($user)) {
                    if (!empty($user->seller_id)) {
                        $seller = Seller::find($user->seller_id);
                        if (!empty($seller)) {
                            $bcc[] = $seller->email;
                        }
                    }
                    Mail::to($user->email)
                        ->bcc($bcc)
                        ->queue(new TicketUserMail('Reply on the ticket', $details));

                }
            }

        });
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function replyable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function get_files()
    {
        $files = [];

        if ($this->files)
            foreach (json_decode($this->files, true) as $file) {
                $files[] = [
                    'image_name' => $file['image_data'],
                    'image_path' => asset($file['path'] . '/' . $file['hashed_name']),
                ];
            }
        return $files;
    }
}
