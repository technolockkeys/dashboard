@extends('backend.layout.app')
@section('content')
    <div class="col">
        <form action="{{route('backend.currencies.update_cur', $currency->id)}}" method="post"
              enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.currency.edit', ['name' => $currency->name])}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.currencies.index')}}" class="btn btn-info"><i
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
                                               class="required form-label">{{trans('backend.currency.name')}}</label>
                                        <input required autocomplete="off" type="text" class="form-control "
                                               id="name_{{$item->code}}" name="name_{{$item->code}}"
                                               value="{{old('name_'.$item->code,$currency->getTranslation('name', $item->code))}}"
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
                        <div class="  mb-10">
                            <label for="code" class="required form-label">{{trans('backend.currency.code')}}</label>
                            <input required autocomplete="off" type="text" class="form-control" id="code" name="code"
                                   value="{{old('code', $currency->code)}}"
                                   placeholder="{{trans('backend.currency.code')}}"/>
                            @error('code') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                    <div class="col">
                        <div class="  mb-10">
                            <label for="value" class="required form-label">{{trans('backend.currency.value')}}</label>
                            <input required autocomplete="off" type="number" min="0" step="0.000001" class="form-control"
                                   id="value" name="value" value="{{old('value', $currency->value)}}"
                                   placeholder="{{trans('backend.currency.value')}}"/>
                            @error('value') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                    <div class="col">
                        <div class="  mb-10">
                            <label for="symbol" class="required form-label">{{trans('backend.currency.symbol')}}</label>
                            <input required autocomplete="off" type="text" class="form-control" id="symbol"
                                   name="symbol" value="{{old('symbol', $currency->symbol)}}"
                                   placeholder="{{trans('backend.currency.symbol')}}"/>
                            @error('symbol') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                    <div class="col  align-items-center">
                        <div class="form-group  align-items-center">
                            <br>
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px"
                                       @if(old('status', $currency->status) == 1) checked @endif type="checkbox"
                                       value="1"
                                       name="status" id="status"/>
                                <label class="form-check-label" for="status">
                                    {{trans('backend.currency.status')}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
                </div>
            </div>
        </form>
    </div>
@endsection
