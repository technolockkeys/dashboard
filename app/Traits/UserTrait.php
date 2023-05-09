<?php

namespace App\Traits;


use App\Mail\PaymentReminder;
use App\Mail\SendStatementMail;
use App\Models\User;
use App\Models\UserWallet;

trait UserTrait
{

    function sendReminder(User $user)
    {
        $this->setMailConfigurations();;
        $amount = UserWallet::query()->where('user_id' , $user->id)->where('status' ,UserWallet::$approve)->sum('amount');
        if ($amount < 0) {
            \Mail::to($user)->queue(new PaymentReminder($user));

            return response()->data(['message' => trans('backend.global.success_message.sent_successfully')]);
        }
        return response()->error(trans('backend.user.user_doesnot_have_dept'));
    }

    function SendStatementMail(User $user)
    {
        \Mail::to($user)->queue(new SendStatementMail($user));

        return response()->data(['message' => trans('backend.global.success_message.sent_successfully')]);
    }
}
