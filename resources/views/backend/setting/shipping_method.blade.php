@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{trans('backend.setting.shipping_method')}}</h3>
                </div>
            </div>
            <div class="collapse show">
                {{ Form::model( array('method' => 'POST', 'route' => array('backend.setting.shipping.update'))) }}
                @csrf
                <div class="card-body border-top p-9">

                    @if(false)
                    <div class="row mb-6">
                        <label for="smtp_type"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.dhl')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="number" id="dhl" name="dhl" step="any"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('dhl',get_setting('dhl'))}}">
                            @error('dhl')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}
                            </b> @enderror
                        </div>
                    </div>
                        <input type="hidden" name="dhl" value="0">
                        <input type="hidden" name="shipping_default" value="dhl">
                    @endif

                    <div class="row mb-6">
                        <label for="mail_host"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.fedex')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="number" id="fedex" name="fedex" step="0.01"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('fedex',get_setting('fedex'))}}">
                            @error('fedex')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="mail_port"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.aramex')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="number" id="aramex" name="aramex" step="0.01"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('aramex',get_setting('aramex'))}}">
                            @error('aramex')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="mail_username"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.ups')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="number" id="ups" name="ups" step="0.01"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('ups',get_setting('ups'))}}">
                            @error('ups')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}
                            </b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="free_shipping_cost"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.free_shipping_cost')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="number" id="free_shipping_cost" name="free_shipping_cost" step="any"
                                   class="form-control form-control-lg form-control-solid"
                                   value="{{old('free_shipping_cost',get_setting('free_shipping_cost'))}}">
                            @error('free_shipping_cost')<b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}
                            </b> @enderror
                        </div>
                    </div>


                    @if(false)
                    <div class="row mb-6">
                        <label for="mail_username"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.shipping_default')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <select type="number" id="shipping_default" name="shipping_default" data-control="select2"
                                    class="form-control form-control-lg form-control-solid">
                                <option @if(old('shipping_default',get_setting('shipping_default')) == 'dhl') selected @endif value="dhl">{{trans('backend.setting.dhl')}}</option>
                                <option @if(old('shipping_default',get_setting('shipping_default')) == 'fedex') selected @endif value="fedex">{{trans('backend.setting.fedex')}}</option>
                                <option @if(old('shipping_default',get_setting('shipping_default')) == 'aramex') selected @endif value="aramex">{{trans('backend.setting.aramex')}}</option>
                                <option @if(old('shipping_default',get_setting('shipping_default')) == 'ups') selected @endif value="ups">{{trans('backend.setting.ups')}}</option>
                            </select>
                            @error('shipping_default')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}
                            </b> @enderror
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>


@endsection
