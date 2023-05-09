@extends('backend.layout.app')
@section('title',trans('backend.menu.manufacturers').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.manufacturers.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.manufacturer.create_new_manufacturer')}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.manufacturers.index')}}" class="btn btn-info"><i
                                    class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                    </div>
                </div>
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
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label @if($key == 0 ) required @endif"
                                                   for="title_{{$item->code}}">{{trans('backend.manufacturer.title')}}</label>
                                            <input type="text" class="form-control" id="title_{{$item->code}}"
                                                   @if($key == 0 ) required @endif
                                                   name="title_{{$item->code}}" value="{{old('title_'.$item->code)}}">
                                            @error('title_'.$item->code)<b class="text-danger"> <i
                                                        class="las la-exclamation-triangle"></i> {{$message}}
                                            </b>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row  ">
                                    <div class="col form-group ">
                                        <div class="form-group">
                                            <label class="form-label @if($key == 0 ) required @endif"
                                                   for="description_{{$item->code}}">{{trans('backend.manufacturer.description')}}</label>
                                            <textarea type="text" class="form-control" @if($key == 0 ) required
                                                      @endif id="description_{{$item->code}}"
                                                      name="description_{{$item->code}}"
                                            > {{old('description_'.$item->code)}}</textarea>
                                            @error('description_'.$item->code)<b class="text-danger"> <i
                                                        class="las la-exclamation-triangle"></i> {{$message}}
                                            </b>@enderror

                                        </div>
                                    </div>
                                </div>
                                {!! form_seo($item->code , $key ,old('meta_title_'.$item->code),old('meta_description_'.$item->code)) !!}

                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="card flex-row-fluid mb-2 mt-5  ">
                <div class="card-body">
                    <div class="row mb-10">

                        <div class="col form-group ">
                            <div class="form-group  align-items-center">


                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('software') == 1 ) checked
                                           @endif type="checkbox" value="1"
                                           name="software" id="software"/>
                                    <label class="form-check-label" for="software">
                                        {{trans('backend.manufacturer.software')}}
                                    </label>
                                </div>

                            </div>
                        </div>
                        <div class="col form-group ">

                            <div class="form-group  align-items-center">
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('token') == 1 ) checked
                                           @endif type="checkbox" value="1"
                                           name="token" id="token"/>
                                    <label class="form-check-label" for="token">
                                        {{trans('backend.manufacturer.token')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col form-group ">

                            <div class="form-group  align-items-center">
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status') == 1 ) checked
                                           @endif type="checkbox" value="1"
                                           name="status" id="status"/>
                                    <label class="form-check-label" for="status">
                                        {{trans('backend.global.do_you_want_active')}}
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col  align-items-center">
                            <div class="col form-group ">
                                <label class="form-label" for="image">{{trans('backend.manufacturer.image')}}</label>
                                <br>
                                {!! single_image('image' , media_file(old('image')) , old('image')  ) !!}
                                <br>
                                @error('image')<b class="text-danger"> <i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
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
    {!! editor_script() !!}

    @foreach(get_languages() as $key=> $item)
        <script>
            CKEDITOR.replace(document.querySelector('#description_{{$item->code}}'));

        </script>
    @endforeach

    @include('backend.shared.seo.script')

@endsection
