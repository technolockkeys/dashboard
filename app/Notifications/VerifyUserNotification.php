<?php

namespace App\Notifications;

use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyUserNotification extends Notification
{
    use Queueable;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new VerifyMail(get_setting('app_url') . 'verify-mail?token=' . $this->user->verification_code, $this->user->name))->to($notifiable->email);
//        return (new MailMessage())
//            ->line(trans('email.verifyYourUser'))
//            ->action(trans('email.clickHereToVerify'),get_setting('app_url').'verify-mail?token='. $this->user->verification_code)
//            ->line(trans('email.thankYouForUsingOurApplication'));
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
