@extends('backend.layout.app')
@section('title',trans('backend.menu.sellers').' | '.get_translatable_setting('system_name', app()->getLocale()))


@section('style')
    <link rel="stylesheet" href="{{asset('backend/plugins/custom/intltell/css/intlTelInput.css'),}}">
    <style>
        .iti {
            width: 100% !important;

        }
    </style>
@endsection
@section('content')
    <div class="col">
        {{ Form::model($seller, array('method' => 'PATCH', 'route' => array('backend.sellers.update', $seller->id, ),'enctype' => "multipart/form-data")) }}
        @csrf
        <div class="card flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.seller.edit',['name' => $seller->name])}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.sellers.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="mb-10">
                            <label for="name" class="required form-label">{{trans('backend.user.name')}}</label>
                            <input required autocomplete="off" type="text" class="form-control " id="name"
                                   name="name" value="{{old('name', $seller->name)}}"
                                   placeholder="{{trans('backend.user.name')}}"/>
                            @error('name') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>

                    </div>

                    <div class="col-12 col-md-6">
                        <div class="  mb-10">
                            <label for="email" class="required form-label">{{trans('backend.user.email')}}</label>
                            <input required autocomplete="off" type="email" class="form-control" id="email"
                                   name="email" value="{{old('email', $seller->email)}}"
                                   placeholder="{{trans('backend.user.email')}}"/>
                            @error('email') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="  mb-10">
                            <label for="phone" class="form-label">{{trans('backend.user.phone')}}</label>
                            <input autocomplete="off" type="text" class="form-control w-100  " id="phone"
                                   name="phone" value="{{old('phone', $seller->phone)}}"
                                   placeholder="{{trans('backend.user.phone')}}"/>
                            @error('phone') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="  mb-10">
                            <label for="email" class="form-label">{{trans('backend.user.whatsapp_number')}}</label>
                            <input autocomplete="off" type="text" class="form-control" id="whatsapp_number"
                                   name="whatsapp_number" value="{{old('whatsapp_number', $seller->whatsapp_number)}}"
                                   placeholder="{{trans('backend.user.whatsapp_number')}}"/>
                            @error('whatsapp_number') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="  mb-10">
                            <label for="facebook" class="form-label">{{trans('backend.user.facebook')}}</label>
                            <input autocomplete="off" type="text" class="form-control w-100  " id="facebook"
                                   name="facebook" value="{{old('facebook', $seller->facebook)}}"
                                   placeholder="{{trans('backend.user.facebook')}}"/>
                            @error('facebook') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="  mb-10">
                            <label for="skype" class="form-label">{{trans('backend.user.skype')}}</label>
                            <input autocomplete="off" type="text" class="form-control" id="skype"
                                   name="skype" value="{{old('skype', $seller->skype)}}"
                                   placeholder="{{trans('backend.user.skype')}}"/>
                            @error('skype') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12 col-md-6" id="seller_manager_wrapper">
                        <div class="  mb-10">
                            <label for="seller_manager"
                                   class="form-label">{{trans('backend.seller.seller_manager')}}</label>
                            <select class="form-control" id="seller_manager" name="seller_manger" data-control="select2"
                                    data-placeholder="Select an option">
                                <option value="-1">{{trans('backend.global.select_an_option')}}</option>
                                @foreach($sellers as $seller_item)
                                    <option
                                            value="{{$seller_item->id}}" {{old('seller_manager', $seller->seller_manger) == $seller_item->id? 'selected':''}}>{{$seller_item->name}}</option>
                                @endforeach
                            </select>
                            @error('seller_manager') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class=" mb-10">
                            <label for="seller_product_rate"
                                   class=" form-label">{{trans('backend.seller.seller_product_rate')}}</label>

                            <div class="mb-0 col-12">
                                <input class="form-control form-control"
                                       id="seller_product_rate" name="seller_product_rate" type="number" step="0.001"
                                       min="0" max="100"
                                       value="{{old('seller_product_rate', $seller->seller_product_rate)}}"/>
                                @error('seller_product_rate') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="  mb-10">
                            <label for="password"
                                   class="form-label ">{{trans('backend.user.password')}}</label>
                            <input autocomplete="off" type="password" class="form-control " id="password"
                                   name="password"
                                   placeholder="{{trans('backend.user.password')}}"/>
                            @error('password') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class=" mb-10">
                            <label for="password_confirmation"
                                   class="form-label ">{{trans('backend.user.password_confirmation')}}</label>
                            <input autocomplete="off" type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   placeholder="{{trans('backend.user.password_confirmation')}}"/>
                            @error('password_confirmation') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class=" mb-10">
                            <label for="role"
                                   class="form-label ">{{trans('backend.seller.role')}}</label>
                            <select name="role" id="role" class="form-control" data-control="select2">
                                @foreach($roles as $item)
                                    <option {{ (old('role')== $item->id ) ||  $seller->hasRole($item->name) ?  'selected' : ""  }} value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('password_confirmation') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12 col-sm-6  ">
                        <div class="form-group ">

                            <label class=" form-label fw-bold m-4 mx-auto">{{trans('backend.profile.avatar')}}</label>

                            <div class=" mx-auto">
                                <div class="image-input image-input-outline image-input-empty"
                                     data-kt-image-input="true"
                                     style="background-image: url({{ old('avatar',$seller->avatar)??asset('backend/media/avatars/blank.png')}})">
                                    <div class="image-input-wrapper text-center z-index-3 w-125px h-125px p-5"
                                         style="background-image: none">
                                    </div>
                                    <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow "
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="avatar" value="{{old('avatar', $seller->avatar)}}"
                                               accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="avatar_remove">
                                    </label>
                                    <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Cancel avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
                                    <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
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
                                <input class="form-check-input h-20px w-30px"
                                       @if(old('status', $seller->status)== 1) checked
                                       @endif type="checkbox" value="1"
                                       name="status" id="status"/>
                                <label class="form-check-label" for="status">
                                    {{trans('backend.user.status')}}
                                </label>
                            </div>
                            <br>
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px"
                                       @if(old('manager', $seller->is_manager)== 1) checked
                                       @endif type="checkbox" value="1" onchange="disable_manager_selection() "
                                       name="manager" id="manager"/>
                                <label class="form-check-label" for="manager">
                                    {{trans('backend.seller.manager')}}
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
        {{Form::close()}}
    </div>
@endsection

@section('script')
    <script src="{{asset("backend/plugins/custom/intltell/js/intlTelInput.js")}}"></script>

    <script>
        const disable_manager_selection = function () {

            var seller_manager = $('#seller_manager');
            var disabled = $('#manager').is(":checked");
            if (disabled) {
                seller_manager.attr('disabled', disabled);
                $("#seller_manager_wrapper").addClass('d-none');
            } else {
                seller_manager.attr('disabled', disabled);
                $("#seller_manager_wrapper").removeClass('d-none')
            }
        }
        disable_manager_selection();

        var phone = document.querySelector("#phone");
        window.intlTelInput(phone, {
            initialCountry: "tr",

            utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
        });

        var whatsapp_number = document.querySelector("#whatsapp_number");
        window.intlTelInput(whatsapp_number, {
            initialCountry: "tr",

            utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
        });
    </script>
@endsection
