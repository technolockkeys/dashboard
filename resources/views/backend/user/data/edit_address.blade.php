{{--<!--begin::Form-->--}}
<link href="{{asset("backend/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>

<form class="form fv-plugins-bootstrap5 fv-plugins-framework fire-address" action="{{route('backend.addresses.update',$address->id)}}"  id="edit_address">
    @csrf
    <!--begin::Modal header-->
    <div class="modal-header" id="kt_modal_new_address_header">
        <!--begin::Modal title-->
        <h2>{{trans('backend.address.edit',['number'=> $address->id])}}</h2>
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
    <!--end::Modal header-->
    <!--begin::Modal body-->
    <div class="modal-body py-10 px-lg-17">
        <!--begin::Scroll-->
        <div class="scroll-y me-n7 pe-7" id="kt_modal_new_address_scroll" data-kt-scroll="true"
             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
             data-kt-scroll-dependencies="#kt_modal_new_address_header"
             data-kt-scroll-wrappers="#kt_modal_new_address_scroll" data-kt-scroll-offset="300px"
             style="max-height: 619px;">
            <!--begin::Notice-->
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <div class="form-group">
                    <label for="country"
                           class="form-label required">{{trans('backend.city.country_name')}}</label>
                    <select required class="form-control  form-control-solid" id="country" name="country"
                            data-control="select2"
                            data-placeholder="Select an option">
                        <option  value="{{null}}"></option>

                        @foreach($countries as $country)
                            <option value="{{$country->id}}" {{$address->country_id == $country->id? "selected":''}}>{{$country->name}}</option>
                        @endforeach
                    </select>
                    <b class="text-danger" id="country_error">  </b>

                </div>
                <!--end::Select-->
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <label class="required fs-5 fw-bold mb-2">{{trans('backend.user.state')}}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input required class="form-control form-control-solid" placeholder="" name="state"
                       value="{{old('state', $address->state)}}"
                >
                <!--end::Input-->
                <b class="text-danger" id="state_error">  </b>
            </div>
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <div class="form-group">
                    <label for="city"
                           class="form-label required">{{trans('backend.address.city')}}</label>
                    <input type="text" class="form-control form-control-solid " name="city"  value="{{old('city', $address->city)}}" id="city">
                    </select>
                    <b class="text-danger" id="city_error">  </b>

                </div>
                <!--end::Select-->
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            <!--end::Input group-->

            <!--end::Input group-->
<!--begin::Input group-->
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <label class="required fs-5 fw-bold mb-2">{{trans('backend.user.street')}}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input required class="form-control form-control-solid" placeholder="" name="street"
                    value="{{old('street', $address->street)}}"
                >
                <!--end::Input-->
                <b class="text-danger" id="street_error">  </b>
            </div>
            <!--end::Input group-->
<!--begin::Input group-->
            <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                <!--begin::Label-->
                <label class="required fs-5 fw-bold mb-2">{{trans('address line')}}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input required class="form-control form-control-solid" placeholder="" name="address"
                    value="{{old('address', $address->address)}}"
                >
                <!--end::Input-->
                <b class="text-danger" id="address_error">  </b>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row g-9 mb-5">
                <!--begin::Col-->
                <!--end::Col-->
                <div class="col-12 col-md-6">
                    <div class="mb-10">
                        <label for="phone" class="form-label">{{trans('backend.user.phone')}}</label><br>
                        <input autocomplete="off" type="text" class="form-control form-control-solid w-100 " id="phone"
                               name="old_phone" value="{{old('phone', $address->phone)}}"
                               placeholder="{{trans('backend.user.phone')}}"/>
                        @error('phone') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                    </div>

                </div>
                <!--begin::Col-->
                <div class=" col-12 col-md-6 fv-row fv-plugins-icon-container">
                    <!--begin::Label-->
                    <label class="fs-5 fw-bold mb-2">{{trans('backend.address.postal_code')}}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input class="form-control form-control-solid" placeholder="" value="{{old('postal_code', $address->postal_code)}}" name="postal_code">
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
                               @if(old('is_default',$address->is_default) == 1 )checked @endif>
                        <!--end::Input-->
                        <!--begin::Label-->
                        <span class="form-check-label fw-bold text-muted">Yes</span>
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
        <button type="submit" class="btn btn-primary">
            <span class="indicator-label">{{trans('backend.global.save')}}</span>
        </button>
        <!--end::Button-->
    </div>
    <!--end::Modal footer-->
    <div></div>
</form>

