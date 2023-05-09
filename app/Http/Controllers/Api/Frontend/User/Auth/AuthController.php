<?php

namespace App\Http\Controllers\Api\Frontend\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\User\ForgetPasswordRequest;
use App\Http\Requests\Api\Frontend\User\UpdatePasswordRequest;
use App\Http\Requests\Api\User\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\User\Auth\FacebookLoginRequest;
use App\Http\Requests\Api\User\Auth\LoginRequest;
use App\Http\Requests\Api\User\Auth\RegisterRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Traits\SetMailConfigurations;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Socialite;

class AuthController extends Controller
{
    use SetMailConfigurations;

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['status'] = 1;

        try {
            $token = auth('api')->attempt($credentials);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        if (!$token) {
            return response()->error(trans('frontend.auth.credentials_not_matched'));
        }
        $user = auth('api')->id();
//        $user2 = auth('api')->user();
        $user = User::find($user);
        if (empty($user->email_verified_at)) {
            return response()->error(trans('frontend.auth.please_verify_your_mail'));
        }
        $user->auth_token = $token;
        $user->save();
        $user->avatar = $user->provider_type == 'email' ? asset($user->avatar) : $user->avatar;
        $user = auth('api')->user();
        $seller = $user->seller()->first();
        $data = [
            'status' => 'success',
            'message' => trans('frontend.auth.success_login'),
            'user' => [
                "id" => $user->id,
                "uuid" => $user->uuid,
                "country_id" => $user->country_id,
                "city" => $user->city,
                "name" => $user->name,
                "provider_type" => $user->provider_type,
                "email" => $user->email,
                "avatar" => $user->provider_type == 'email' ? asset($user->avatar) : $user->avatar,
                "address" => $user->address,
                "state" => $user->state,
                "street" => $user->street,
                "company_name" => $user->company_name,
                "website_url" => $user->website_url,
                "type_of_business" => $user->type_of_business,
                "postal_code" => $user->postal_code,
                "balance" => $user->balance,
                "referral_code" => $user->referral_code,
                "status" => $user->status,

            ],
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ];
        if (!empty($seller)) {
            $seller->avatar = asset($seller->avatar);
            $data['user']['seller'] = $seller;
        }
        return response()->json($data);
    }

    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->provider_type = 'email';
        $user->password = \Hash::make($request->password);
        $user->address = $request->address;
        $user->state = $request->state;
        $user->street = $request->street;
        $user->country_id = $request->country;
        $user->company_name = $request->company_name;
        $user->website_url = $request->website_url;
        $user->postal_code = $request->postal_code;
        $user->save();
//        $user =$user->first();
        //auth after register
//        $token = \auth('api')->login($user);
//        $user->auth_token = $token;
//        $user->save();
        //send email verified


        return response()->data(['user' => $user, 'register' => 'success', 'token' => null, 'message' => trans('frontend.auth.registered_successfully')]);
    }

    public function forgot_password(ForgetPasswordRequest $request)
    {
        $token = Str::random(64);

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );
        $this->setMailConfigurations();
        $mail = Mail::send('email.password', ['route' =>  get_setting('app_url').'auth/reset-password?token='.$token ], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });
//        dd($mail);
        return response()->api_data(['message' => trans('passwords.sent')]);
    }

    public function reset_password(UpdatePasswordRequest $request)
    {
        $email = DB::table('password_resets')->where('token', $request->token)->first();
        $updatePassword = DB::table('password_resets')
            ->where(['email' => $email->email])
            ->first();

        if (!$updatePassword)
            return response()->api_error('error', 'Invalid token!');
        $user = User::query()->where('email', $email->email)->first();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();
        DB::table('password_resets')->where(['email' => $user->email])->delete();

        return response()->api_data(['message' => trans('frontend.auth.password_changed')], 200);

    }


    public function logout()
    {
        $user = auth('api')->user();
        $user->auth_token = null;
        $user->save();
        $token = JWTAuth::getToken();

        JWTAuth::clearResolvedInstance($token->get());
        auth('api')->logout();
        auth('api')->invalidate(true);
        Auth::guard('api')->logout();
        $forever = true;
        return response()->data(['message' => trans('frontend.auth.logout_successfully')]);
    }

    public function login_with_facebook(FacebookLoginRequest $request)
    {

        $user = User::withTrashed()->
        where(function ($q) use ($request) {
            $q->where('facebook_id', $request->userID);
        });
        if (!empty($request->email)) {
            $user->orWhere('email', $request->email);
        }
        $user = $user->first();
        if (empty($user)) {
            $user = new User ();
        }
        if ($user->deleted_at != null)
            return response()->api_error(trans('frontend.auth.cannt_login'), 403);

        $user->facebook_id = $request->userID;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->facebook_id = $request->userID;
        $user->email_verified_at = Carbon::now();
        $user->avatar = $request->profile_picture;
        $user->provider_type = 'facebook';
        $user->save();
        $seller = $user->seller()->first();

        $user->refresh();

        $data = [
            "id" => $user->id,
            "uuid" => $user->uuid,
            "country_id" => $user->country_id,
            "city" => $user->city,
            "email" => $user->email,
            "name" => $user->name,
            "provider_type" => $user->provider_type,
            "facebook_id" => $user->facebook_id,
            "google_id" => $user->google_id,
            "avatar" => $user->provider_type == 'email' ? asset($user->avatar) : $user->avatar,
            "address" => $user->address,
            "state" => $user->state,
            "street" => $user->street,
            "company_name" => $user->company_name,
            "website_url" => $user->website_url,
            "type_of_business" => $user->type_of_business,
            "postal_code" => $user->postal_code,
            "balance" => $user->balance,
            "referral_code" => $user->referral_code,
            "status" => $user->status,

        ];
        if (!empty($seller)) {

            $seller->avatar = asset($seller->avatar);
            $data->seller = $seller;
        }
        \Auth::login($user);
        $token = \auth('api')->login($user);
        $user->auth_token = $token;
        $user->save();

        return response()->data(['user' => $data, 'register' => 'success', 'token' => $token]);
    }

    public function login_with_google(Request $request)
    {
        $user = User::withTrashed();
//        where(function ($q) use ($request) {
//            $q->where('google_id', $request->userID);
//        });
        $user->where('email', $request->email);
//        if (!empty($request->email)) {
//            $user->where('email', $request->email);
//        }
        $user = $user->first();
        if (empty($user)) {
            $user = new User ();
        }
        if ($user->deleted_at != null)
            return response()->api_error(trans('frontend.auth.cannt_login'), 403);

        $user->deleted_at = null;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->google_id = $request->userID;
        $user->avatar = $request->profile_picture;
        $user->email_verified_at = Carbon::now();
        $user->provider_type = 'google';

        $user->save();
        $seller = $user->seller()->first();

        $user->refresh();
        $data = [
            "id" => $user->id,
            "uuid" => $user->uuid,
            "email" => $user->email,
            "country_id" => $user->country_id,
            "city" => $user->city,
            "name" => $user->name,
            "provider_type" => $user->provider_type,
            "facebook_id" => $user->facebook_id,
            "google_id" => $user->google_id,
            "avatar" => (!empty($user->avatar) && !filter_var($user->avatar, FILTER_VALIDATE_URL)) ? asset($user->avatar) : $user->avatar,
            "address" => $user->address,
            "state" => $user->state,
            "street" => $user->street,
            "company_name" => $user->company_name,
            "website_url" => $user->website_url,
            "type_of_business" => $user->type_of_business,
            "postal_code" => $user->postal_code,
            "balance" => $user->balance,
            "referral_code" => $user->referral_code,
            "status" => $user->status,
        ];
        if (!empty($seller)) {

            $seller->avatar = asset($seller->avatar);
            $data->seller = $seller;
        }
        \Auth::login($user);
        $token = \auth('api')->login($user);
        $user->auth_token = $token;
        $user->save();
        return response()->data(['user' => $data, 'register' => 'success', 'token' => $token]);

    }

    public function change_password(ChangePasswordRequest $request)
    {
        $user = auth('api')->user();

        if ($request->old_password != null && Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);

            $user->save();

            return response()->api_data(['user' => $user, 'message' => trans('backend.global.success_message.updated_successfully')]);

        } elseif ($user->password == null) {
            $user->password = Hash::make($request->password);

            $user->save();

            return response()->api_data(['user' => $user, 'message' => trans('backend.global.success_message.updated_successfully')]);
        } else {
            return response()->error(['password' => trans('frontend.auth.the_password_is_incorrect')]);
        }
    }

    function verfiyMail(Request $request)
    {
        $token = $request->token;
        if (empty($token))
            return response()->error(trans('api.auth.user_not_found'));

        $user = User::where('verification_code', $token)->first();
        if (empty($user))
            return response()->error(trans('api.auth.user_not_found'));
        $user->email_verified_at = \Carbon\Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        $user->verification_code = null;
        $token = \auth('api')->login($user);
        $user->auth_token = $token;
        $user->save();
        $user->refresh();
        return response()->api_data(['message' => trans('api.auth.verify_mail'), 'token' => $user->auth_token, 'user' => $user]);

    }

    function testVerfiyMail(Request $request)
    {
        $user = User::find(170);
        $user->email_verified_at = \Carbon\Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        $user->verification_code = null;
        $token = \auth('api')->login($user);
        $user->auth_token = $token;
        $user->save();
        $user->refresh();
        return response()->api_data(['message' => trans('api.auth.verify_mail'), 'token' => $user->auth_token, 'user' => $user]);
    }

    function testVerfiyMailfail(Request $request)
    {
        return response()->error(trans('api.auth.user_not_found'));
    }

}
