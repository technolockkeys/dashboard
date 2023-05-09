@extends('backend.layout.app')
@section('title',trans('backend.menu.statuses').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.cms.statuses.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.status.create_new_status')}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.cms.statuses.index')}}" class="btn btn-info"><i
                                    class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                        @foreach(get_languages() as $key=> $language)
                            <li class="nav-item">
                                <a class="nav-link  @if($key == 0 ) active @endif" data-bs-toggle="tab"
                                   href="#{{$language->code}}">{{$language->language}}</a>
                            </li>
                        @endforeach

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        @foreach(get_languages() as $key=> $language)
                            <div class="tab-pane fade   @if($key == 0 )show active @endif" id="{{$language->code}}"
                                 role="tabpanel">

                                <div class="row mb-6">
                                    <div class="col form-group ">
                                        <label class="form-label @if($key == 0 ) required @endif"
                                               for="image_{{$language->code}}">{{trans('backend.status.image')}}</label>
                                        <br>
                                        {!! single_image('image_'.$language->code , media_file(old('image_'.$language->code)) , old('image_'.$language->code)  ) !!}
                                        <br>
                                        @error('image_'.$language->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col form-group d-none link_div" id="link_div">
                                        <label for="link_{{$language->code}}"
                                               class="form-label @if($key == 0 ) required @endif">{{trans('backend.status.link')}}</label>
                                        <input type="text" class="form-control" id="link_{{$language->code}}"
                                               name="link_{{$language->code}}"
                                               value="{{old('link_'.$language->code)}}"/>

                                        @error('link_'.$language->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}} </b>@enderror
                                    </div>
                                    <div class="col form-group d-none video_div" id="video_div">
                                        <label for="link_video_{{$language->code}}"
                                               class="form-label @if($key == 0 ) required @endif">{{trans('backend.status.video')}}:
                                            Ex:https://youtu.be/ELqlqpsnR9A</label>
                                        <input type="text" class="form-control" id="video_{{$language->code}}"
                                               name="video_{{$language->code}}"
                                               value="{{old('video_'.$language->code)}}"/>

                                        @error('video_'.$language->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                                    </div>
                                    <div id="media_div_{{$language->code}}" class="col media_div form-group d-none">
                                        <div class="col form-group ">
                                            <label class="form-label @if($key == 0 ) required @endif"
                                                   for="media_{{$language->code}}">{{trans('backend.status.image')}}</label>
                                            <br>
                                            {!! single_image('media_data_'.$language->code , media_file(old('media_data_'.$language->code)) , old('media_data_'.$language->code)) !!}
                                            <br>

                                        </div>
                                    </div>
                                </div>


                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="card flex-row-fluid mb-2 mt-5  ">
                <div class="card-body">
                    <div class="row form-group ">
                        <label class="form-label required"
                               for="type">{{trans('backend.status.type')}}</label>

                        <select class="form-control type" id="type"
                                name="type" required
                                data-control="select2"
                                data-placeholder="Type">
                            <option selected value="{{null}}"></option>
                            @foreach($types as $type)
                                <option value="{{$type}}"
                                        {{old('type') == $type? "selected":"" }}>{{$type}}</option>
                            @endforeach
                        </select>
                        @error('type') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                    </div>

                    <div class="row form-group">
                        <label for="order" class="form-label required">{{trans('backend.status.order')}}</label>
                        <input type="number" min="1" max="10" class="form-control" id="order"
                               name="order"
                               value="{{old('order', $order)}}"/>

                        @error('order')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b>@enderror
                    </div>
                    <div class="form-group row">

                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status')== 1) checked
                                           @endif type="checkbox" value="1"
                                           name="status" id="status"/>
                                    <label class="form-check-label" for="status">
                                        {{trans('backend.global.do_you_want_active')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
             </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
            </div>
    </div>
    </form>
    </div>
@endsection

@section('script')
    <script>

        $(document).on("change", '.type', function () {
            var type = $(this).val();
            var language = $(this).data('language')
            if (type === "link") {
                var link = $('.link_div').removeClass('d-none');
                link.attr("required", "required");

                $('.media_div').addClass('d-none');
                $('.video_div').addClass('d-none');

            }
            if (type === "image") {
                var image = $('.media_div').removeClass('d-none');
                image.attr("required", "required");

                var link = $('.link_div').addClass('d-none');
                link.removeAttr('required');
                var video = $('.video_div').addClass('d-none');
                video.removeAttr('required');
            }
            if (type === 'video') {
                var video = $('.video_div').removeClass('d-none');
                video.attr('required', 'required')
                var link = $('.link_div').addClass('d-none');
                link.removeAttr('required');
                var image = $('.media_div').addClass('d-none');
                image.removeAttr('required');

            }
        });

        $(document).ready(function () {

            @if(!empty(old('type')))
            $("#type").val("{{old('type')}}").change();
            @endif
            @foreach(get_languages() as $language)
            var code = {{$language->code}}
            @if(!empty(old('media_data_'.$language->code)))
            $("#media_data_{{$language->code}}").val('{{old('media_data_'.$language->code)}}')
            @endif

            @if(!empty(old('link_'.$language->code)))
            $("#link_{{$language->code}}").val('{{old('link_'.$language->code)}}')
            @endif
            @if(!empty(old('video_'.$language->code)))
            $("#video_{{$language->code}}").val('{{old('video_'.$language->code)}}')
            @endif

            @endforeach

        })
    </script>
@endsection