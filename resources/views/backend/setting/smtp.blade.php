@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{trans('backend.setting.smtp')}}</h3>
                </div>
            </div>
            <div class="collapse show">
                {{ Form::model( array('method' => 'POST', 'route' => array('backend.setting.smtp.update'))) }}
                    @csrf
                    <div class="card-body border-top p-9">
                        <div class="row mb-6">
                            <label for="smtp_type"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.smtp_type')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="smtp_type" name="smtp_type"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Ex: smtp" value="{{old('smtp_type',get_setting('smtp_type'))}}">
                                @error('smtp_type')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="mail_host"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.mail_host')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="mail_host" name="mail_host"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Ex: smtp.mailtrap.io" value="{{old('mail_host',get_setting('mail_host'))}}">
                                @error('mail_host')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="mail_port"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.mail_port')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="mail_port" name="mail_port"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Ex: 2525" value="{{old('mail_port',get_setting('mail_port'))}}">
                                @error('mail_port')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="mail_username"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.mail_username')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="mail_username" name="mail_username"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Ex: b44758xxxxxxxc" value="{{old('mail_username',get_setting('mail_username'))}}">
                                @error('mail_username')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="mail_password"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.mail_password')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="mail_password" name="mail_password"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Ex: ab37xxxxxxxxxx" value="{{old('mail_password',get_setting('mail_password'))}}">
                                @error('mail_password')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="mail_encryption"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.mail_encryption')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="mail_encryption" name="mail_encryption"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Ex: tls" value="{{old('mail_encryption',get_setting('mail_encryption'))}}">
                                @error('mail_encryption')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="mail_from_address"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.mail_from_address')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="email" id="mail_from_address" name="mail_from_address"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Ex: example@example.com" value="{{old('mail_from_address',get_setting('mail_from_address'))}}">
                                @error('mail_from_address')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="mail_from_name"
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.mail_from_name')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="mail_from_name" name="mail_from_name"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Sender Name" value="{{old('mail_from_name',get_setting('mail_from_name'))}}">
                                @error('mail_from_name')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
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
