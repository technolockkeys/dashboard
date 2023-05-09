@extends('backend.layout.app')
@section('title',trans('backend.menu.sliders').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.cms.sliders.update', $slider->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.slider.edit_slider',['name'=>$slider->id])}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.cms.sliders.index')}}" class="btn btn-info"><i
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
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label @if($key == 0)required @endif"
                                                   for="link_{{$language->code}}">{{trans('backend.slider.link')}}</label>
                                            <input type="text" class="form-control" @if($key == 0)required
                                                   @endif id="name_{{$language->code}}"
                                                   name="link_{{$language->code}}"
                                                   value="{{old('link_'.$language->code, $slider->getTranslation('link', $language->code))}}">
                                            @error('link_'.$language->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col form-group ">
                                        <label class="form-label" for="{{'image_'.$language->code}}">{{trans('backend.slider.image')}}</label>
                                        <br>
                                        {!! single_image('image_'.$language->code , media_file(old('image_'.$language->code, $slider->getTranslation('image', $language->code))) , old('image_'.$language->code, $slider->getTranslation('image', $language->code))  ) !!}
                                        <br>
                                        @error('image_'.$language->code)<b class="text-danger"> <i
                                                class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="card flex-row-fluid mb-2 mt-5  ">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <div class="form-group">
                                    <label class="form-label"
                                           for="type">{{trans('backend.slider.type')}}</label>
                                    <select class="form-control type" id="type"
                                            name="type" required
                                            data-control="select2"
                                            data-placeholder="Type">

                                        <option {{old('type',$slider->type) == 'main'? "selected":"" }} data-width="800"
                                                data-height="460"
                                                value="main">{{trans('backend.slider.main')}}</option>
                                        <option
                                            {{old('type',$slider->type) == 'banner'? "selected":"" }} data-width="450"
                                            data-height="650"
                                            value="banner">{{trans('backend.slider.banner')}}</option>


                                    </select>
                                    @error('type') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                                </div>
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px"
                                           @if(old('status',$slider->status) == 1) checked @endif type="checkbox"
                                           value="1"
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
        var languags = {!! get_languages() !!}

        function check_size() {
            var width = $("#type").find(':selected').data('width');
            var height = $("#type").find(':selected').data('height');
            console.log(width +" " + height)
            for (var i = 0; i < languags.length; i++) {
                $("#button_single_image_" + languags[i].code).attr('data-width', width);
                $("#button_single_image_" + languags[i].code).attr('data-height', height);
                $("label[for=image_" + languags[i].code + "]").text("{{trans('backend.slider.image')}} (" + height + '*' + width + ")")
            }
        }

        $(document).ready(function () {
            check_size()
        })
        $(document).on('change', '#type', function () {
            check_size();
        })
    </script>
@endsection
