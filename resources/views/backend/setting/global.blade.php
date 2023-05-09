@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    <link href="{{asset('backend/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome -->

@endsection
@section('content')

    <div class="col">
        <form method="post" action="{{route('backend.setting.global.update')}}">
            @csrf
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                     aria-expanded="true">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{trans('backend.setting.global_seo')}}</h3>
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

                                        <label for="meta_title_{{$item->code}}"
                                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.meta_title')}}</label>

                                        <input required autocomplete="off" type="text" class="form-control "
                                               id="meta_title_{{$item->code}}" name="meta_title_{{$item->code}}"
                                               value="{{old('meta_title_'.$item->code, get_translatable_setting('meta_title', $item->code))}}"
                                               placeholder="{{trans('backend.setting.meta_title')}}"/>
                                        @error('meta_title_'.$item->code) <b class="text-danger"><i
                                                    class="las la-exclamation-triangle"></i> {{$message}}
                                        </b> @enderror
                                    </div>
                                    <div class="row mb-2">
                                        <label for="meta_description_{{$item->code}}"
                                               class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.meta_description')}}</label>
                                        {{--                                        <div class="col-lg-8 fv-row fv-plugins-icon-container">--}}
                                        <textarea type="textarea" id="meta_description_{{$item->code}}"
                                                  name="meta_description_{{$item->code}}"
                                                  class="form-control form-control-lg "
                                        > {{old('meta_description_'.$item->code,get_translatable_setting('meta_description', $item->code))}} </textarea>
                                        @error('meta_description')<b class="text-danger"><i
                                                    class="las la-exclamation-triangle"></i> {{$message}}
                                        </b> @enderror
                                        {{--                                        </div>--}}
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="card flex-row-fluid p-2  ">
                <div class="card-body">

                <div class="row mb-6">
                    <label for="keywords"
                           class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.setting.keywords')}}</label>
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
{{--                        @dd(json_decode(get_setting('keywords'), true))--}}
                        <div class="mb-6">
                            {{--                                    <input name="keywords" class="form-control form-control-solid" value="" id="keywords"/>--}}
                            {{--                                        <input name="keywords" class="form-control" value="{{get_setting('keywords')}}" data-role="tagsinput"  id="keywords"/>--}}
                            <select class="form-control form-control-lg text-dark " id="keywords"
                                    name="keywords[]" multiple data-allow-new="true" data-separator=" |,|  ">
                                <option value="" selected>Type a tag...</option>
                                @if(!empty(json_decode(get_setting('keywords'))) != null)
                                    @foreach(json_decode(get_setting('keywords'), true) as $key => $keyword)
                                        <option value="{{$keyword}}" selected>{{$keyword}}</option>
                                    @endforeach
                                @endif

                            </select>
                            <b class="text-gray-600"> seperated by comma </b>
                        </div>
                        @error('keywords')<b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                    </div>
                </div>
                <div class="row mb-6">
                    <label for="system_logo_white"
                           class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.meta_image')}} (1200*627) </label>
                    <div class="col-lg-8">


                        {!! single_image('meta_image' , media_file(old('meta_image',get_setting('meta_image'))) , get_setting('meta_image') ,'image',['width'=>1200,'height'=>627] ) !!}
                        <br>
                        @error('meta_image') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
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
    <script type="module">
        import Tags from "https://cdn.jsdelivr.net/gh/lekoala/bootstrap5-tags@master/tags.js";

        Tags.init("select", {
            badgeStyle: 'primary',
            textStyle: 'dark'
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js" type="module"></script>


    <script src="{{asset('backend/plugins/global/plugins.bundle.js')}}"></script>
    <script
            type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"
    ></script>
    {{--    <script>--}}

    {{--        // $('#keywords').tagsinput({--}}
    {{--        //     typeaheadjs: {--}}
    {{--        //         name: 'citynames',--}}
    {{--        //         displayKey: 'name',--}}
    {{--        //         valueKey: 'name',--}}
    {{--        //     }--}}
    {{--        // });--}}

    {{--    </script>--}}
@endsection
