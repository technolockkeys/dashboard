<?php

use App\Models\Language;

if (!function_exists('isRTL')) {
    /**
     * Check if the request has RTL param
     *
     * @return bool
     */
    function isRTL()
    {
        return (bool)request()->input('rtl');
    }
}

if (!function_exists('datatable_style')) {
    /**
     * print datatable style
     *
     * @return bool
     */
    function datatable_style()
    {
        return '
         <link href="' . asset('backend/plugins/custom/datatables/datatables.bundle.css') . '" rel="stylesheet" type="text/css"/>
        ';
    }
}

if (!function_exists('datatable_script')) {
    /**
     * print datatable script
     *
     * @return bool
     */
    function datatable_script()
    {
        return '<script src="' . asset('backend/plugins/custom/datatables/datatables.bundle.js') . '"></script>';
    }
}

if (!function_exists('debug_request')) {
    function debug_request()
    {
        return (new \App\Http\Helpers\Debug())->get();
    }
}

if (!function_exists('permission_can')) {
    function permission_can($permission, $guard)
    {
        if (auth($guard)->check()) {
            return auth($guard)->user()->can($permission);
        }
        return false;
    }
}

if (!function_exists('single_image')) {
    function single_image($name, $old_image, $value, $type = 'image', $option = [])
    {
        $water_mark = 'true';
        if (!empty($option['watermark'])) {
            $water_mark = ($option['watermark'] == 'no' || $option['watermark'] == false || $option['watermark'] == '-1') ? 'false' : 'true';

        }

        if (get_setting('watermark_status') != 1) {
            $water_mark = 'false';
        }
        $height = 600;
        $width = 600;
        if (isset($option['height']) && !empty($option['height'])) {
            $height = $option['height'];
        }
        if (isset($option['width']) && !empty($option['width'])) {
            $width = $option['width'];
        }

        return view('backend.shared.media.single', compact('name', 'width', 'height', 'old_image', 'water_mark', 'value', 'type'))->render();
    }
}

if (!function_exists('multi_images')) {
    function multi_images($name, $old_image, $value, $type = 'image', $small = false)
    {
        $path = '/';
        if (!empty(json_decode($old_image))) {
            $media_path = \App\Models\MediaFiles::query()->where('id', json_decode($old_image)[0])->orderBy('id')->first();
            $path = $media_path->path;
        }


        return view('backend.shared.media.multi', compact('name', 'old_image', 'value', 'path', 'type', 'small'))->render();
    }
}

if (!function_exists('model_media')) {
    function model_media()
    {
        return view('backend.shared.media.model')->render();
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $value = null)
    {

//        if (Cache::has($key) && !empty(Cache::get($key)) && empty($value)) {
//            return Cache::get($key);
//        }

        if (empty($value)) {
            $setting = \App\Models\Setting::query()->where('type', $key)->first();

            if (!empty($setting)) {
//                Cache::put($key, $setting->value);
                return $setting->value;

            }
            return "";
        } else {
            $setting = \App\Models\Setting::query()->where('type', $key)->first();


            if (empty($setting)) {
                $setting = new \App\Models\Setting();
                $setting->type = $key;
            }
            $setting->value = $value;
            $setting->save();
            Cache::forget($key);
            Cache::flush();
            Cache::delete($key);
            return $value;

        }
    }
}

if (!function_exists('set_setting')) {
    function set_setting($key, $value)
    {

        $setting = \App\Models\Setting::query()->firstOrNew(['type' => $key]);
        $setting->type = $key;
        $setting->value = gettype($value) == 'array' ? json_encode($value) : $value;

        $setting->save();
        Cache::forget($key);
        Cache::flush();
        Cache::delete($key);
        return $value;


    }
}

if (!function_exists('get_translatable_setting')) {
    function get_translatable_setting($key, $locale)
    {
//        if (Cache::has($key) && !empty(Cache::get($key)) && empty($value)) {
//            $setting = Cache::get($key);
//            $value = json_decode($setting, true)[$locale] ?? $setting;
//            return $value;
//        }

        $setting = \App\Models\Setting::query()->where('type', $key)->first();
        if (!$setting) {
            $setting = new \App\Models\Setting();
            $setting->key = $key;
            $setting->value = json_encode([$locale => '']);
            $setting->save();
        }
        Cache::forget($key);
        try {
            Cache::flush();
        } catch (Exception $exception) {
            \Illuminate\Support\Facades\Log::debug($exception->getMessage());
        }
        Cache::delete($key);
        $value = json_decode($setting->value, true)[$locale] ?? $setting->value;

        return $value;


    }
}
if (!function_exists('media_file')) {
    function media_file($id = null, $return_media_object = false)
    {
        if (!isset($id)) {
            return asset('backend/media/svg/files/blank-image.svg');
        }
        if (\Illuminate\Support\Facades\Cache::has('media_' . $id)) {
            $mediaFile = \Illuminate\Support\Facades\Cache::get('media_' . $id);
        } else {
            $mediaFile = \App\Models\MediaFiles::find($id);
            try {
                \Illuminate\Support\Facades\Cache::put('media_' . $id, $mediaFile);
            } catch (\Exception $e) {
//               Log::info("esg-ex : " . $e->getMessage());
            }
        }
        $mediaFile = \App\Models\MediaFiles::find($id);

        if ($id == 39202) {

            \Illuminate\Support\Facades\Log::info($id);
            \Illuminate\Support\Facades\Log::info($mediaFile->alt);

        }
        if ($return_media_object) {

            return $mediaFile;
        }
        if (!empty($mediaFile)) {

            if (in_array($mediaFile->extension, ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief', 'jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'webp', 'wbmp', 'xbm', 'xpm', 'xwd', 'jfif'])) {
                return asset(
                    implode("", ['storage', $mediaFile->path, $mediaFile->title]));
            }
            if (in_array($mediaFile->extension, ['pdf'])) {
                return asset('backend/media/svg/files/pdf.svg');
            }
            return $mediaFile;
        }
        return asset('backend/media/svg/files/blank-image.svg');

    }
}

function RandomString()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 16; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function get_multisized_image($id, $sizes = [])
{


    if (!\Illuminate\Support\Facades\Cache::has("media_" . $id)) {
        $mediaFile = \App\Models\MediaFiles::find($id);
        if (!empty($mediaFile)) {
            try {
                \Illuminate\Support\Facades\Cache::put('media_' . $id, $mediaFile);
            } catch (\Exception $exception) {

            }
        }
    } else {
        $mediaFile = \Illuminate\Support\Facades\Cache::get("media_" . $id);
    }


    if (!isset($id) || empty($mediaFile)) {
        if (get_setting('default_images') == null)
            return asset('backend/media/svg/files/blank-image.svg');
        return get_multisized_image(get_setting('default_images'), $sizes);
    }

    $ids = json_decode($mediaFile->related_images_ids, true) ?? [
        'original_id' => $id,
        'medium_id' => $id,
        'thumbnail_id' => $id,
    ];

//    if (\Illuminate\Support\Facades\Cache::has('get_multisized_image_' . $id) ) {
//        return \Illuminate\Support\Facades\Cache::get('get_multisized_image_' . $id);
//    }
    $links = [];
    $sizes = $sizes == [] ? ['s', 'l', 'm'] : $sizes;
//    {"original_id":9998,"medium_id":9999,"thumbnail_id":10000}
    $mediaFileDefault = media_file(get_setting('default_images'));
    foreach ($sizes as $size) {
        $_id = $size == 's' ? 'thumbnail_id' : ($size == 'm' ? 'medium_id' : 'original_id');
        $item_id = $size == 'l' ? $id : $ids[$_id];

        if (!\Illuminate\Support\Facades\Cache::has("media_" . $item_id)) {
            $image = \App\Models\MediaFiles::find($item_id);
            if (!empty($mediaFile)) {
                try {
                    \Illuminate\Support\Facades\Cache::put('media_' . $item_id, $image);
                } catch (\Exception $exception) {

                }
            } else {
                $image = $mediaFile;
            }
        } else {
            $image = \Illuminate\Support\Facades\Cache::get("media_" . $item_id);
        }


        $exists = file_exists(public_path() . '/storage/' . $image?->path . ($image?->title));
        $exists_default = file_exists(public_path() . '/storage/' . $mediaFile?->path . ($mediaFile?->title));
        $rep_url = "";
        if ($exists_default) {
            $rep_url = asset(implode('', ['storage', $mediaFile->path, $mediaFile->title]));
        } else {
            $rep_url = $mediaFileDefault;
        }
        $links[$size] =
//            file_exists(public_path() . '/storage/' . $image->path . ($image->title))
//                ?
            [
                'id' => RandomString(),
                'alt' => $image?->alt,
                'url' => $exists ? asset('storage/' . ($image?->path == '/' ? "" : $image?->path) . ($image?->title)) : $rep_url,
                'width' => $image?->width,
                'height' => $image?->height,
                'rel' => $image?->rel,
                'description' => $image?->description,
                'title' => $image?->title,
                'open_graph' => $image?->open_graph,
                'scale' => $image?->scale,
            ];
//        : get_multisized_image(get_setting('default_images'), [$size])[$size];
        if (!empty($links['s'])) {
            if (empty($links['m'])) {
                $links['m'] = $links['s'];
            }
            if (empty($links['l'])) {
                $links['l'] = $links['s'];
            }
        }
    }
    try {
        \Illuminate\Support\Facades\Cache::put('get_multisized_image_' . $id, $links);
    } catch (\Exception $exception) {

    }

    if (!empty($links['s'])) {
        if (empty($links['m'])) {
            $links['m'] = $links['s'];
        }
        if (empty($links['l'])) {
            $links['l'] = $links['s'];
        }
    }


    if (!empty($links['m'])) {
        if (empty($links['l'])) {
            $links['l'] = $links['m'];
        }
        if (empty($links['s'])) {
            $links['s'] = $links['m'];
        }
    }


    if (!empty($links['l'])) {
        if (empty($links['m'])) {
            $links['m'] = $links['l'];
        }
        if (empty($links['s'])) {
            $links['s'] = $links['l'];
        }
    }


    return $links;

}

function convertToKebabCase(string $string)
{
    $string = preg_replace('/[\s.]+/', '-', $string);
    $string = preg_replace('/[^0-9a-zA-Z_\-]/', '-', $string);
    $string = strtolower(preg_replace('/[A-Z]+/', '-\0', $string));
    $string = trim($string, '-');

    return preg_replace('/[_\-][_\-]+/', '-', $string);
}

if (!function_exists('get_currencies')) {

    function get_currencies()
    {
//        if (Cache::has('currencies')) {
//            return Cache::get('currencies');
//        }
        $currencies = \App\Models\Currency::where('status', 1)->orderBy('is_default', 'desc')->get();
        try {
            Cache::put('currencies', $currencies);
        } catch (\Exception $exception) {

        }
        return $currencies;

    }
}

if (!function_exists('get_menus')) {

    function get_menus()
    {
//        if (Cache::has('menus')) {
//            return Cache::get('menus');
//        }
        $menus = \App\Models\Menu::where('status', 1)->get();
        try {
            Cache::put('menus', $menus);
        } catch (\Exception $exception) {

        }
        return $menus;
    }

}

if (!function_exists('get_languages')) {
    function get_languages()
    {
//        if (Cache::has('language')) {
//            return Cache::get('language');
//        } else {
        $languages = \App\Models\Language::query()->where('status', 1)->orderByDesc('is_default')->get();
        try {
            Cache::put('language', $languages);
        } catch (\Exception $exception) {

        }
        return $languages;
//        }
    }
}

if (!function_exists('get_countries')) {
    function get_countries()
    {
        return \App\Models\Country::query()->where('status', 1)->pluck('name', 'id');
    }
}

if (!function_exists('langArray')) {
    function langArray()
    {
        if (Cache::has('langArray')) {
            $langArray = Cache::get('langArray');
        } else {
            $langArray = \App\Models\Language::query()->where('status', 1)->orderByDesc('is_default')->get()->toArray();
            try {
                Cache::put('langArray', $langArray);
            } catch (\Exception $exception) {

            }
        }
        return $langArray;
    }
}

if (!function_exists('editor_script')) {
    function editor_script()
    {
        return '<script src="https://cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>';
    }
}

if (!function_exists('select_status')) {
    function select_status()
    {
        return view('backend.shared.select_status')->render();
    }
}

if (!function_exists('apply_filter_button')) {
    function apply_filter_button()
    {
        return view('backend.shared.filter_button')->render();
    }
}

if (!function_exists('multi_select_2')) {
    function multi_select_2($name, $options, $translation)
    {
        return view('backend.shared.multiselect2', compact('name', 'options', 'translation'))->render();
    }
}

if (!function_exists('select_bool')) {
    function select_bool($name, $translation)
    {
        return view('backend.shared.select_switch', compact('name', 'translation'))->render();
    }
}

if (!function_exists('currency')) {
    function currency($amount)
    {
        return number_format($amount, 2) . " $";
    }
}

if (!function_exists('api_currency')) {
    function api_currency($amount, \App\Models\Currency $currency)
    {

        return ["value" => number_format($amount * $currency->value, 2, '.', ''), "currency" => $currency->symbol];
    }
}

if (!function_exists('script_check_slug')) {
    function script_check_slug($route, $name = 'slug', $from = null)
    {
        $view = view('backend.shared.script_check_slug', compact('route', 'name', 'from'))->render();
        return $view;
    }
}

if (!function_exists('form_seo')) {
    function form_seo($code, $key, $title = null, $description = null)
    {
        $view = view('backend.shared.seo.form', compact('code', 'key', 'title', 'description'))->render();
        return $view;
    }
}
if (!function_exists('random_color_part')) {
    function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }
}
if (!function_exists('random_color')) {

    function random_color()
    {
        return random_color_part() . random_color_part() . random_color_part();
    }
}
if (!function_exists('exc_currency')) {

    function exc_currency($number, $exchange, $symbol)
    {
        try {
            $number = number_format(floatval($number), 2, '.', '');
            $exchange = number_format(floatval($exchange), 2, '.', '');
            return number_format($number * $exchange, 2) . ' ' . $symbol;
        } catch (Exception $exception) {
            Log::error("EX : " . json_encode(['message' => $exception->getMessage(), 'exchange' => $exchange, 'symbol' => $symbol, 'number' => $number]));
            return "ERROR";
        }
    }
}

if (!function_exists('api_exc_currency')) {

    function api_exc_currency($number, $exchange_rate, $symbol)
    {
        try {
            return ["value" => number_format($number * $exchange_rate, 2, '.', ''), "currency" => $symbol];

            return number_format(number_format($number, 2) * number_format($exchange, 2), 2) . ' ' . $symbol;
        } catch (Exception $exception) {
            return "ERROR";
        }
    }
}


if (!function_exists('check_image')) {

    function check_image($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result !== FALSE) {
            return $url;
        } else {
            return media_file(get_setting('default_images'));
        }
    }
}

if (!function_exists('check_slug')) {
    function check_slug($model, $slug, $column = 'slug')
    {
        $check = $model->where($column, $slug);

        $check = $check->count();
        if ($check == 0) {
            return $slug;
        }
        return check_slug($model, $slug . '-' . rand(1000, 9999), $column);
    }
}

if (!function_exists('getTranslations')) {

    function getTranslations($key, $attr = [])
    {
        $languages = get_languages();
        $data = [];
        foreach ($languages as $language) {

            try {
                $data[$language->code] = trans($key, $attr, $language->code);
            } catch (Exception $exception) {
                $data[$language->code] = trans($key, $attr);

            }
        }
        return $data;
    }

}
