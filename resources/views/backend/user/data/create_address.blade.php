<form class="form fv-plugins-bootstrap5 fv-plugins-framework fire-address" action="{{route('backend.addresses.store')}}"
      >
@csrf
<!--begin::Modal header-->
    <div class="modal-header" id="modal_new_address_header">
        <!--begin::Modal title-->
        <h2>{{trans('backend.address.add_new_address')}}</h2>
        <!--end::Modal title-->
        <!--begin::Close-->
        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
            <span class="svg-icon svg-icon-1">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                      height="2" rx="1"
                                                                      transform="rotate(-45 6 17.3137)"
                                                                      fill="currentColor"></rect>
																<rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                                      transform="rotate(45 7.41422 6)"
                                                                      fill="currentColor"></rect>
															</svg>
														</span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Close-->
    </div>
    <input type="hidden" value="{{$user_id}}" name="user_id">
    <!--end::Modal header-->
    <!--begin::Modal body-->
    <div class="modal-body py-10 px-lg-17">
        <!--begin::Scroll-->
        <div class="scroll-y me-n7 pe-7" id="modal_new_address_scroll" data-kt-scroll="true"
             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
             data-kt-scroll-dependencies="#modal_new_address_header"
             data-kt-scroll-wrappers="#modal_new_address_scroll" data-kt-scroll-offset="300px"
             style="max-height: 619px;">
            <!--begin::Input group-->

            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <div class="form-group">
                    <label for="country"
                           class="form-label required">{{trans('backend.city.country_name')}}</label>
                    <select required class="form-control form-control-solid" id="country" name="country"
                            data-control="select2"
                            data-placeholder="Select an option">
                        <option selected value="{{null}}"></option>
                        @foreach($countries as $country)
                            <option value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                    <b class="text-danger" id="country_error"> </b>

                </div>
                <!--end::Select-->
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            <!--end::Input group-->

            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <div class="form-group">
                    <label for="state"
                           class="form-label required">{{trans('backend.user.state')}}</label>
                    <input type="text" class="form-control form-control-solid " name="state"  value="{{old('state')}}" id="state">
                                        <b class="text-danger" id="state_error"> </b>
                </div>
                <!--end::Select-->
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <div class="form-group">
                    <label for="city"
                           class="form-label required">{{trans('backend.user.city')}}</label>
                    <input type="text" class="form-control form-control-solid " name="city"  value="{{old('city')}}" id="city">
                                        <b class="text-danger" id="city_error"> </b>
                </div>
                <!--end::Select-->
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            <!--end::Input group-->
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <div class="form-group">
                    <label for="street"
                           class="form-label required">{{trans('backend.user.street')}}</label>
                    <input type="text" class="form-control form-control-solid " name="street"  value="{{old('street')}}" id="street">
                                        <b class="text-danger" id="street_error"> </b>
                </div>
                <!--end::Select-->
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <label class="required fs-5 fw-bold mb-2">{{trans('backend.user.address')}}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input required class="form-control form-control-solid" placeholder="" name="address">
                <!--end::Input-->
                <b class="text-danger" id="address_error"> </b>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row g-9 mb-5">
                <!--begin::Col-->
                <div class="col-12 col-md-6">
                    <div class="mb-10">
                        <label for="phone" class="form-label">{{trans('backend.user.phone')}}</label><br>
                        <input autocomplete="off" type="text" class="form-control form-control-solid w-100 " id="create_phone"
                               name="old_phone" value="{{old('phone')}}"
                               placeholder="{{trans('backend.user.phone')}}"/>
                        @error('phone') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                    </div>

                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6 fv-row fv-plugins-icon-container">
                    <!--begin::Label-->
                    <label class="fs-5 fw-bold mb-2">{{trans('backend.address.postal_code')}}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input class="form-control form-control-solid" placeholder="" name="postal_code">
                    <!--end::Input-->
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-5">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack">
                    <!--begin::Label-->
                    <div class="me-5">
                        <!--begin::Label-->
                        <label class="fs-5 fw-bold">{{trans('backend.address.add_as_default_address')}}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div class="fs-7 fw-bold text-muted">{{trans('backend.address.default_address')}}
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Label-->
                    <!--begin::Switch-->
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <!--begin::Input-->
                        <input class="form-check-input" name="is_default" type="checkbox" value="1"
                               @if(old('is_default')==1)checked @endif>
                        <!--end::Label-->
                    </label>
                    <!--end::Switch-->
                </div>
                <!--begin::Wrapper-->
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Scroll-->
    </div>
    <!--end::Modal body-->
    <!--begin::Modal footer-->
    <div class="modal-footer flex-center">
        <!--begin::Button-->
        <!--end::Button-->
        <!--begin::Button-->
        <button type="submit" class="btn btn-primary">
            <span class="indicator-label">{{trans('backend.global.save')}}</span>
        </button>
        <!--end::Button-->
    </div>
    <!--end::Modal footer-->
    <div></div>
</form>
<script src="{{asset("backend/plugins/custom/intltell/js/intlTelInput.js")}}"></script>

<script>

    var create_phone = document.querySelector("#create_phone");
    window.intlTelInput(create_phone, {
        initialCountry: "tr",
        hiddenInput: 'phone',
        utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
    });
</script>
