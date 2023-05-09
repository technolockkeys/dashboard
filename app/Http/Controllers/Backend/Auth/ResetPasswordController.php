<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\Password\ForgetPasswordRequest;
use App\Http\Requests\Backend\Auth\Password\UpdatePasswordRequest;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function getPassword($token) {

        return view('backend.auth.passwords.reset', ['token' => $token]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $updatePassword = DB::table('admins_password_resets')
            ->where(['email' => $request->email, 'token' => $request->token])
            ->first();

        if(!$updatePassword)
            return back()->withInput()->with('error', 'Invalid token!');

        $admin = Admin::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect(route('backend.login'))->with('success', 'Your password has been changed!');

    }

}
