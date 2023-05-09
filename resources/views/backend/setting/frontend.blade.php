@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    {{ Form::model( array('method' => 'post', 'route' => array('backend.setting.frontend.update'),'id' => 'form1')) }}

    <div class="col mb-12">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{trans('backend.setting.frontend')}}</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                @csrf
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label for="system_logo_white"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.watermark')}} </label>
                        <div class="col-lg-8">
                            {!! single_image('watermark' , media_file(old('watermark',get_setting('watermark'))) , get_setting('watermark')  ,'image' , ['watermark'=>'no']) !!}
                            <br>
                            @error('watermark') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6 flex-center">
                        <label for="watermark_status"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.watermark_status')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                <input type="checkbox" id="watermark_status" name="watermark_status"
                                       class="form-check-input h-20px w-30px"
                                       {{old('watermark_status',get_setting('watermark_status')) == 1? 'checked':''}}
                                       value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6 flex-center">
                        <label for="watermark_status"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.watermark_opacity')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="number" max="100" min="1" id="watermark_opacity" name="watermark_opacity"
                                   class="form-control"
                                   value="{{old('watermark_opacity',get_setting('watermark_opacity')) }}">
                        </div>
                    </div>


                    <div class="row mb-6">
                        <label for="top_banner_1"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.top_banner_1')}}
                            (90 * 40) </label>
                        <div class="col-lg-8">
                            {!! single_image('top_banner_1' , media_file(old('top_banner_1',get_setting('top_banner_1'))) , get_setting('top_banner_1') ,'image',['watermark'=>'no' ,'width'=>90 , 'height'=>40] ) !!}
                            <br>
                            @error('top_banner_1') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="top_banner_2"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.top_banner_2')}}
                            (240 * 40) </label>
                        <div class="col-lg-8">
                            {!! single_image('top_banner_2' , media_file(old('top_banner_2',get_setting('top_banner_2'))) , get_setting('top_banner_2'),'image',['watermark'=>'no' ,'width'=>240 , 'height'=>40] ) !!}
                            <br>
                            @error('top_banner_2') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="system_logo_white"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.banner_image')}}
                            (600 *25) </label>
                        <div class="col-lg-8">
                            {!! single_image('banner_image' , media_file(old('banner_image',get_setting('banner_image'))) , get_setting('banner_image'), 'image',['watermark'=>'no' ,'width'=>600 , 'height'=>25] ) !!}
                            <br>
                            @error('banner_image') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label for="banner_link"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.banner_link')}}</label>
                        <div class="col-8">
                            <input type="text" id="paypal_client_id_test" name="banner_link"
                                   class="form-control form-control-lg"
                                   value="{{old('banner_link',get_setting('banner_link'))}}">
                            @error('banner_link')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6 flex-center">
                        <label for="banner_status"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.banner_status')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                <input type="checkbox" id="banner_status" name="banner_status"
                                       class="form-check-input h-20px w-30px"
                                       {{old('banner_status',get_setting('banner_status')) == 1? 'checked':''}}
                                       value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="bottom_big_banner_image"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.bottom_big_banner_image')}}
                            (620 *160 ) </label>
                        <div class="col-lg-8">
                            {!! single_image('bottom_big_banner_image' , media_file(old('bottom_big_banner_image',get_setting('bottom_big_banner_image'))) , get_setting('bottom_big_banner_image') , 'image',['watermark'=>'no' ,'width'=>620 , 'height'=>160] ) !!}
                            <br>
                            @error('bottom_big_banner_image') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="bottom_big_banner_link"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.bottom_big_banner_link')}}</label>
                        <div class="col-8">
                            <input type="text" id="paypal_client_id_test" name="bottom_big_banner_link"
                                   class="form-control form-control-lg"
                                   value="{{old('bottom_big_banner_link',get_setting('bottom_big_banner_link'))}}">
                            @error('bottom_big_banner_link')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6 flex-center">
                        <label for="bottom_big_banner_status"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.bottom_big_banner_status')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                <input type="checkbox" id="bottom_big_banner_status" name="bottom_big_banner_status"
                                       class="form-check-input h-20px w-30px"
                                       {{old('bottom_big_banner_status',get_setting('bottom_big_banner_status')) == 1? 'checked':''}}
                                       value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="bottom_small_banner_image"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.bottom_small_banner_image')}}
                            (300 * 160) </label>
                        <div class="col-lg-8">
                            {!! single_image('bottom_small_banner_image' , media_file(old('bottom_small_banner_image',get_setting('bottom_small_banner_image'))) , get_setting('bottom_small_banner_image' ), 'image',['watermark'=>'no' ,'width'=>300 , 'height'=>160] ) !!}
                            <br>
                            @error('bottom_small_banner_image') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="bottom_small_banner_link"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.bottom_small_banner_link')}}</label>
                        <div class="col-8">
                            <input type="text" id="paypal_client_id_test" name="bottom_small_banner_link"
                                   class="form-control form-control-lg"
                                   value="{{old('bottom_small_banner_link',get_setting('bottom_small_banner_link'))}}">
                            @error('bottom_small_banner_link')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6 flex-center">
                        <label for="bottom_small_banner_status"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.bottom_small_banner_status')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                <input type="checkbox" id="bottom_small_banner_status" name="bottom_small_banner_status"
                                       class="form-check-input h-20px w-30px"
                                       {{old('bottom_small_banner_status',get_setting('bottom_small_banner_status')) == 1? 'checked':''}}
                                       value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="tawk_chat_api"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.tawk_chat_api')}}</label>
                        <div class="col-8">
                            <input type="text" id="paypal_client_id_test" name="tawk_chat_api"
                                   class="form-control form-control-lg"
                                   value="{{old('tawk_chat_api',get_setting('tawk_chat_api'))}}">
                            @error('tawk_chat_api')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="sender_form"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.sender_form')}}</label>
                        <div class="col-8">
                            <input type="text" id="paypal_client_id_test" name="sender_form"
                                   class="form-control form-control-lg"
                                   value="{{old('sender_form',get_setting('sender_form'))}}">
                            @error('sender_form')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="sender_form_id"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.sender_form_id')}}</label>
                        <div class="col-8">
                            <input type="text" id="paypal_client_id_test" name="sender_form_id"
                                   class="form-control form-control-lg"
                                   value="{{old('sender_form_id',get_setting('sender_form_id'))}}">
                            @error('sender_form_id')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="sender_form_popup"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.sender_form_popup')}}</label>
                        <div class="col-8">
                            <input type="text" id="sender_form_popup" name="sender_form_popup"
                                   class="form-control form-control-lg"
                                   value="{{old('sender_form_popup',get_setting('sender_form_popup'))}}">
                            @error('sender_form_popup')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="sender_form_id_popup"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.sender_form_id_popup')}}</label>
                        <div class="col-8">
                            <input type="text" id="sender_form_id_popup" name="sender_form_id_popup"
                                   class="form-control form-control-lg"
                                   value="{{old('sender_form_id_popup',get_setting('sender_form_id_popup'))}}">
                            @error('sender_form_id_popup')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label for="google_map_iframe"
                               class="col-md-4 col-form-label fw-bold fs-6">{{trans('backend.setting.google_map_iframe')}}</label>
                        <div class="col-8">
                            <input type="text" id="paypal_client_id_test" name="google_map_iframe"
                                   class="form-control form-control-lg"
                                   value="{{old('google_map_iframe',get_setting('google_map_iframe'))}}">
                            @error('google_map_iframe')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>

                    @foreach(['key-remote','accessories-tools','device-machines','manufacturers','token-software','cars','download','pincode'] as $item)
                        <div class="row mb-6">
                            <label for="{{'icon_'.$item}}"
                                   class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.icon_'.$item)}} (70
                                * 70) </label>
                            <div class="col-lg-8">
                                {!! single_image('icon_'.$item , media_file(old('icon_'.$item,get_setting('icon_'.$item))) , get_setting('icon_'.$item ), 'image',['watermark'=>'no' ,'width'=>70 , 'height'=>70] ) !!}
                                <br>
                                @error('icon_'.$item) <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                </div>

            </div>
        </div>

    </div>
    {{ Form::close() }}
@endsection
