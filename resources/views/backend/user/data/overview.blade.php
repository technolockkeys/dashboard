<link href="{{asset("backend/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>

<div class="col">
    <form action="{{route('backend.users.update', $user->id)}}" id="edit_user" method="post"
          enctype="multipart/form-data">
        @csrf
        <div class="card flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.user.edit',['name'=> $user->name])}}</h3>

            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="mb-10">
                            <label for="name" class="required form-label">{{trans('backend.user.name')}}</label>
                            <input required autocomplete="off" type="text" class="form-control " id="name"
                                   name="name" value="{{old('name', $user->name)}}"
                                   placeholder="{{trans('backend.user.name')}}"/>
                            <b class="text-danger" id="name_error"> </b>
                        </div>

                    </div>

                    <div class="col-12 col-md-6">
                        <div class="  mb-10">
                            <label for="email" class="form-label required ">{{trans('backend.user.email')}}</label>
                            <input required autocomplete="off" type="email" class="form-control" id="email"
                                   name="email" value="{{old('email', $user->email)}}"
                                   placeholder="{{trans('backend.user.email')}}"/>
                            <b class="text-danger" id="email_error"> </b>

                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="mb-10">
                            <label for="phone" class="form-label required ">{{trans('backend.user.phone')}}</label><br>
                            <input required autocomplete="off" type="text" class="form-control w-100  " id="edit_phone"
                                   name="old_phone" value="{{old('phone', $user->phone)}}"
                                   placeholder="{{trans('backend.user.phone')}}"/>
                            @error('phone') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>

                    </div>

                    <div class="col-12 col-md-6">
                        <div class="mb-10">
                            <label for="type_of_business"
                                   class="form-label">{{trans('backend.user.type_of_business')}}</label><br>
                            <input autocomplete="off" type="text" class="form-control w-100  " id="type_of_business"
                                   name="type_of_business" value="{{old('type_of_business', $user->type_of_business)}}"
                                   placeholder="{{trans('backend.user.type_of_business')}}"/>
                            @error('type_of_business') <b class="text-danger"><i
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
                                   name="company_name" value="{{old('company_name', $user->company_name)}}"
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
                                   name="website_url" value="{{old('website_url', $user->website_url)}}"
                                   placeholder="{{trans('backend.user.website_url')}}"/>
                            @error('website_url') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                </div>

                <div class="row mb-10">
                    <div class="col-12 col-md-6">

                        <div class="form-group">
                            <label for="seller"
                                   class="form-label ">{{trans('backend.user.seller')}}</label>
                            <select class="form-control" id="seller" name="seller" data-control="select2"
                                    data-placeholder="Select an option">
                                <option selected value="{{null}}"></option>
                                @foreach($sellers as $seller)
                                    <option
                                        value="{{$seller->id}}" {{$user->seller_id == $seller->id? 'selected':''}}>{{$seller->name}}</option>
                                @endforeach
                            </select>
                            @error('seller') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="  mb-10">
                            <label for="password"
                                   class="form-label">{{trans('backend.user.password')}}</label>
                            <input autocomplete="off" type="password" class="form-control " id="password"
                                   name="password"
                                   placeholder="{{trans('backend.user.password')}}"/>
                            <b class="text-danger" id="password_error"> </b>

                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class=" mb-10">
                            <label for="password_confirmation"
                                   class="form-label">{{trans('backend.user.password_confirmation')}}</label>
                            <input autocomplete="off" type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   placeholder="{{trans('backend.user.password_confirmation')}}"/>
                            <b class="text-danger" id="password_confirmation_error"> </b>

                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <div class="col-12 col-sm-6  ">
                        <div class="form-group ">

                            <label
                                class=" form-label fw-bold m-4    mx-auto">{{trans('backend.profile.avatar')}}</label>

                            <div class=" mx-auto">
                                <div
                                    class="image-input image-input-outline @if($user->avatar==null) image-input-empty @endif"
                                    data-kt-image-input="true"
                                    style="background-image: url({{ asset('backend/media/avatars/blank.png')}})">
                                    <div class="image-input-wrapper text-center z-index-3 w-125px h-125px p-5"
                                         style="background-image:  url({{$user->avatar}})">
                                    </div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="avatar" value="{{old('avatar', $user->avatar)}}"
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
                                       @if(old('status', $user->status)== 1) checked
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

<script>

    $("#seller").select2({});
    var edit_phone = document.querySelector("#edit_phone");
    window.intlTelInput(edit_phone, {
        initialCountry: "tr",
        hiddenInput: 'phone',
        utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
    });
    // Static methods
    KTImageInput.getInstance = function(element) {
        if ( element !== null && KTUtil.data(element).has('image-input') ) {
            return KTUtil.data(element).get('image-input');
        } else {
            return null;
        }
    }

    // Create instances
    KTImageInput.createInstances = function(selector = '[data-kt-image-input]') {
        // Initialize Menus
        var elements = document.querySelectorAll(selector);

        if ( elements && elements.length > 0 ) {
            for (var i = 0, len = elements.length; i < len; i++) {
                new KTImageInput(elements[i]);
            }
        }
    }

    // Global initialization
    KTImageInput.init = function() {
        KTImageInput.createInstances();
    };

    // On document ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', KTImageInput.init);
    } else {
        KTImageInput.init();
    }

    // Webpack Support
    if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
        module.exports = KTImageInput;
    }

</script>

<!--end::Input group-->
<!--begin::Input group-->
{{--                    <div class="row mb-7">--}}
{{--                        <!--begin::Label-->--}}
{{--                        <label class="col-lg-4 fw-bold text-muted">Country--}}
{{--                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Country of origination" aria-label="Country of origination"></i></label>--}}
{{--                        <!--end::Label-->--}}
{{--                        <!--begin::Col-->--}}
{{--                        <div class="col-lg-8">--}}
{{--                            <span class="fw-bolder fs-6 text-gray-800">Germany</span>--}}
{{--                        </div>--}}
{{--                        <!--end::Col-->--}}
{{--                    </div>--}}
<!--end::Input group-->
</div>
