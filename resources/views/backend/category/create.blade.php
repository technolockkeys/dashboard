@extends('backend.layout.app')
@section('title',trans('backend.menu.categories').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.categories.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.category.create_new_category')}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.categories.index')}}" class="btn btn-info"><i
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
                                                   for="name_{{$item->code}}">{{trans('backend.category.name')}}</label>
                                            <input type="text" class="form-control" id="name_{{$item->code}}" @if($key == 0 ) required @endif
                                                   name="name_{{$item->code}}" value="{{old('name_'.$item->code)}}">
                                            @error('name_'.$item->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row  ">
                                    <div class="col form-group ">
                                        <div class="form-group">
                                            <label class="form-label @if($key == 0 ) required @endif"
                                                   for="description_{{$item->code}}">{{trans('backend.category.description')}}</label>
                                            <textarea type="text" class="form-control" @if($key == 0 ) required @endif id="description_{{$item->code}}"
                                                      name="description_{{$item->code}}"
                                            > {{old('description_'.$item->code)}}</textarea>
                                            @error('description_'.$item->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror

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
                    <div class="row">
                        <div class="col form-group ">
                            <label class="form-label required"
                                   for="slug">{{trans('backend.category.slug')}}</label>
                            <input required type="text" class="form-control" id="slug"
                                   name="slug" value="{{old('slug')}}">
                            <b class="text-danger" id="message_slug">
                                @error('slug') <i class="las la-exclamation-triangle"></i> {{$message}} @enderror
                            </b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col form-group ">
                            <label class="form-label"
                                   for="parent">{{trans('backend.category.parent')}}</label>
                            <select class="form-control parent" data-placeholder="{{trans('backend.category.parent')}}" data-control="select2" name="parent" id="parent">
                                <option selected value="0">{{trans('backend.category.parent')}}</option>
                                {!! \App\Models\Category::select2(old('parent') ,0,0) !!}
                            </select>
                            @error('parent')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror

                        </div>
                        <div class="col form-group " id="category_type">
                            <label class="form-label" for="type">{{trans('backend.category.type')}}</label>
                            <select class="form-control  " data-control="select2" name="type" id="type">

                                <option
                                    value="physical" {{old('type')== 'physical'? "selected":""}}>{{trans('backend.category.physical')}}</option>
                                <option
                                    value="software" {{old('type')== 'software'? "selected":""}}>{{trans('backend.category.software')}}</option>

                            </select>
                            @error('type')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror

                        </div>
                    </div>
                    <div class="row">
                        <div class="col form-group ">
                            <label class="form-label" for="banner">{{trans('backend.category.form_banner')}}</label>
                            <br>
                            {!! single_image('banner' , media_file(old('banner')) , old('banner')  ,'image',[  'width'=>1200 , 'height'=>300] ) !!}
                            <br>
                            @error('banner')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                        </div>
                        <div class="col form-group ">
                            <label class="form-label" for="icon">{{trans('backend.category.form_icon')}}</label>
                            <br>
                            {!! single_image('icon' , media_file(old('icon')) , old('icon')  ) !!}
                            <br>
                            @error('icon')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
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
    {!! script_check_slug(route('backend.categories.check.slug'),'slug' ,'name_en') !!}

    <script>
        $(document).on('change','#parent', function(){
            var parent = $("#parent").val();

            if(parent === '0' || parent == null ){
                $('#category_type').removeClass('d-none')
            }else{
                $('#category_type').addClass('d-none')
            }

            $("#city").children().remove()
        });

        $(document).ready(function (){
            var parent = '{{old('parent')}}'
            if(parent !== null ){
                $('#parent').val(parent).change();
            }
        });
    </script>
    @include('backend.shared.seo.script')

@endsection
