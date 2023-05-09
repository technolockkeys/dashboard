@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    <link href="{{asset('backend/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>

@endsection
@section('content')
    <div class="col">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{trans('backend.setting.notifications')}}</h3>
                </div>
            </div>
            <div class="collapse show">
                {{ Form::model( array('method' => 'POST', 'route' => array('backend.setting.notifications.update'))) }}
                @csrf
                <div class="card-body border-top p-9">
                    {{--                    send_reminder_after--}}
                    <div class="row mb-6">
                        <label for="send_reminder_after"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.send_reminder_after')}}
                            ({{trans('backend.offer.days')}})</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="send_reminder_after" name="send_reminder_after"
                                   class="form-control form-control-sm form-control-solid"
                                   placeholder="{{trans('backend.offer.days')}}"
                                   value="{{old('send_reminder_after',get_setting('send_reminder_after'))}}">
                            @error('send_reminder_after')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    @if(false)
                        {{--                    api key--}}
                        <div class="row mb-6">
                            <label for="firebase_apiKey"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.firebase_apiKey')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="firebase_apiKey" name="firebase_apiKey"
                                       class="form-control form-control-sm form-control-solid"
                                       value="{{old('firebase_apiKey',get_setting('firebase_apiKey'))}}">
                                @error('firebase_apiKey')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        {{--                auth domain--}}
                        <div class="row mb-6">
                            <label for="firebase_authDomain"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.firebase_authDomain')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="firebase_authDomain" name="firebase_authDomain"
                                       class="form-control form-control-sm form-control-solid"
                                       value="{{old('firebase_authDomain',get_setting('firebase_authDomain'))}}">
                                @error('firebase_authDomain')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        {{--                 projuct id--}}
                        <div class="row mb-6">
                            <label for="firebase_projectId"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.firebase_projectId')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="firebase_projectId" name="firebase_projectId"
                                       class="form-control form-control-sm form-control-solid"
                                       value="{{old('firebase_projectId',get_setting('firebase_projectId'))}}">
                                @error('firebase_projectId')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        {{--                    storage bucket--}}
                        <div class="row mb-6">
                            <label for="firebase_storageBucket"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.firebase_storageBucket')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="firebase_storageBucket" name="firebase_storageBucket"
                                       class="form-control form-control-sm form-control-solid"
                                       value="{{old('firebase_storageBucket',get_setting('firebase_storageBucket'))}}">
                                @error('firebase_storageBucket')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        {{--               Messaging sender id    --}}
                        <div class="row mb-6">
                            <label for="firebase_messagingSenderId"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.firebase_messagingSenderId')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="firebase_messagingSenderId" name="firebase_messagingSenderId"
                                       class="form-control form-control-sm form-control-solid"
                                       value="{{old('firebase_messagingSenderId',get_setting('firebase_messagingSenderId'))}}">
                                @error('firebase_messagingSenderId')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        {{--            App Id        --}}
                        <div class="row mb-6">
                            <label for="firebase_appId"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.firebase_appId')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="firebase_appId" name="firebase_appId"
                                       class="form-control form-control-sm form-control-solid"
                                       value="{{old('firebase_appId',get_setting('firebase_appId'))}}">
                                @error('firebase_appId')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        {{--             Measurement Id      --}}
                        <div class="row mb-6">
                            <label for="firebase_measurementId"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.firebase_measurementId')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="text" id="firebase_measurementId" name="firebase_measurementId"
                                       class="form-control form-control-sm form-control-solid"
                                       value="{{old('firebase_measurementId',get_setting('firebase_measurementId'))}}">
                                @error('firebase_measurementId')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                    @endif
                    {{--           Server Api key         --}}
                  @if(false)
                    <div class="row mb-6">
                        <label for="firebase_server_api_key"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.firebase_server_api_key')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" id="firebase_server_api_key" name="firebase_server_api_key"
                                   class="form-control form-control-sm form-control-solid"
                                   value="{{old('firebase_server_api_key',get_setting('firebase_server_api_key'))}}">
                            @error('firebase_server_api_key')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    @endif
                    {{--             offers status --}}
                    <div class="row mb-6 flex-center">
                        <label for="offers_status"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.offers_status')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">

                                <input type="checkbox" id="offers_status" name="offers_status"
                                       class="form-check-input h-20px w-30px"
                                       {{old('offers_status',get_setting('offers_status')) == 1? 'checked':''}}
                                       value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="order_notifications_receivers"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.order_notifications_receivers')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="mb-6">
                                <select class="form-control form-control-sm form-control-solid text-dark " id="order_notifications_receivers"
                                        data-regex=".*@*\.com$"
                                        name="order_notifications_receivers[]" multiple data-allow-new="true"
                                        data-separator=" |,|  ">
                                    <option value="" selected>Type a tag...</option>

                                        @foreach(json_decode(get_setting('order_notifications_receivers'),true)??[] as $data)
                                            <option value="{{$data}}" selected>{{$data}}</option>
                                        @endforeach


                                </select>
                                <b class="text-gray-600"> seperated by comma </b>
                            </div>
                            @error('order_notifications_receivers')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="product_notifications_receivers"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.product_notifications_receivers')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="mb-6">
                                <select class="form-control form-control-sm form-control-solid text-dark " id="product_notifications_receivers"
                                        data-regex=".*@*\.com$"
                                        name="product_notifications_receivers[]" multiple data-allow-new="true"
                                        data-separator=" |,|  ">
                                    <option value="" selected>Type a tag...</option>

                                        @foreach(json_decode(get_setting('product_notifications_receivers'),true)??[] as $data)
                                            <option value="{{$data}}" selected>{{$data}}</option>
                                        @endforeach


                                </select>
                                <b class="text-gray-600"> seperated by comma </b>
                            </div>
                            @error('product_notifications_receivers')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="review_notifications_receivers"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.review_notifications_receivers')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="mb-6">
                                <select class="form-control form-control-sm form-control-solid text-dark " id="review_notifications_receivers"
                                        data-regex=".*@*\.com$"
                                        name="review_notifications_receivers[]" multiple data-allow-new="true"
                                        data-separator=" |,|  ">
                                    <option value="" selected>Type a tag...</option>

                                        @foreach(json_decode(get_setting('review_notifications_receivers'),true)??[] as $data)
                                            <option value="{{$data}}" selected>{{$data}}</option>
                                        @endforeach


                                </select>
                                <b class="text-gray-600"> seperated by comma </b>
                            </div>
                            @error('review_notifications_receivers')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="ticket_notifications_receivers"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.ticket_notifications_receivers')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="mb-6">
                                <select class="form-control form-control-sm form-control-solid text-dark " id="ticket_notifications_receivers"
                                        data-regex=".*@*\.com$"
                                        name="ticket_notifications_receivers[]" multiple data-allow-new="true"
                                        data-separator=" |,|  ">
                                    <option value="" selected>Type a tag...</option>

                                        @foreach(json_decode(get_setting('ticket_notifications_receivers'),true)??[] as $data)
                                            <option value="{{$data}}" selected>{{$data}}</option>
                                        @endforeach


                                </select>
                                <b class="text-gray-600"> seperated by comma </b>
                            </div>
                            @error('ticket_notifications_receivers')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label for="contact_us_notifications_receivers"
                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.contact_us_notifications_receivers')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="mb-6">
                                <select class="form-control form-control-sm form-control-solid text-dark " id="contact_us_notifications_receivers"
                                        data-regex=".*@*\.com$"
                                        name="contact_us_notifications_receivers[]" multiple data-allow-new="true"
                                        data-separator=" |,|  ">
                                    <option value="" selected>Type a tag...</option>

                                        @foreach(json_decode(get_setting('contact_us_notifications_receivers'),true)??[] as $data)
                                            <option value="{{$data}}" selected>{{$data}}</option>
                                        @endforeach


                                </select>
                                <b class="text-gray-600"> seperated by comma </b>
                            </div>
                            @error('contact_us_notifications_receivers')<b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary w-25" style="max-height: 42px !important;">{{trans('backend.global.save')}}</button>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection

@section('script')
    {{--    <script src="{{asset('backend/assets/plugins/global/plugins.bundle.js')}}"></script>--}}
    <script type="module">
        import Tags from "https://cdn.jsdelivr.net/gh/lekoala/bootstrap5-tags@master/tags.js";

        Tags.init("select", {
            badgeStyle: 'info',
            // textStyle: 'dark',

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js" type="module"></script>


    <script src="{{asset('backend/plugins/global/plugins.bundle.js')}}"></script>
    <script
            type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"
    ></script>

@endsection
