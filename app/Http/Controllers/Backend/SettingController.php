<?php

namespace App\Http\Controllers\Backend;

use App;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Setting\ContactRequest;
use App\Http\Requests\Backend\Setting\EditSetting;
use App\Http\Requests\Backend\Setting\FrontendRequest;
use App\Http\Requests\Backend\Setting\GolbalSeoRequest;
use App\Http\Requests\Backend\Setting\NotificationRequest;
use App\Http\Requests\Backend\Setting\PaymentRequest;
use App\Http\Requests\Backend\Setting\ProductRequest;
use App\Http\Requests\Backend\Setting\ShippingRequest;
use App\Http\Requests\Backend\Setting\SMTPRequest;
use App\Http\Requests\Backend\Setting\SocialRequest;
use App\Models\Country;
use App\Models\Review;
use Config;

class SettingController extends Controller
{
    function index()
    {
        if (!permission_can('setting website', 'admin')) {
            return abort(403);
        }
        $timezones = Country::groupBy('timezone_name')->pluck('timezone_name');
        return view('backend.setting.index', compact('timezones'));
    }

    function update(EditSetting $request)
    {
        $system_name =[];
        $excepted_keys[] = 'token';
        foreach (get_languages() as $language)
        {
            $system_name[$language->code] = $request->get('system_name_'.$language->code);
            $excepted_keys[] = 'system_name_'.$language->code;
        }

        set_setting('system_name', $system_name);
        foreach ($request->except($excepted_keys) as $key => $part) {
            set_setting($key, $request->get($key) ?? '');

        }
        return redirect()->route('backend.setting.index')->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    function smtp()
    {
        if (!permission_can('setting smtp', 'admin')) {
            return abort(403);
        }

        return view('backend.setting.smtp');

    }

    function smtp_update(SMTPRequest $request)
    {

        foreach ($request->except('_token') as $key => $part) {
            set_setting($key, $request->get($key));
        }

        return redirect()->route('backend.setting.smtp')->with('success', trans('backend.global.success_message.updated_successfully'));

    }

    function frontend()
    {
        if (!permission_can('setting smtp', 'admin')) {
            return abort(403);
        }

        return view('backend.setting.frontend');
    }

    function frontend_update(FrontendRequest $request)
    {

        foreach ($request->except('_token') as $key => $part) {
            set_setting($key, $request->get($key));
        }
        $switches = ['banner_status', 'bottom_small_banner_status','watermark_status', 'bottom_big_banner_status'];
        foreach ($switches as $switch) {
            $value = $request->has($switch);
            set_setting($switch, (empty($value) ? "-1" : "1"));

        }
        return redirect()->route('backend.setting.frontend')->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    function global_seo()
    {

        if (!permission_can('setting global_seo', 'admin')) {
            return abort(403);
        }

        return view('backend.setting.global');

    }

    function global_seo_update(GolbalSeoRequest $request)
    {
        $meta_title = [];
        $meta_description = [];
        $excepted_keys[] = 'token';
        foreach (get_languages() as $language)
        {
            $meta_title[$language->code] = $request->get('meta_title_'.$language->code);
            $meta_description[$language->code] = $request->get('meta_description_'.$language->code);
            $excepted_keys[] = 'meta_title_'.$language->code;
            $excepted_keys[] = 'meta_description_'.$language->code;
        }

        set_setting('meta_title', json_encode($meta_title));
        set_setting('meta_description', json_encode($meta_description));
        foreach ($request->except($excepted_keys) as $key => $part) {
            set_setting($key, $request->get($key));
        }

        return redirect()->route('backend.setting.global')->with('success', trans('backend.global.success_message.updated_successfully'));

    }

    function social_media()
    {
        if (!permission_can('setting social', 'admin')) {
            return abort(403);
        }

        return view('backend.setting.social');
    }

    function social_media_update(SocialRequest $request)
    {
        foreach ($request->except('_token') as $key => $part) {
            set_setting($key, $request->get($key));
        }

        return redirect()->route('backend.setting.social')->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    function contact()
    {
        if (!permission_can('setting contact', 'admin')) {
            return abort(403);
        }

        return view('backend.setting.contact');
    }

    function contact_update(ContactRequest $request)
    {
        foreach ($request->except('_token') as $key => $part) {
            set_setting($key, $request->get($key));
        }

        return redirect()->route('backend.setting.contact')->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    function translate()
    {
        if (!permission_can('setting translate', 'admin')) {
            return abort(403);
        }
        return view('backend.setting.translate');
    }

    function payment_methods()
    {
        if (!permission_can('setting payment', 'admin')) {
            return abort(403);
        }
        return view('backend.setting.payment');
    }

    public function payment_update(PaymentRequest $request)
    {
        foreach ($request->except('_token') as $key => $part) {
            set_setting($key, $request->get($key));
        }

        $switches = ['stripe_sandbox_mode', 'paypal_sandbox_mode', 'stripe_status', 'paypal_status'];
        foreach ($switches as $switch) {
            $value = $request->has($switch);
            set_setting($switch, (empty($value) ? "-1" : "1"));

        }
        return redirect()->route('backend.setting.payment')->with('success', trans('backend.global.success_message.updated_successfully'));

    }

    function shipping()
    {
        return view('backend.setting.shipping_method');
    }

    function store_shipping(ShippingRequest $request)
    {
        set_setting("dhl", 1);
        set_setting("shipping_default", "dhl");

        foreach ($request->except('_token') as $key => $part) {
            set_setting($key, $request->get($key));

        }
        return redirect()->back()->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    public function notifications()
    {

        return view('backend.setting.notifications');
    }

    public function update_notifications(NotificationRequest $request)
    {

        $excepted_keys[] = '_token';

        foreach ($request->except($excepted_keys) as $key => $part) {
            set_setting($key, $request->get($key));
        }
        return redirect()->back()->with('success', trans('backend.global.success_message.updated_successfully'));
    }

//    public function default_images()
//    {
//        if (!permission_can('setting product', 'admin')) {
//            return abort(403);
//        }
////        $types = ['product','category', 'attribute', 'download','status', 'brand', 'page' ];
//        $types = ['product','category', 'attribute', 'download','status', 'brand', 'page' ];
//        return view('backend.setting.default_image', compact('types'));
//
//    }


//    public function product_update(ProductRequest $request)
////    {
////        foreach ($request->except('_token') as $key => $part) {
////            set_setting($key, $request->get($key));
////        }
////
////        return redirect()->route('backend.setting.default_images')->with('success', trans('backend.global.success_message.updated_successfully'));
////
////    }

}
