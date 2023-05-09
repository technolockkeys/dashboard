@extends('backend.layout.app')
@section('title',trans('backend.menu.cities').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.cities.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.city.create_new_city')}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.cities.index')}}" class="btn btn-info"><i
                                    class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-10">
                                <label for="name" class="required form-label">{{trans('backend.user.name')}}</label>
                                <input required autocomplete="off" type="text" class="form-control " id="name"
                                       name="name" value="{{old('name')}}"
                                       placeholder="{{trans('backend.user.name')}}"/>
                                @error('name') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="country"
                                       class="required form-label">{{trans('backend.city.country_name')}}</label>
                                <select class="form-control" id="country" name="country" data-control="select2"
                                        data-placeholder="Select an option">
                                    <option selected value="{{null}}"></option>
                                    @foreach($countries as $country)

                                        <option value="{{$country->id}}" {{old('country')== $country->id? "selected":""}}>{{$country->name}}</option>
                                    @endforeach
                                </select>
                                @error('country') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                    </div>
                    <div class="row flex-center">
                        <div class="col">
                            <div class=" mb-10">
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status') == 1) checked @endif type="checkbox" value="1"
                                           name="status" id="status"/>
                                    <label class="form-check-label" for="status">
                                        {{trans('backend.city.status')}}
                                    </label>
                                </div>
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
