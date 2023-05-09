@extends('backend.layout.app')
@section('title',trans('backend.menu.attributes').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.attributes.sub-attributes.store',$attribute->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.attribute.add_attribute_to')}}: {{$attribute->name}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.attributes.sub-attributes.index',['attribute_id'=> $attribute->id])}}" class="btn btn-info"><i
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
                                            <label class="form-label required"
                                                   for="value_{{$language->code}}">{{trans('backend.attribute.value')}}</label>
                                            <input type="text" class="form-control" id="value_{{$language->code}}"
                                                   name="value_{{$language->code}}" value="{{old('value_'.$language->code)}}">
                                            @error('value_'.$language->code)<b class="text-danger"> <i
                                                        class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
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
                    <div class="row">
                        <div class="col form-group ">
                            <label class="form-label" for="image">{{trans('backend.attribute.image')}}</label>
                            <br>
                            {!! single_image('image' , media_file(old('image')) , old('image')  ) !!}
                            <br>
                            @error('image')<b class="text-danger"> <i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status') == 1) checked @endif type="checkbox" value="1"
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
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>

    @foreach(get_languages() as $key=> $item)
        <script>
            ClassicEditor.create(document.querySelector('#description_{{$item->code}}'));

        </script>
    @endforeach
@endsection
