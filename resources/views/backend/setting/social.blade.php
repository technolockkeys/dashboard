@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer"
                 aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{trans('backend.setting.social.social')}}</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                {{ Form::model( array('method' => 'POST', 'route' => array('backend.setting.social.update'))) }}
                @csrf
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label for="social_email"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social.social_email')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="email" id="social_email" name="social_email"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_email',get_setting('social_email'))}}">
                            @error('social_email')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_facebook"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social.social_facebook')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_facebook" name="social_facebook"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_facebook',get_setting('social_facebook'))}}">
                            @error('social_facebook')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_twitter"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social.social_twitter')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_twitter" name="social_twitter"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_twitter',get_setting('social_twitter'))}}">
                            @error('social_twitter')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_telegram"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social.social_telegram')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_telegram" name="social_telegram"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_telegram',get_setting('social_telegram'))}}">
                            @error('social_telegram')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_whatsapp"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social.social_whatsapp')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_whatsapp" name="social_whatsapp"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_whatsapp',get_setting('social_whatsapp'))}}">
                            @error('social_whatsapp')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_phone"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social.social_phone')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="tel" id="social_phone" name="social_phone"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_phone',get_setting('social_phone'))}}">
                            @error('social_phone')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_tiktok"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social.social_tiktok')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_tiktok" name="social_tiktok"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_tiktok',get_setting('social_tiktok'))}}">
                            @error('social_tiktok')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_instagram"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social_instagram')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_instagram" name="social_instagram"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_instagram',get_setting('social_instagram'))}}">
                            @error('social_instagram')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_wechat"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social_wechat')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_wechat" name="social_wechat"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_wechat',get_setting('social_wechat'))}}">
                            @error('social_wechat')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_pinterest"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social_pinterest')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_pinterest" name="social_pinterest"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_pinterest',get_setting('social_pinterest'))}}">
                            @error('social_pinterest')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_reddit"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social_reddit')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_reddit" name="social_reddit"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_reddit',get_setting('social_reddit'))}}">
                            @error('social_reddit')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_quora"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social_quora')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_quora" name="social_quora"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_quora',get_setting('social_quora'))}}">
                            @error('social_quora')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_skype"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social_skype')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_skype" name="social_skype"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_skype',get_setting('social_skype'))}}">
                            @error('social_skype')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="social_linkedin"
                               class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.social_linkedin')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="social_linkedin" name="social_linkedin"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('social_linkedin',get_setting('social_linkedin'))}}">
                            @error('social_linkedin')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary" >{{trans('backend.global.save')}}</button>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>


@endsection
