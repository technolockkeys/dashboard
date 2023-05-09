<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    #region logout
    public function logout()
    {
        auth('seller')->logout();
        return redirect('/');
    }
    #endregion

    #region setlocale
    public function change_locale($locale)
    {
        \app()->setLocale($locale);
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    }
    #endregion
}
