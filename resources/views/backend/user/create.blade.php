@extends('backend.layout.app')
@section('title',trans('backend.menu.users').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    <link rel="stylesheet" href="{{asset('backend/plugins/custom/intltell/css/intlTelInput.css')}}">
    <style>
        .iti {
            width: 100% !important;

        }
    </style>
@endsection
@section('content')
    <div class="col">
        <form action="{{route('backend.users.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.user.create_new_user')}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.users.index')}}" class="btn btn-info"><i
                                    class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-12 col-md-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">{{trans('backend.user.name')}}</label>
                                <input required autocomplete="off" type="text" class="form-control " id="name"
                                       name="name" value="{{old('name')}}"
                                       placeholder="{{trans('backend.user.name')}}"/>
                                @error('name') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>

                        <div class="col-12 col-md-6">
                            <div class="  mb-10">
                                <label for="email" class="required form-label">{{trans('backend.user.email')}}</label>
                                <input required autocomplete="off" type="email" class="form-control" id="email"
                                       name="email" value="{{old('email')}}"
                                       placeholder="{{trans('backend.user.email')}}"/>
                                @error('email') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12 col-md-6">
                            <div class="mb-10">
                                <label for="phone" class="form-label required">{{trans('backend.user.phone')}}</label><br>
                                <input required autocomplete="off" type="text" class="form-control w-100  " id="phone"
                                       name="phone" value="{{old('phone')}}"
                                       placeholder="{{trans('backend.user.phone')}}"/>
                                @error('phone') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>

                        <div class="col-12 col-md-6">
                            <div class="  mb-10">
                                <label for="postal_code"
                                       class="form-label  ">{{trans('backend.user.postal_code')}}</label>
                                <input   autocomplete="off" type="text" class="form-control" id="postal_code"
                                       name="postal_code" value="{{old('postal_code')}}"
                                       placeholder="{{trans('backend.user.postal_code')}}"/>
                                @error('postal_code') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12 col-md-6">
                            <div class="mb-10">
                                <label for="company_name"
                                       class="form-label">{{trans('backend.user.company_name')}}</label><br>
                                <input autocomplete="off" type="text" class="form-control w-100  " id="company_name"
                                       name="company_name" value="{{old('company_name')}}"
                                       placeholder="{{trans('backend.user.company_name')}}"/>
                                @error('company_name') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>

                        <div class="col-12 col-md-6">
                            <div class="  mb-10">
                                <label for="website_url"
                                       class="form-label">{{trans('backend.user.website_url')}}</label>
                                <input autocomplete="off" type="text" class="form-control" id="website_url"
                                       name="website_url" value="{{old('website_url')}}"
                                       placeholder="{{trans('backend.user.website_url')}}"/>
                                @error('website_url') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12 col-md-6">
                            <div class="mb-10">
                                <label for="type_of_business"
                                       class="form-label">{{trans('backend.user.type_of_business')}}</label><br>
                                <input autocomplete="off" type="text" class="form-control w-100  " id="type_of_business"
                                       name="type_of_business" value="{{old('type_of_business')}}"
                                       placeholder="{{trans('backend.user.type_of_business')}}"/>
                                @error('type_of_business') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="seller"
                                       class="form-label ">{{trans('backend.user.seller')}}</label>
                                <select class="form-control" id="seller" name="seller" data-control="select2"
                                        data-placeholder="Select an option">
                                    <option selected value="{{null}}"></option>
                                    @foreach($sellers as $seller)
                                        <option value="{{$seller->id}}"{{old('seller')== $seller->id? 'selected': ''}}>{{$seller->name}}</option>
                                    @endforeach
                                </select>
                                @error('seller') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>

                    </div>
                    <div class="row mb-10">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="country"
                                       class="form-label required">{{trans('backend.city.country_name')}}</label>
                                <select required class="form-control" id="country" name="country" data-control="select2"
                                        data-placeholder="Select an option">
                                    <option selected value="{{null}}"></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                                @error('country') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class=" ">
                                <label for="state"
                                       class="form-label  ">{{trans('backend.user.state')}}</label>
                                <input   autocomplete="off" type="text" class="form-control" id="state"
                                       name="state" value="{{old('state')}}"
                                       placeholder="{{trans('backend.user.state')}}"/>
                                @error('state') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>

                    </div>
                    <div class="row mb-6">

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="city"
                                       class="form-label required">{{trans('backend.user.city')}}</label>
                                <input type="text" class="form-control  " name="city"  value="{{old('city')}}" id="city">
                                <b class="text-danger" id="city_error"> </b>
                                @error('city') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class=" ">
                                <label for="street"
                                       class="form-label required">{{trans('backend.user.street')}}</label>
                                <input autocomplete="off" type="text" class="form-control" id="street"
                                       name="street" value="{{old('street')}}"
                                       placeholder="{{trans('backend.user.street')}}"/>
                                @error('street') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>

                    </div>

                    <div class="row mb-10">

                        <div class="col-12">
                            <div class="form-group">
                                <label for="address"
                                       class="form-label">{{trans('backend.user.address')}}</label>

                                <textarea name="address" id="address" class="form-control"
                                          rows="5">{{old('address')}}</textarea>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @php
                         $pass=    substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 8);    @endphp
                        <div class="col-12 col-md-6">
                            <div class="  mb-10">
                                <label for="password"
                                       class="form-label required">{{trans('backend.user.password')}}</label>
                                <input required autocomplete="off" type="text" class="form-control " id="password"
                                       name="password" value="{{old('password',$pass)}}"
                                       placeholder="{{trans('backend.user.password')}}"/>
                                @error('password') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class=" mb-10">
                                <label for="password_confirmation"
                                       class="form-label required">{{trans('backend.user.password_confirmation')}}</label>
                                <input required autocomplete="off" type="text" class="form-control"
                                       id="password_confirmation" name="password_confirmation"  value="{{old('password_confirmation',$pass)}}"
                                       placeholder="{{trans('backend.user.password_confirmation')}}"/>
                                @error('password_confirmation') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-12 col-sm-6  ">
                            <div class="form-group ">

                                <label class=" form-label fw-bold m-4   mx-auto">{{trans('backend.profile.avatar')}}</label>

                                <div class=" mx-auto">
                                    <div class="image-input image-input-outline image-input-empty"
                                         data-kt-image-input="true"
                                         style="background-image: url({{ old('avatar',asset('backend/media/avatars/blank.png'))}})">
                                        <div class="image-input-wrapper text-center z-index-3 w-125px h-125px p-5"
                                             style="background-image: none">
                                        </div>
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow "
                                               data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
                                               data-bs-original-title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="avatar" value="{{old('avatar')}}"
                                                   accept=".png, .jpg, .jpeg">
                                            <input type="hidden" name="avatar_remove">
                                        </label>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                              data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title=""
                                              data-bs-original-title="Cancel avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                              data-kt-image-input-action="remove" data-bs-toggle="tooltip" title=""
                                              data-bs-original-title="Remove avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status')== 1) checked
                                           @endif type="checkbox" value="1"
                                           name="status" id="status"/>
                                    <label class="form-check-label" for="status">
                                        {{trans('backend.user.status')}}
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

@section('script')
    <script src="{{asset("backend/plugins/custom/intltell/js/intlTelInput.js")}}"></script>

    <script>

        $(document).ready(function () {
            var country = $("#country").val();
            @if(!empty(old('country')))
            $("#country").val("{{old('country')}}").change();
            @endif

        })
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            initialCountry: "tr",

            utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
        });
    </script>
@endsection
