@extends('backend.layout.app')
@section('title',trans('backend.menu.setting').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 aria-expanded="true"                 >
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{trans('backend.setting.default_images')}}</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form method="post" action="{{route('backend.setting.default_images.update')}}">
                    @csrf
                    @foreach($types as $type)
                        <div class="card-body border-top p-9">

                            <div class="row mb-6">
                                <label for="system_logo_white"
                                       class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.setting.'.$type.'_default_image')}} </label>
                                <div class="col-lg-8">


                                    {!! single_image($type.'_default_image' , media_file(old($type.'_default_image',get_setting($type.'_default_image'))) , old($type.'_default_image',get_setting($type.'_default_image')) ) !!}
                                    <br>
                                    @error('product_default_image') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection
