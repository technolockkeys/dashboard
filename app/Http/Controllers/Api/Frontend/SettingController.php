<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\Setting\SettingRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Download;
use App\Models\Manufacturer;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function get(SettingRequest $request)
    {

        $website_setting = [
            'system_name' => 'system_name',
        ];

        $seo_setting = [
            'meta_title' => 'meta_title', 'meta_description' => 'meta_description', 'keywords' => 'keywords'
        ];
        $icons = [
            'key-remote',
            'accessories-tools',
            'device-machines',
            'manufacturers',
            'token-software',
            'cars',
            'download',
            'pincode'];
        $icon_data = [];
        foreach ($icons as $item) {
            $icon_data[$item] = media_file(get_setting('icon_' . $item));
        }
        $social_media = [
            'email' => 'social_email',
            'facebook' => 'social_facebook',
            'twitter' => 'social_twitter',
            'telegram' => 'social_telegram',
            'whatsapp' => 'social_whatsapp',
            'phone' => 'social_phone',
            'tiktok' => 'social_tiktok',
            'instagram' => 'social_instagram',
            'wechat' => 'social_wechat',
            'pinterest' => 'social_pinterest',
            'reddit' => 'social_reddit',
            'quora' => 'social_quora',
            'skype' => 'social_skype',
            'linkedin' => 'social_linkedin'
        ];

        $contact = [
            'email_primary' => 'contact_email',
            'email_secondary' => 'contact_email_secondary',
            'telegram' => 'contact_telegram',
            'whatsapp' => 'contact_whatsapp',
            'phone_primary' => 'contact_phone',
            'phone_secondary' => 'contact_phone_secondary',
            'address' => 'contact_address',
        ];

        $payment_methods = [];

        if (get_setting('paypal_status') == 1) {
            $payment_methods['paypal'] = 'paypal';
            $payment_methods['paypal_client_id'] = get_setting('paypal_sandbox_mode') == 0 ? get_setting('paypal_client_id') : get_setting('paypal_client_id_test');
        }

        if (get_setting('stripe_status') == 1) {
            $payment_methods['stripe'] = 'stripe';
        }

        $shipping_methods[] = ['dhl' => trans('backend.setting.dhl')];

//        if (get_setting('fedex') == 1)
        $shipping_methods[] = ['fedex' => trans('backend.setting.fedex')];

//        if (get_setting('aramex') == 1)
        $shipping_methods[] = ['aramex' => trans('backend.setting.aramex')];
//        if (get_setting('ups') == 1)
        $shipping_methods[] = ['ups' => trans('backend.setting.ups')];

        $languages = [];
        $currencies = [];
        $menus = [];
        $footer_1 = [];
        $footer_2 = [];
        $default_currency = null;
        $default_language = null;
        foreach (get_languages() as $language) {
            $languages[$language->code] = [
                'language' => $language->language,
                'display_type' => $language->display_type,
                'flag' => get_multisized_image($language->flage, ['s'])
            ];
            if ($language->is_default)
                $default_language = [
                    'language' => $language->language,
                    'display_type' => $language->display_type,
                    'flag' => get_multisized_image($language->flage, ['s'])
                ];

        }

        foreach (get_currencies() as $currency) {
            $currencies[] = $currency->code;
            if ($currency->is_default == 1)
                $default_currency = $currency;
        }

        $menus[trans('backend.menu.downloads')] = [

            'icon' => media_file(get_setting('icon_download')),
            'link' => 'downloads'
        ];
        $menus[trans('backend.order.pin_code')] = [
            'icon' => media_file(get_setting('icon_pincode')),
            'link' => 'pin-code'
        ];


        foreach (get_menus() as $menu) {
            if ($menu->type == 'header') {
                $menus[$menu->title] = [
                    'link' => $menu->link,
                    'icon' => media_file($menu->icon)
                ];
            } elseif ($menu->type == 'footer_column_1')
                $footer_1[$menu->title] = $menu->link;
            elseif ($menu->type == 'footer_column_2')
                $footer_2[$menu->title] = $menu->link;
        }
        foreach ($website_setting as $key => $setting) {
            $website_setting[$key] = json_decode(get_setting($setting));
        }

        foreach ($social_media as $key => $setting) {
            $social_media[$key] = get_setting($setting);
        }
        foreach ($contact as $key => $setting) {
            $contact[$key] = get_setting($setting);
        }
        $contact['google_map_iframe'] = get_setting('google_map_iframe');

        foreach ($seo_setting as $key => $setting) {
            $seo_setting[$key] = get_setting($setting);
        }

        if (get_setting('banner_status') == 1) {
            $website_setting['banner_image'] = get_multisized_image(get_setting('banner_image'));
            $website_setting['banner_link'] = get_setting('banner_link');
        }
        if (get_setting('bottom_small_banner_status') == 1) {
            $website_setting['bottom_small_banner_image'] = get_multisized_image(get_setting('bottom_small_banner_image'));
            $website_setting['bottom_small_banner_link'] = get_setting('bottom_small_banner_link');
        }
        if (get_setting('bottom_big_banner_status') == 1) {
            $website_setting['bottom_big_banner_image'] = get_multisized_image(get_setting('bottom_big_banner_image'));
            $website_setting['bottom_big_banner_link'] = get_setting('bottom_big_banner_link');
        }
        $website_setting['top_banner_1'] = get_multisized_image(get_setting('top_banner_1'));
        $website_setting['top_banner_2'] = get_multisized_image(get_setting('top_banner_2'));

        $website_setting['tawk_chat_api'] = get_setting('tawk_chat_api');

        $brands = Brand::query()->where('status', 1)->get();

        $brands_data = [];
        foreach ($brands as $brand) {
            $brands_data[] = [
                'slug' => "/" . $brand->slug,
                'name' => $brand->make,
                'image' => get_multisized_image($brand->image),
            ];
        }

        $manufacturers = Manufacturer::query()->where('status', 1)->get();

        $manufacturers_data = [];
        foreach ($manufacturers as $manufacturer) {
            $manufacturers_data[] = [
                'slug' => $manufacturer->slug,
                'name' => $manufacturer->getTranslations('title'),
                'image' => get_multisized_image($manufacturer->image),
            ];
        }


        $categories = Category::query()->where('status', 1)
            ->whereNull('parent_id')->get();

        $categories_data = [];
        foreach ($categories as $category) {

            $categories_data[] = [
                'slug' => $category->slug,
                'name' => $category->getTranslations('name'),
                'image' => get_multisized_image($category->icon),
                'items' => $category->get_children()
            ];
        }

        $main_menu = [];
        $categories = Category::query()->where('status', 1)
            ->where(function ($q) {
                $q->where('slug', 'like', '%Device-&-Machines%')
                    ->orWhere('slug', 'like', '%key-Remote%')
                    ->orWhere('slug', 'like', '%Accessories-Tools%');
            })
            ->whereNull('parent_id')
            ->with('children')->get();

        foreach ($categories as $category) {

            $main_menu[$category->slug] = [
                'slug' => $category->slug,
                'name' => $category->getTranslations('name'),
                'image' => get_multisized_image($category->icon),
                'items' => $category->get_children_with_transaltions()
            ];
        }
        $device_machines = Category::query()->where('slug', 'Device-Machines')->first();
        $main_menu['device_machines'] = [
            'slug' => 'Device-Machines',
            'name' => getTranslations('frontend.menu.device_machines'),
            'image' => get_multisized_image($device_machines->icon),
            'items' => $device_machines->get_children_with_transaltions(),

        ];
        $manufacturers = Manufacturer::query()->where('status', 1)->get();
        $main_menu['manufacturers'] = [
            'slug' => 'manufacturers',
            'name' => getTranslations('frontend.menu.manufacturers'),
        ];
        foreach ($manufacturers as $manufacturer) {

            $main_menu['manufacturers']['items'][] = [
                'slug' => $manufacturer->slug,
                'name' => $manufacturer->getTranslations('title'),
                'image' => get_multisized_image($manufacturer->image),
            ];
        }

        $main_menu['tokens-software'] = [
            'slug' => 'tokens-software',
            'name' => getTranslations('frontend.menu.tokens_software'),
        ];


//        foreach ($device_machines as $device_machine){
//            $main_menu['device_machines']['items'][]= [
//                'slug' => $device_machine->slug,
//                'name' => $device_machine->getTranslations('name'),
//                'image' => get_multisized_image($device_machine->image),
//                'url' => get_multisized_image($device_machine->image),
//            ];;
//        }

        $main_menu['tokens-software']['items'] = ['software' => [], 'token' => []];
        foreach ($manufacturers as $manufacturer) {
            if ($manufacturer->software == 1) {

                $main_menu['tokens-software']['items']['software'][] = [
                    'slug' => $manufacturer->slug . '?manufacturer-type=software',
                    'name' => $manufacturer->getTranslations('title'),
                    'image' => get_multisized_image($manufacturer->image),
                ];
            }
            if ($manufacturer->token == 1) {
                $main_menu['tokens-software']['items']['token'][] = [
                    'slug' => $manufacturer->slug . '?manufacturer-type=token',
                    'name' => $manufacturer->getTranslations('title'),
                    'image' => get_multisized_image($manufacturer->image),
                ];
            }
        }

        $main_menu['cars'] = [
            'slug' => '#c',
            'name' => getTranslations('frontend.menu.cars'),
        ];
        foreach ($brands as $brand) {
            $main_menu['cars']['items'][] = [
                'slug' => $brand->slug,
                'name' => $brand->getTranslations('make'),
                'image' => get_multisized_image($brand->image),
            ];
        }

        $website_setting['system_logo_icon'] = get_multisized_image(get_setting('system_logo_icon'));
        $website_setting['system_logo_black'] = get_multisized_image(get_setting('system_logo_black'));
        $website_setting['default_images'] = get_multisized_image(get_setting('default_images'));

        $login_setting = [];

        $login_setting['google'] = [
            'enable' => get_setting('google_status'),
            'client_id' => get_setting('google_client_id'),
        ];
        $login_setting['facebook'] = [
            'enable' => get_setting('facebook_status'),
            'app_id' => get_setting('facebook_app_id'),
            'version' => get_setting('facebook_version'),
        ];
        $login_setting['reCaptcha'] = get_setting('reCaptcha_code');

        $seo_setting['meta_image'] = get_multisized_image(get_setting('meta_image'));

        $subscribe = [];
        $subscribe['sender_form'] = get_setting('sender_form');
        $subscribe['sender_form_id'] = get_setting('sender_form_id');
        $subscribe['sender_form_popup'] = get_setting('sender_form_popup');
        $subscribe['sender_form_id_popup'] = get_setting('sender_form_id_popup');

        $setting = [];

        $setting['website'] = $website_setting;
        $setting['social_media'] = $social_media;
        $setting['contact'] = $contact;
        $setting['payment_methods'] = $payment_methods;
        $setting['seo'] = $seo_setting;
        $setting['languages'] = $languages;
        $setting['default_language'] = $default_language;
        $setting['currencies'] = $currencies;
        $setting['default_currency'] = $default_currency;
        $setting['shipping_methods'] = $shipping_methods;
        $setting['menus'] = $menus;
        $setting['footer_1'] = $footer_1;
        $setting['footer_2'] = $footer_2;
        $setting['brands'] = $brands_data;
        $setting['manufacturers'] = $manufacturers_data;
        $setting['categories'] = $categories_data;
        $setting['main_menu'] = $main_menu;
        $setting['login_setting'] = $login_setting;
        $setting['subscribe'] = $subscribe;
        $setting['icons'] = $icon_data;

        return response()->data(['setting' => $setting]);
    }

}
