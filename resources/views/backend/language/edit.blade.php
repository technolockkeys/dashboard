@extends('backend.layout.app')
@section('title',trans('backend.menu.languages').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($language, array('method' => 'PUT', 'route' => array('backend.languages.update', $language->id))) }}
        @csrf
        <div class="card flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.language.edit')}}: {{$language->language}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.languages.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row flex-center">

                    <div class="col">
                        <div class="mb-10">
                            <label for="language"
                                   class="required form-label">{{trans('backend.language.language')}}</label>
                            <input required autocomplete="off" type="text" class="form-control " id="language"
                                   name="language" value="{{old('language', $language->language)}}"
                            />
                            @error('language') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>

                    </div>

                    <div class="col">
                        <div class=" mb-10">
                            <label for="code" class="required form-label">{{trans('backend.language.code')}}</label>

                            <select required name="code" id="code" class="form-control" data-control="select2">
                                @foreach($codes as $code)
                                    <option @if(old('code', $language->code) == strtolower($code) )  selected @endif value="{{strtolower($code)}}">{{strtolower($code)}}</option>
                                @endforeach
                            </select>
                            @error('code') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                </div>
                <div class="row mb-10 flex">

                    <div class="col-6">
                        <div class="form-group">
                            <label for="display_type"
                                   class="required form-label">{{trans('backend.language.display_type')}}</label>
                            <select class="form-control" id="display_type" name="display_type"
                                    data-control="select2"
                                    data-placeholder="Select an option">
                                <option selected value="{{null}}"></option>


                                @foreach($display_types as $type)
                                    <option {{ (old('display_type', $language->display_type )== $type )?  'selected':""  }} value="{{$type}} ">{{$type}}</option>
                                @endforeach
                            </select>
                            @error('display_type') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                    <div class="col">
                        <div class="row mb-6">
                            <label for="flag"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.language.flag')}} </label>
                            <div class="col-lg-8">


                                {!! single_image('flag' , media_file(old('flag', $language->flag)), old('flag',$language->flag) ) !!}
                                <br>
                                @error('flag') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
            </div>
            {{ Form::close() }}
        </div>

@endsection
