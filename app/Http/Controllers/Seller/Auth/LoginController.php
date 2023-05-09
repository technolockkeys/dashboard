<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Auth\LoginRequest;
 use App\Models\Seller;
 use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
        if (auth('seller')->check()) {
            return '/seller/123';
        }

        return '/seller';
    }
    function showLoginForm()
    {
        return view('seller.auth.login');
    }

    function login(LoginRequest $request)
    {
        $seller =Seller::query()->where('email' , $request->email)->first();
         if (!empty($seller) && $seller->status != 0){
             if (Hash::check($request->password ,$seller->password)){
                auth('seller')->login($seller);
                return redirect()->route('seller.home');
            }
            return back()->with('message',trans('frontend.auth.credentials_not_matched'));
        }else{
            return back()->with('message',trans('frontend.auth.seller_is_not_active'));
        }
    }

}
