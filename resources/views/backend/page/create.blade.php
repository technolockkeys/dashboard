@extends('backend.layout.app')
@section('title',trans('backend.menu.pages').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.pages.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.page.create_new_page')}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.pages.index')}}" class="btn btn-info"><i
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
                                <div class="row mb-6">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label @if($key == 0 ) required @endif"
                                                   for="title_{{$item->code}}">{{trans('backend.page.title')}}</label>
                                            <input type="text" class="form-control" id="title_{{$item->code}}" @if($key == 0 ) required @endif
                                                   name="title_{{$item->code}}" value="{{old('title_'.$item->code)}}">
                                            @error('title_'.$item->code)<b class="text-danger"> <i
                                                        class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col form-group ">
                                        <div class="form-group">
                                            <label class="form-label "
                                                   for="description_{{$item->code}}">{{trans('backend.page.description')}}</label>
                                            <textarea type="text" class="form-control" id="description_{{$item->code}}"
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
                    <div class="row mb-10">
                        <div class=" form-group flex flex-center ">
                            <label class="form-label required"
                                   for="slug">{{trans('backend.page.slug')}}</label>
                            <input type="text" class="form-control" id="slug" required
                                   name="slug" value="{{old('slug')}}">
                         <b class="text-danger" id="message_slug">
                             @error('slug') <i class="las la-exclamation-triangle"></i> {{$message}}@enderror
                         </b>
                        </div>
                    </div>

                    <div class="col">
                        <div class="row mb-6">
                            <label for="meta_image"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.page.meta_image')}}   (1200*627) </label>
                            <div class="col-lg-8">


                                {!! single_image('meta_image' , media_file(old('meta_image')), old('meta_image','')  ,'image',['width'=>1200,'height'=>627]) !!}
                                <br>
                                @error('meta_image') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" checked type="checkbox" value="1"
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
            CKEDITOR.replace(
                document.querySelector('#description_{{$item->code}}'))

        </script>
    @endforeach

    {!! script_check_slug(route('backend.pages.check.slug'),'slug','title_en') !!}
    @include('backend.shared.seo.script')

@endsection
