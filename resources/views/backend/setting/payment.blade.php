@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    {{ Form::model( array('method' => 'post', 'route' => array('backend.setting.payment.update'),'id' => 'form1')) }}

    <div class="col mb-12">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{trans('backend.setting.paypal')}}</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                @csrf
                <div class="card-body border-top p-9">
                    <div class="row">

                        <div class="col-12 mb-6">
                            <label for="paypal_client_id"
                                   class=" col-form-label required fw-bold fs-6">{{trans('backend.setting.paypal_client_id')}}</label>
                            <input type="text" id="paypal_client_id" name="paypal_client_id"
                                   class="form-control form-control-lg form-control-solid"
                                   placeholder="" value="{{old('paypal_client_id',get_setting('paypal_client_id'))}}">
                            @error('paypal_client_id')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
{{--                        <div class="col-6 mb-6">--}}

{{--                            <label for="paypal_client_secret"--}}
{{--                                   class=" col-form-label required fw-bold fs-6">{{trans('backend.setting.paypal_client_secret')}}</label>--}}
{{--                            <input type="text" id="paypal_client_secret" name="paypal_client_secret"--}}
{{--                                   class="form-control form-control-lg form-control-solid"--}}
{{--                                   value="{{old('paypal_client_secret',get_setting('paypal_client_secret'))}}">--}}
{{--                            @error('paypal_client_secret')<b class="text-danger"><i--}}
{{--                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror--}}
{{--                        </div>--}}
                    </div>
                    <div class="row">

                        <div class="col mb-6">
                            <label for="smtp_type"
                                   class="col-lg-6 col-form-label required fw-bold fs-6">{{trans('backend.setting.paypal_client_id_test')}}</label>
                            <input type="text" id="paypal_client_id_test" name="paypal_client_id_test"
                                   class="form-control form-control-lg form-control-solid"
                                   placeholder=""
                                   value="{{old('paypal_client_id_test',get_setting('paypal_client_id_test'))}}">
                            @error('paypal_client_id_test')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
{{--                        <div class="col-6 mb-6">--}}
{{--                            <label for="paypal_client_secret_test"--}}
{{--                                   class=" col-form-label required fw-bold fs-6">{{trans('backend.setting.paypal_client_secret_test')}}</label>--}}
{{--                            <input type="text" id="paypal_client_secret_test" name="paypal_client_secret_test"--}}
{{--                                   class="form-control form-control-lg form-control-solid"--}}
{{--                                   value="{{old('paypal_client_secret_test',get_setting('paypal_client_secret_test'))}}">--}}
{{--                            @error('paypal_client_secret_test')<b class="text-danger"><i--}}
{{--                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror--}}
{{--                        </div>--}}
                    </div>
                    <div class="row mb-6 flex-center">
                        <label for="paypal_sandbox_mode"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.paypal_sandbox_mode')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                <input type="checkbox" id="paypal_sandbox_mode" name="paypal_sandbox_mode"
                                       class="form-check-input h-20px w-30px"
                                       {{old('paypal_sandbox_mode',get_setting('paypal_sandbox_mode')) == 1? 'checked':''}}
                                       value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6 flex-center">
                        <label for="paypal_status"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.paypal_status')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                <input type="checkbox" id="paypal_status" name="paypal_status"
                                       class="form-check-input h-20px w-30px"
                                       {{old('paypal_status',get_setting('paypal_status')) == 1? 'checked':''}}
                                       value="1">
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="col mb-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                     aria-expanded="true">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{trans('backend.setting.stripe')}}</h3>
                    </div>
                </div>
                <div id="kt_account_settings_profile_details" class="collapse show">

                    <div class="card-body border-top p-9">
                        <div class="row mb-6">
                            <div class="col-12 mb-6">
                                <label for="strip_key"
                                       class=" col-form-label required fw-bold fs-6">{{trans('backend.setting.stripe_key')}}</label>
                                <input type="text" id="strip_key" name="strip_key"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="" value="{{old('strip_key',get_setting('strip_key'))}}">
                                @error('stripe_key')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
{{--                            <div class="col-6 mb-6">--}}
{{--                                <label for="strip_secret"--}}
{{--                                       class=" col-form-label required fw-bold fs-6">{{trans('backend.setting.stripe_secret')}}</label>--}}
{{--                                <input type="text" id="strip_secret" name="strip_secret"--}}
{{--                                       class="form-control form-control-lg form-control-solid"--}}
{{--                                       value="{{old('strip_secret',get_setting('strip_secret'))}}">--}}
{{--                                @error('stripe_secret')<b class="text-danger"><i--}}
{{--                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror--}}
{{--                            </div>--}}
                        </div>

                        <div class="row mb-6">
                            <div class="col-12 mb-6">
                                <label for="strip_key_test"
                                       class=" col-form-label required fw-bold fs-6">{{trans('backend.setting.stripe_key_test')}}</label>
                                <input type="text" id="strip_key_test" name="strip_key_test"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder=""
                                       value="{{old('strip_key_test',get_setting('strip_key_test'))}}">
                                @error('strip_key_test')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
{{--                            <div class="col-6 mb-6">--}}
{{--                                <label for="strip_secret_test"--}}
{{--                                       class=" col-form-label required fw-bold fs-6">{{trans('backend.setting.stripe_secret_test')}}</label>--}}
{{--                                <input type="text" id="strip_secret_test" name="strip_secret_test"--}}
{{--                                       class="form-control form-control-lg form-control-solid"--}}
{{--                                       value="{{old('strip_secret_test',get_setting('strip_secret_test'))}}">--}}
{{--                                @error('stripe_secret_test')<b class="text-danger"><i--}}
{{--                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror--}}
{{--                            </div>--}}
                        </div>

                        <div class="row mb-6 flex-center">
                            <label for="stripe_sandbox_mode"
                                   class="col-lg-4 col-form-label  fw-bold fs-6">{{trans('backend.setting.stripe_sandbox_mode')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                    <input type="checkbox" id="stripe_sandbox_mode" name="stripe_sandbox_mode"
                                           class="form-check-input h-20px w-30px"
                                           {{old('stripe_sandbox_mode',get_setting('stripe_sandbox_mode')) == 1? 'checked':''}}
                                           value="1">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6 flex-center">
                            <label for="stripe_status"
                                   class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.stripe_status')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                    <input type="checkbox" id="stripe_status" name="stripe_status"
                                           class="form-check-input h-20px w-30px"
                                           {{old('stripe_status',get_setting('stripe_status')) == 1? 'checked':''}}
                                           value="1">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" onclick="submitForms()"
                                class="btn btn-primary">{{trans('backend.global.save')}}</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
    {{ Form::close() }}
@endsection
