@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form method="post" action="{{route('backend.setting.update')}}">
            @csrf
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                     aria-expanded="true">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{trans('backend.menu.setting')}}</h3>
                    </div>
                </div>
                <div id="kt_account_settings_profile_details" class="collapse show">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                            @foreach(get_languages() as $key=> $item)
                                <li class="nav-item">
                                    <a class="nav-link  @if($key == 0 ) active @endif" data-bs-toggle="tab"
                                       href="#{{$item->code}}">{{$item->language}}</a>
                                </li>
                            @endforeach

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            @foreach(get_languages() as $key=> $item)
                                <div class="tab-pane fade   @if($key == 0 )show active @endif" id="{{$item->code}}"
                                     role="tabpanel">
                                    <div class="row mb-2">

                                        <label for="system_name_{{$item->code}}"
                                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.system_name')}}</label>

                                        <input required autocomplete="off" type="text" class="form-control "
                                               id="system_name_{{$item->code}}" name="system_name_{{$item->code}}"
                                               value="{{old('system_name_'.$item->code, get_translatable_setting('system_name', $item->code))}}"
                                               placeholder="{{trans('backend.setting.system_name')}}"/>
                                        @error('system_name_'.$item->code) <b class="text-danger"><i
                                                    class="las la-exclamation-triangle"></i> {{$message}}
                                        </b> @enderror
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-10 flex-row-fluid p-2  ">
                <div class="card-body">
                        <div class="row mb-6">
                            <label for="app_url"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.app_url')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="app_url" name="app_url"
                                       class="form-control form-control-lg form-control-solid"
                                       {{media_file(get_setting('app_url'))}}
                                       placeholder="{{trans('backend.setting.app_url')}}"
                                       value="{{old('app_url',get_setting('app_url'))}}">
                                @error('app_url')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label for="low_product_quantity_alert"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.low_product_quantity_alert')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="number" step="1" id="low_product_quantity_alert"
                                       name="low_product_quantity_alert"
                                       class="form-control form-control-lg form-control-solid"
                                       value="{{old('low_product_quantity_alert',get_setting('low_product_quantity_alert'))}}">
                                @error('low_product_quantity_alert')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label for="merchant_app_name"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.merchant_app_name')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="merchant_app_name" name="merchant_app_name"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.setting.merchant_app_name')}}"
                                       value="{{old('merchant_app_name',get_setting('merchant_app_name'))}}">
                                @error('merchant_app_name')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="merchant_id"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.merchant_id')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="merchant_id" name="merchant_id"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.setting.merchant_id')}}"
                                       value="{{old('merchant_id',get_setting('merchant_id'))}}">
                                @error('merchant_id')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="client_credentials_path"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.client_credentials_path')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="client_credentials_path" name="client_credentials_path"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.setting.client_credentials_path')}}"
                                       value="{{old('client_credentials_path',get_setting('client_credentials_path'))}}">
                                @error('client_credentials_path')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="api_currency_key"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.api_currency_key')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="api_currency_key" name="api_currency_key"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.setting.api_currency_key')}}"
                                       value="{{old('api_currency_key',get_setting('api_currency_key'))}}">
                                @error('api_currency_key')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="sender_email_token"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.sender_email_token')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="sender_email_token" name="sender_email_token"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.setting.sender_email_token')}}"
                                       value="{{old('sender_email_token',get_setting('sender_email_token'))}}">
                                @error('sender_email_token')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label for="system_logo_icon"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.system_logo_icon')}}
                            (32x32)
                            </label>
                            <div class="col-lg-8">


                                {!! single_image('system_logo_icon' , media_file(old('system_logo_icon',get_setting('system_logo_icon'))) , old('system_logo_icon',get_setting('system_logo_icon')),'image',['width'=>32 ,'height'=>32] ) !!}
                                <br>
                                @error('system_logo_icon') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>
                        <div class="row mb-6">
                            <label for="system_logo_white"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.system_logo_white')}} (160x50)</label>
                            <div class="col-lg-8">


                                {!! single_image('system_logo_white' , media_file(old('system_logo_white',get_setting('system_logo_white'))) , get_setting('system_logo_white') ,'image',['width'=>160 ,'height'=>50]) !!}
                                <br>
                                @error('system_logo_white') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>
                        <div class="row mb-6">
                            <label for="system_logo_black"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.system_logo_black')}}(160x50)</label>
                            <div class="col-lg-8">


                                {!! single_image('system_logo_black' , media_file(old('system_logo_black',get_setting('system_logo_black'))) , get_setting('system_logo_black'),'image',['width'=>160 ,'height'=>50] ) !!}
                                <br>
                                @error('system_logo_black') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>

                        <div class="row mb-6">
                            <label for="system_logo_black"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.admin_background')}}(160x50)</label>
                            <div class="col-lg-8">
                                {!! single_image('admin_background' , media_file(old('admin_background',get_setting('admin_background'))) ,old('admin_background',get_setting('admin_background')) ,'image',['width'=>160 ,'height'=>50]) !!}
                                <br>
                                @error('admin_background') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>
                        <div class="row mb-6">
                            <label for="default_images"
                                   class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.default_images')}} (400*400) </label>
                            <div class="col-lg-8">


                                {!! single_image('default_images' , media_file(old('default_images',get_setting('default_images'))) , get_setting('default_images') ,'image',['width'=>400 ,'height'=>400]) !!}
                                <br>
                                @error('default_images') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>
                    </div>
            </div>
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                     aria-expanded="true">
                    <div class="card-title m-0">
                        <h3>{{trans('backend.setting.social_login')}}</h3>
                    </div>
                </div>
                <div id="kt_account_settings_profile_details" class="collapse show">

                    <div class="card-body border-top p-9">
{{--                        <div class="row my-6">--}}
{{--                            <label for="reCaptcha_code"--}}
{{--                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.reCaptcha_code')}}</label>--}}
{{--                            <div class="col-lg-8 fv-row fv-plugins-icon-container">--}}
{{--                                <input type="text" id="reCaptcha_code" name="reCaptcha_code"--}}
{{--                                       class="form-control form-control-lg form-control-solid"--}}
{{--                                       placeholder="{{trans('backend.setting.reCaptcha_code')}}"--}}
{{--                                       value="{{old('reCaptcha_code',get_setting('reCaptcha_code'))}}">--}}
{{--                                @error('reCaptcha_code')<b class="text-danger"><i--}}
{{--                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="row my-6">
                            <label for="facebook_app_id"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.facebook_app_id')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="facebook_app_id" name="facebook_app_id"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.setting.facebook_app_id')}}"
                                       value="{{old('facebook_app_id',get_setting('facebook_app_id'))}}">
                                @error('facebook_app_id')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>

{{--                        <div class="row mb-6">--}}
{{--                            <label for="facebook_app_secret"--}}
{{--                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.facebook_app_secret')}}</label>--}}
{{--                            <div class="col-lg-8 fv-row fv-plugins-icon-container">--}}
{{--                                <input type="text" id="facebook_app_secret" name="facebook_app_secret"--}}
{{--                                       class="form-control form-control-lg form-control-solid"--}}
{{--                                       placeholder="{{trans('backend.setting.facebook_app_secret')}}"--}}
{{--                                       value="{{old('facebook_app_secret',get_setting('facebook_app_secret'))}}">--}}
{{--                                @error('facebook_app_secret')<b class="text-danger"><i--}}
{{--                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="row mb-6">
                            <label for="facebook_version"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.facebook_version')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="facebook_version" name="facebook_version"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.setting.facebook_version')}}"
                                       value="{{old('facebook_version',get_setting('facebook_version'))}}">
                                @error('facebook_version')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6 flex-center">
                            <label for="facebook_status"
                                   class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.facebook_status')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                    <input type="checkbox" id="facebook_status" name="facebook_status"
                                           class="form-check-input h-20px w-30px"
                                           {{old('facebook_status',get_setting('facebook_status')) == 1? 'checked':''}}
                                           value="1">
                                </div>
                            </div>
                        </div>
                        <div class="row my-6">
                            <label for="google_client_id"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.google_client_id')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="google_client_id" name="google_client_id"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.setting.google_client_id')}}"
                                       value="{{old('google_client_id',get_setting('google_client_id'))}}">
                                @error('google_client_id')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>

{{--                        <div class="row mb-6">--}}
{{--                            <label for="google_client_secret"--}}
{{--                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.google_client_secret')}}</label>--}}
{{--                            <div class="col-lg-8 fv-row fv-plugins-icon-container">--}}
{{--                                <input type="text" id="google_client_secret" name="google_client_secret"--}}
{{--                                       class="form-control form-control-lg form-control-solid"--}}
{{--                                       placeholder="{{trans('backend.setting.google_client_secret')}}"--}}
{{--                                       value="{{old('google_client_secret',get_setting('google_client_secret'))}}">--}}
{{--                                @error('google_client_secret')<b class="text-danger"><i--}}
{{--                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="row mb-6 flex-center">
                            <label for="google_status"
                                   class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.google_status')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                    <input type="checkbox" id="google_status" name="google_status"
                                           class="form-check-input h-20px w-30px"
                                           {{old('google_status',get_setting('google_status')) == 1? 'checked':''}}
                                           value="1">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

@endsection
