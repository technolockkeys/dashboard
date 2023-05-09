<div class="app-navbar-item ms-1 ms-lg-3">
    <!--begin::Menu wrapper-->
    <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40p position-relative"
         data-kt-menu-trigger="click" data-kt-menu-attach="parent"
         data-kt-menu-placement="bottom-end">
        <!--begin::Svg Icon | path: icons/duotune/general/gen022.svg-->
        <span class="svg-icon svg-icon-1">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
												<path d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z"
                                                      fill="currentColor"></path>
												<path d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z"
                                                      fill="currentColor"></path>
												<path opacity="0.3"
                                                      d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z"
                                                      fill="currentColor"></path>
												<path opacity="0.3"
                                                      d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z"
                                                      fill="currentColor"></path>
											</svg>
										</span>

        <!--end::Svg Icon-->
    </div>
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column w-250px w-lg-325px"
         data-kt-menu="true">
        <div class="d-flex flex-column bgi-no-repeat pt-2 rounded-top bg-dark">
            <!--begin::Title-->
            {{--                                    <h3 class="text-white fw-semibold px-9 mt-10 mb-6">Notifications--}}
            {{--                                        <span class="fs-8 opacity-75 ps-3">24 reports</span></h3>--}}
            <!--end::Title-->
            <!--begin::Tabs-->
            <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9"
                role="tablist">

                <li class="nav-item" role="presentation">
                    <a class="nav-link text-white text-md opacity-75 opacity-state-100 pb-4 active"
                       data-bs-toggle="tab" href="#kt_topbar_notifications_2"
                       aria-selected="true"
                       role="tab">{{trans('seller.calculate_shipping_cost')}}</a>
                </li>

            </ul>
            <!--end::Tabs-->
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="kt_topbar_notifications_2" role="tabpanel">
                <div class="scroll-y mh-325px my-5 px-8 ">

                    <div class="col form-group">
                        <label for="shipping-country"
                               class="col-form-label form-label">{{trans('seller.product.countries')}}</label>
                        <select class="form-control form-control-sm" name="shipping-country"
                                data-control="select2" id="shipping-country">
                            <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                            @foreach(get_countries() as $key => $country)
                                <option value="{{$key}}">{{$country}}</option>
                            @endforeach
                        </select>
                        <b class="text-danger" id="shipping-country-error"> </b>
                    </div>
                    <div class="col form-group mt-2 " id="display-quantity">
                        <label for="shipping-weight"
                               class="col-form-label form-label">{{trans('seller.product.weight')}}
                            (KG)</label>
                        <input type="number" step="0.0001" name="shipping-weight" id="shipping-weight"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="shipping-weight-error"> </b>
                    </div>
                    <div class="col form-group mt-2 " id="display-shipping">
                        <table id="display-shipping-table" style="width: 100%;">

                        </table>
                    </div>
                    <button class="btn btn-primary mt-2 "
                            id="get-shipping-price">{{trans('seller.get_shipping_price')}}</button>

                </div>
            </div>

        </div>
    </div>
    <!--end::Menu-->
    <!--end::Menu wrapper-->
</div>
