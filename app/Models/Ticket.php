<?php

namespace App\Models;

use App\Mail\OrderMail;
use App\Mail\TicketAdminMail;
use App\Traits\Auditable;
use App\Traits\MediaUploadingTrait;
use App\Traits\NotificationTrait;
use App\Traits\SaveFilesTrait;
use App\Traits\SerializeDateTrait;
use App\Traits\SetMailConfigurations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends Model
{
    use SoftDeletes;
    use Notifiable;
    use Auditable;
    use HasFactory;
    use SerializeDateTrait;
    use MediaUploadingTrait;
    use LogsActivity;
    use NotificationTrait;
    use SetMailConfigurations;
    use SaveFilesTrait;

    public static $PENDING = 'pending';
    public static $OPEN = 'open';
    public static $SOLVED = 'solved';

    protected $fillable = ['type', 'subject', 'details', 'files', 'viewed', 'client_viewed', 'sent_at','model_id'];

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $receivers['admins'] = Admin::query()->where('status', 1)->get();
            $name = auth('api')->user()->name;
            $data = [
                'title' => 'New Ticket Created',
                'body' => $name . ' Created a new Ticket'
            ];
            $model->sendNotification($receivers, 'new_ticket', $data, '/', Ticket::class, $model->id,);

            if (!empty(get_setting('ticket_notifications')) && auth('api')->check()) {
                $model->setMailConfigurations();
                $details['ticket'] = $model;
                $details['title'] = $model->subject;
                $details['user'] = auth('api')->user();
                $details['content'] = $model->details;
                $details['button'] = 'Show ticket';
                $bcc = json_decode(get_setting('ticket_notifications_receivers'));
                $user = auth('api')->user();
                if (!empty($user) && !empty($user->email)) {
                    if (!empty($user->seller_id)) {
                        $seller = Seller::query()->where('id', $user->seller_id)->first();
                        if (!empty($seller)) {
                            $bcc[] = $seller->email;
                        }
                    }
                    Mail::to($user->email)
                        ->bcc($bcc)
                        ->queue(new TicketAdminMail('New Ticket Created', $details));

                }
            }

        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
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

    public function get_replies()
    {
        $replies = [];
        foreach ($this->replies ?? [] as $reply) {
            $files = [];
            foreach (json_decode($reply->files, true) ?? [] as $file) {
                $files[] = [
                    'image_name' => $file['image_data'],
                    'image_path' => asset($file['path'] . '/' . $file['hashed_name']),
                ];
            }
            $replies[] = [
                'reply' => $reply->reply,
                'files' => $files,
                'from' => $reply->replyable ?->name,
                'avatar' => asset($reply->replyable ?->avatar),
                'created_at' => Carbon::parse($reply->created_at)->format('Y-m-d h-m-s'),
            ];
        }

        return $replies;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
