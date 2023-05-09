@extends('backend.layout.app')
@section('title',trans('backend.menu.colors').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($color, array('method' => 'PUT', 'route' => array('backend.colors.update', $color->id))) }}
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.color.edit')}}: {{$color->name}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.colors.index')}}" class="btn btn-info"><i
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

                                    <div class="mb-10">
                                        <label for="name_{{$item->code}}"
                                               class="@if($key == 0 ) required @endif    form-label">{{trans('backend.color.color_name')}}</label>
                                        <input @if($key == 0 ) required @endif    autocomplete="off" type="text" class="form-control "
                                               id="name_{{$item->code}}" name="name_{{$item->code}}"
                                               value="{{old('name_'.$item->code,$color->getTranslation('name', $item->code))}}"
                                               placeholder="{{trans('backend.color.color_name')}}"/>
                                        @error('name_'.$item->code) <b class="text-danger"><i
                                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        <div class="card flex-row-fluid mb-2  ">

            <div class="card-body">
                <div class="col">


                        <div class="col">
                            <div class="  mb-10">
                                <label for="code" class="required form-label">{{trans('backend.color.code')}}</label>
                                <input required autocomplete="off" type="text" class="form-control" id="code" name="code" value="{{old('code', isset($color)?$color->code:null)}}"
                                       placeholder="{{trans('backend.color.code')}}"/>
                                @error('code') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status') == 1)checked @endif type="checkbox" value="{{old('status', isset($color)?$color->status:null)}}"
                                           name="status" id="status"/>
                                    <label class="form-check-label" for="status">
                                        {{trans('backend.color.status')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
@endsection
