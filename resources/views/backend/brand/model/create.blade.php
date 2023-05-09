@extends('backend.layout.app')
@section('title',trans('backend.brand.model').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.brands.models.store',$brand->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.brand.add_model_to', ['name'=> $brand->make])}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.brands.models.index',['brand_id'=> $brand->id])}}"
                           class="btn btn-info"><i
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
                                    <div class="col-12 col-md-12 mb-10">
                                        <label for="model_{{$item->code}}"
                                               class="form-label required">{{trans('backend.brand.model')}}</label>
                                        <input class="form-control" id="model_{{$item->code}}"
                                               name="model_{{$item->code}}"
                                               value="{{old('model_'.$item->code)}}"
                                               placeholder="Type a brand name"/>

                                        @error('model') <b class="text-danger"><i
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
                    <div class="row flex-center mb-6">

                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status') == 1) checked
                                           @endif type="checkbox" value="1"
                                           name="status" id="status"/>
                                    <label class="form-check-label" for="status">
                                        {{trans('backend.global.do_you_want_active')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
