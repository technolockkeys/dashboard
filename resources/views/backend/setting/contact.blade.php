@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{trans('backend.setting.social.contact')}}</h3>
                </div>
            </div>
            <div class="collapse show">
                {{ Form::model( array('method' => 'POST', 'route' => array('backend.setting.social.update'))) }}
                @csrf
                <div class="card-body border-top p-9">

                    <div class="row mb-6">
                        <label for="contact_email"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.social.social_email')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="email" id="contact_email" name="contact_email"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('contact_email',get_setting('contact_email'))}}">
                            @error('contact_email')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="contact_email_secondary"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.social.social_email')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="email" id="contact_email_secondary" name="contact_email_secondary"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('contact_email_secondary',get_setting('contact_email_secondary'))}}">
                            @error('contact_email_secondary')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="contact_telegram"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.social.social_telegram')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="contact_telegram" name="contact_telegram"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('contact_telegram',get_setting('contact_telegram'))}}">
                            @error('contact_telegram')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="contact_whatsapp"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.social.social_whatsapp')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="contact_whatsapp" name="contact_whatsapp"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('contact_whatsapp',get_setting('contact_whatsapp'))}}">
                            @error('contact_whatsapp')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="contact_phone"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.social.social_phone')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="tel" id="contact_phone" name="contact_phone"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('contact_phone',get_setting('contact_phone'))}}">
                            @error('contact_phone')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="contact_phone_secondary"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.social.social_phone')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="tel" id="contact_phone_secondary" name="contact_phone_secondary"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('contact_phone_secondary',get_setting('contact_phone_secondary'))}}">
                            @error('contact_phone_secondary')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="contact_address"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.contact.address')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <textarea  type="text-area" id="contact_address" name="contact_address"
                                   class="form-control form-control-lg form-control-solid"
                                       >{{old('contact_address',get_setting('contact_address'))}}"</textarea>
                            @error('contact_address')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>


@endsection
