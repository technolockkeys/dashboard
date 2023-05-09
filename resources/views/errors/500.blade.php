@extends('backend.layout.login')

@section('title', __('System Error'))
@section('content')

    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="d-flex flex-column flex-column-fluid text-center p-10 py-lg-15">
                <a href="{{route('backend.home')}}" class="mb-10 pt-lg-10">
                    <img alt="Logo" src="{{media_file(get_setting('system_logo_white'))}}" class="h-40px mb-5">
                </a>
                <div class="pt-lg-10 mb-10">
                    <h1 class="fw-bolder fs-2qx text-gray-800 mb-10">{{trans('backend.global.system_error')}}</h1>
                    <div class="fw-bold fs-3 text-muted mb-15">{{trans('backend.global.went_wrong')}}
                        <br>{{trans('backend.global.try_again')}}
                    </div>
                    <div class="text-center">
                        <a href="javascript:history.back()" class="btn btn-primary">{{trans('backend.global.get_back')}}</a>
                    </div>
                </div>

                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px"
                     style="background-image: url({{asset('backend/media/illustrations/sketchy-1/17.png')}}"></div>
            </div>
        </div>
    </div>
@endsection()

