<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\LoginRequest;
use App\Models\Admin;
use App\Notifications\TwoFactorCodeNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('is.not.admin')->except('logout');
    }

    public function redirectTo()
    {
        if (auth('admin')->check()) {
            return '/admin/123';
        }

        return '/admin';
    }
    function showLoginForm()
    {
        return view('backend.auth.login');
    }

    function login(LoginRequest $request)
    {
        $admin = Admin::query()->where('email' , $request->email)->first();
        if (!empty($admin) && $admin->status != 0){
             if (Hash::check($request->password ,$admin->password)){
                auth('admin')->login($admin);
                return redirect()->route('backend.home');
            }
            return back()->with('message',trans('frontend.auth.credentials_not_matched'));
        }else{
            return back()->with('message',trans('frontend.auth.admin_not_active'));
        }
    }

}
