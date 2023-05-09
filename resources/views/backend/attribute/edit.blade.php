@extends('backend.layout.app')
@section('title',trans('backend.menu.attributes').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($attribute, array('method' => 'PATCH', 'route' => array('backend.attributes.update', $attribute->id))) }}
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.attribute.edit',['name' => $attribute->name])}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.attributes.index')}}" class="btn btn-info"><i
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
                                            <label class="form-label"
                                                   for="name_{{$language->code}}">{{trans('backend.attribute.name')}}</label>
                                            <input type="text" class="form-control" id="name_{{$language->code}}"
                                                   name="name_{{$language->code}}" value="{{old('name_'.$language->code,$attribute->getTranslation( 'name',$language->code) )}}">
                                            @error('name_'.$language->code)<b class="text-danger"> <i
                                                        class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col form-group ">
                                        <label class="form-label" for="image">{{trans('backend.attribute.image')}}</label>
                                        <br>
                                        {!! single_image('image_'.$language->code , media_file(old('image_'.$language->code, $attribute->getTranslation('image', $language->code))) , old('image_'.$language->code, $attribute->getTranslation('image', $language->code))  ) !!}
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
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status', $attribute->status)== 1) checked @endif type="checkbox" value="1"
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
        {{form::close()}}
    </div>
@endsection
@section('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>

@endsection
